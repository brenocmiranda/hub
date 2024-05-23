<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LeadsRqt;
use App\Models\Buildings;
use App\Models\BuildingsPartners;
use App\Models\Companies;
use App\Models\Leads;
use App\Models\LeadsFields;
use App\Models\LeadsOrigins;
use App\Models\UsersLogs;
use App\Models\Pipelines;
use App\Models\PipelinesLog;
use App\Jobs\ProcessBuildingJobs;

class LeadsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('leads.index')->with('leads', Leads::select('created_at', 'name', 'email', 'companies_id', 'buildings_id', 'leads_origins_id', 'batches_id', 'id')->orderBy('created_at', 'desc')->get());
    }

    public function create()
    {   
        $companies = Companies::where('active', 1)->orderBy('name', 'asc')->get();
        $buildings = Buildings::where('active', 1)->orderBy('name', 'asc')->get();
        
        foreach($buildings as $building){
            $building->companie = BuildingsPartners::where('buildings_id', $building->id)->where('main', 1)->first()->companies_id;
        }
        
        foreach($companies as $companie){
            foreach($buildings as $building){ 
                if( $companie->id == $building->companie ){
                    $array[$companie->name][] = $building;
                }
            }
        } 

        return view('leads.create')->with('origins', LeadsOrigins::where('active', 1)->orderBy('name', 'asc')->get())->with('array', isset($array) ? $array : null);
    }

    public function store(LeadsRqt $request)
    {   
        // Defined partner responsible
        $partners = BuildingsPartners::where( 'buildings_id', $request->building )->orderBy('created_at', 'desc')->get();
        if( $partners->first() ){
            foreach( $partners as $partner ){
                if( $partner->leads == 99 ){
                    $companie = $partner->companies_id;
                    break;
                } else {
                    $countAllPartners = BuildingsPartners::where( 'buildings_id', $request->building )->select('leads')->sum('leads');
                    $leads = Leads::where( 'buildings_id', $request->building )->orderBy('created_at', 'desc')->take( $countAllPartners - 1)->get();
                    $leadsPartner = $leads->sortBy('created_at')->where( 'companies_id', $partner->companies_id )->count();
 
                    if( $leadsPartner < $partner->leads ){
                        $companie = $partner->companies_id;
                        break;
                    }
                }
            }
        }   
        $companie = isset($companie) ? $companie : BuildingsPartners::where( 'buildings_id', $request->building )->where('main', 1)->first()->companies_id;

        $tel = preg_replace( '/\D/', '', str_replace( '+55', '', $request->phone ));
	    $phone = strlen( $tel ) < 10  ? substr( $tel . str_repeat( '9', 11 ), 0, 11 ) : $tel;

        $lead = Leads::create([
            'api' => false, 
            'name' => ucwords($request->name), 
            'phone' => $phone, 
            'email' => strtolower($request->email),
            'buildings_id' => $request->building,
            'leads_origins_id' => $request->origin,
            'companies_id' => $companie,
        ]);

        // Cadastrando novas integrações e novos campos
        $fields = $request->input('array');
        if(isset($fields)){
            foreach($fields["nameField"] as $index => $field) {
                LeadsFields::create([
                    'name' => $fields["nameField"][$index], 
                    'value' => $fields["valueField"][$index],
                    'leads_id' => $lead->id,
                ]);
            }
        }

        // Enviando para as execução das integrações
        ProcessBuildingJobs::dispatch($lead->id);

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de novo lead',
            'description' => 'Foi realizado o cadastro de um novo lead: ' . $request->name . '.',
            'action' => 'create',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('leads.index')->with('create', true);
    }

    public function show(string $id)
    {   
        // Salvando log
        UsersLogs::create([
            'title' => 'Visualização de lead',
            'description' => 'Foi realizada a visualização das informações do lead: ' . Leads::find($id)->name . '.',
            'action' => 'show',
            'users_id' => Auth::user()->id
        ]);

        return view('leads.show')->with('lead', Leads::find($id));
    }

    public function edit(string $id)
    {      
        //
    } 

    public function update(LeadsRqt $request, string $id)
    {
       //
    }

    public function destroy(string $id)
    {   

        $pipelines = Pipelines::where('leads_id', $id)->get();
        foreach( $pipelines as $pipeline ) {
            PipelinesLog::where( 'pipelines_id', $pipeline->id )->delete();
        }
        Pipelines::where('leads_id', $id)->delete();
        LeadsFields::where('leads_id', $id)->delete();
        Leads::find($id)->delete();

        return redirect()->route('leads.index')->with('destroy', true);
    }

    public function search(){
        $term = $_GET['search'];
        $leads= Leads::where('name', 'like', '%'.$term.'%')->orWhere('phone', 'like', '%'.$term.'%')->orWhere('email', 'like', '%'.$term.'%')->select( 'name', 'id' )->limit(5)->get();

        foreach($leads as $index => $lead) {
            $leads[$index]['url'] = route('leads.show', $lead->id);
        }
        return $leads;
    }

    public function retryAll(){ 
        Artisan::call('queue:retry', ['id' => ['all']]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Tentar novamente todos',
            'description' => 'Foi realizado uma nova tentativa de integração de todos os lead com erro.',
            'action' => 'retryAll',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('leads.pipelines.index')->with( 'retryAll', true );
    }

    // Problem
    public function retry($id){ 
        $lead = Leads::find($id);
        $batches = $lead->batches_id;
        Artisan::call('queue:retry-batch', ['id' => $batches]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Tentar novamente',
            'description' => 'Foi realizado uma nova tentativa de integração do lead: ' . $lead->name . '.',
            'action' => 'retry',
            'users_id' => Auth::user()->id
        ]);
        
        return redirect()->back()->with( 'retry', true );
    }

    public function resend($id){ 
        ProcessBuildingJobs::dispatch($id);

        // Salvando log
        UsersLogs::create([
            'title' => 'Reenvio do lead',
            'description' => 'Foi realizado o reenvio do lead: ' . Leads::find($id)->name . '.',
            'action' => 'resend',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('leads.show', $id)->with( 'resend', true );
    }
}