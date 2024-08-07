<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Http\Request;
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
        $this->middleware('can:leads_show', ['only' => ['index', 'data', 'search', 'show']]);
        $this->middleware('can:leads_create', ['only' => ['create', 'store']]);
        $this->middleware('can:leads_update', ['only' => ['edit', 'update']]);
        $this->middleware('can:leads_destroy', ['only' => ['destroy']]);
        $this->middleware('can:leads_retry', ['only' => ['retry']]);
        $this->middleware('can:leads_resend', ['only' => ['resend']]);
	}
    
    public function index()
    {   
        return view('leads.index');
    }

    public function data(Request $request)
    {   
        // Page Length
        $pageLength = $request->limit;
        $skip       = $request->offset;

        // Get data from leads all
        $leads = Leads::orderBy('created_at', 'desc')
                        ->join('leads_origins', 'leads.leads_origins_id', '=', 'leads_origins.id')
                        ->join('buildings', 'leads.buildings_id', '=', 'buildings.id')
                        ->join('companies', 'leads.companies_id', '=', 'companies.id')
                        ->select('leads.*', 'leads_origins.name as origin', 'buildings.name as building', 'companies.name as companie');
        $recordsTotal = Leads::count();

        // Search
        $search = $request->search;
        $leads = $leads->where( function($leads) use ($search){
            $leads->orWhere('leads.name', 'like', "%".$search."%");
            $leads->orWhere('leads.email', 'like', "%".$search."%");
            $leads->orWhere('leads.phone', 'like', "%".$search."%");
            $leads->orWhere('companies.name', 'like', "%".$search."%");
            $leads->orWhere('buildings.name', 'like', "%".$search."%");
            $leads->orWhere('leads_origins.name', 'like', "%".$search."%");
        });

        // Apply Length and Capture RecordsFilters
        $recordsFiltered = $recordsTotal = $leads->count();
        $leads = $leads->skip($skip)->take($pageLength)->get();
        
        if( $leads->first() ){
            foreach($leads as $lead) {
                // Status
                if( $lead->batches_id ) {
                    if (Bus::findBatch($lead->batches_id)->failedJobs > 0 && Bus::findBatch($lead->batches_id)->pendingJobs > 0 ){
                        $status = '<span class="badge border rounded-pill bg-danger-subtle border-danger-subtle text-danger-emphasis"> <i class="bi bi-x-octagon px-1"></i> Erro </span>';
                    } elseif (Bus::findBatch($lead->batches_id)->pendingJobs > 0 ) {
                        $status = '<span class="badge border rounded-pill bg-secondary-subtle border-secondary-subtle text-secondary-emphasis"> <i class="bi bi-gear-wide-connected px-1"></i> Executando </span>';
                    } elseif (Bus::findBatch($lead->batches_id)->pendingJobs === 0 ){
                        $status = '<span class="badge border rounded-pill bg-success-subtle border-success-subtle text-success-emphasis"> <i class="bi bi-check2-circle px-1"></i> Finalizado </span>';
                    }  
                } else { 
                    $status = '<span class="badge border rounded-pill bg-info-subtle border-info-subtle text-info-emphasis"> <i class="bi bi-box-seam px-1"></i> Na fila </span>';
                } 

                // Operações
                $operations = '<div class="d-flex justify-content-center align-items-center gap-2"> <a href="' . route('leads.show', $lead->id ) . '" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Visualizar"><i class="bi bi-eye"></i></a>' . ($lead->batches_id && Bus::findBatch($lead->batches_id)->failedJobs > 0 && Bus::findBatch($lead->batches_id)->pendingJobs > 0 ? '<a href="'. route('leads.retry', $lead->id ) .'" class="btn btn-outline-danger px-2 py-1 retry" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tentar Novamente"><i class="bi bi-arrow-repeat"></i></a>' : "") . '</div>';
                
                // Array do emp
                $array[] = [
                    'date'  => $lead->created_at->format("d/m/Y H:i:s"),
                    'origin' => $lead->origin, 
                    'companie' => $lead->companie, 
                    'building' => $lead->building, 
                    'name' => $lead->name,
                    'email'=> $lead->email,
                    'status' => $status,
                    'operations' => $operations
                ];
            }
        } else {
            $array = [];
        }

        return response()->json(["total" => $recordsTotal, "totalNotFiltered" => $recordsFiltered, 'rows' => $array], 200);
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

    public function search()
    {
        $term = $_GET['search'];
        $leads= Leads::where('name', 'like', '%'.$term.'%')->orWhere('phone', 'like', '%'.$term.'%')->orWhere('email', 'like', '%'.$term.'%')->select( 'name', 'id' )->limit(5)->get();

        foreach($leads as $index => $lead) {
            $leads[$index]['url'] = route('leads.show', $lead->id);
        }
        return $leads;
    }

    public function retry($id)
    { 
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

    public function resend($id)
    { 
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