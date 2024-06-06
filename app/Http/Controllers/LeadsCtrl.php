<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use App\Http\Requests\LeadsRqt;
use App\Models\Buildings;
use App\Models\Companies;
use App\Models\Leads;
use App\Models\LeadsFields;
use App\Models\LeadsOrigins;
use App\Models\UsersLogs;
use App\Jobs\ProcessBuildingJobs;

class LeadsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('leads.index');
    }

    public function data()
    {
        $leads = Leads::orderBy('created_at', 'desc')->get();
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

            $array[] = [
                'date'  => $lead->created_at->format("d/m/Y H:i:s"),
                'origin' => $lead->RelationOrigins->name, 
                'building' => $lead->RelationBuildings->name, 
                'name' => $lead->name,
                'email'=> $lead->email,
                'status' => $status,
                'operations' => $operations
            ];
        }
        return json_encode($array);
    }

    public function create()
    {   
        $companies = Companies::where('active', 1)->orderBy('name', 'asc')->get();
        $buildings = Buildings::where('active', 1)->orderBy('name', 'asc')->get();
        
        foreach($companies as $companie){
            foreach($buildings as $building){ 
                if($companie->id == $building->companie_id){
                    $array[$companie->name][] = $building;
                }
            }
        } 

        return view('leads.create')->with('origins', LeadsOrigins::where('active', 1)->orderBy('name', 'asc')->get())->with('array', isset($array) ? $array : null);
    }

    public function store(LeadsRqt $request)
    {   
        $tel = preg_replace( '/\D/', '', str_replace( '+55', '', $request->phone ));
	    $phone = strlen( $tel ) < 10  ? substr( $tel . str_repeat( '9', 11 ), 0, 11 ) : $tel;

        $lead = Leads::create([
            'api' => false, 
            'name' => $request->name, 
            'phone' => $phone, 
            'email' => $request->email,
            'building_id' => $request->building,
            'leads_origin_id' => $request->origin,
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
            'user_id' => Auth::user()->id
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
            'user_id' => Auth::user()->id
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
        //
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
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('leads.index')->with( 'retryAll', true );
    }

    public function retry($id){ 
        $lead = Leads::find($id);
        Artisan::call('queue:retry', ['id' => [ $lead->batches_id ]]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Tentar novamente',
            'description' => 'Foi realizado uma nova tentativa de integração do lead: ' . Leads::find($id)->name . '.',
            'action' => 'retry',
            'user_id' => Auth::user()->id
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
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('leads.show', $id)->with( 'resend', true );
    }
}