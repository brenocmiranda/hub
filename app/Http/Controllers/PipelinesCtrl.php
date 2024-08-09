<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Pipelines;
use App\Models\UsersLogs;

class PipelinesCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
        $this->middleware('can:pipelines_show', ['only' => ['index', 'data', 'show']]);
        $this->middleware('can:pipelines_create', ['only' => ['create', 'store']]);
        $this->middleware('can:pipelines_update', ['only' => ['edit', 'update']]);
        $this->middleware('can:pipelines_destroy', ['only' => ['destroy']]);
        $this->middleware('can:pipelines_resetAll,access_komuh', ['only' => ['resetAll']]);
	}

    public function index()
    {
        $requestSuccess = DB::table('job_batches')->where('pending_jobs', '=', 0)->count();
        $requestFail = DB::table('job_batches')->where('pending_jobs', '>', 0)->where('failed_jobs', '>', 0)->count();
        $requestPending = DB::table('job_batches')->count() - $requestSuccess - $requestFail;

        return view('leads.pipelines.index')->with('requestPending', $requestPending)->with('requestSuccess', $requestSuccess)->with('requestFail', $requestFail);
    }

    public function data(Request $request)
    {   
        // Page Length
        $pageLength = $request->limit;
        $skip       = $request->offset;

        // Get data from pipelines all
        if( Gate::check('access_komuh') ) {
            $pipelines = Pipelines::orderBy('created_at', 'desc')
                                ->join('leads', 'pipelines.leads_id', '=', 'leads.id')
                                ->leftJoin('leads_origins', 'leads.id', '=', 'leads_origins.id')
                                ->leftJoin('companies', 'leads.companies_id', '=', 'companies.id')
                                ->leftJoin('integrations', 'pipelines.integrations_id', '=', 'integrations.id')
                                ->select('pipelines.*', 'integrations.name as integration', 'leads.name as lead', 'leads_origins.name as origin', 'companies.name as companie');
        } else {
            $pipelines = Pipelines::orderBy('created_at', 'desc')
                                ->join('leads', 'pipelines.leads_id', '=', 'leads.id')
                                ->leftJoin('leads_origins', 'leads.id', '=', 'leads_origins.id')
                                ->leftJoin('companies', 'leads.companies_id', '=', 'companies.id')
                                ->leftJoin('integrations', 'pipelines.integrations_id', '=', 'integrations.id')
                                ->where('leads.companies_id', Auth::user()->companies_id)
                                ->select('pipelines.*', 'integrations.name as integration', 'leads.name as lead', 'leads_origins.name as origin');
        }
        $recordsTotal = Pipelines::count();

        // Search
        $search = $request->search;
        $pipelines = $pipelines->where( function($pipelines) use ($search){
            $pipelines->orWhere('pipelines.statusCode', 'like', "%".$search."%");
            $pipelines->orWhere('leads.name', 'like', "%".$search."%");
            $pipelines->orWhere('leads_origins.name', 'like', "%".$search."%");
            $pipelines->orWhere('integrations.name', 'like', "%".$search."%");

            if( Gate::check('access_komuh') ) {
                $pipelines->orWhere('companies.name', 'like', "%".$search."%");
            }
        });

        // Apply Length and Capture RecordsFilters
        $recordsFiltered = $recordsTotal = $pipelines->count();
        $pipelines = $pipelines->skip($skip)->take($pageLength)->get();
        
        if ($pipelines->first()){
            foreach($pipelines as $pipeline) {
                // Integration
                if ($pipeline->statusCode == 0){
                    $integration = "Payload (" . $pipeline->RelationIntegrations->name . ")";
                }else if( $pipeline->statusCode == 1){
                    $integration = "Disparo de e-mail";
                }else if( $pipeline->statusCode == 2){
                    $integration = "Google Sheets";
                }else if( $pipeline->RelationIntegrations ){
                    $integration = $pipeline->RelationIntegrations->name;
                }

                // Operações
                $operations = '';
                if ( Gate::any(['pipelines_show']) ) {
                    $operations .= '<div class="d-flex justify-content-center align-items-center gap-2">';

                    
                        $operations .= '<a href="' . route('leads.pipelines.show', $pipeline->id ) .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Visualizar"><i class="bi bi-eye"></i></a>';
                    

                    $operations .= '</div>';
                } else {
                    $operations = '-';
                }

                $operations = '<div class="d-flex justify-content-center align-items-center gap-2"></div>';
                
                // Array do emp
                $array[] = [
                    'date'  => $pipeline->created_at->format("d/m/Y H:i:s"),
                    'companie' => Gate::check('access_komuh') ? $pipeline->companie : '-',
                    'status' => $pipeline->statusCode, 
                    'integration' => $integration, 
                    'origin' => $pipeline->origin,
                    'lead'=> $pipeline->lead,
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
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        // Salvando log
        UsersLogs::create([
            'title' => 'Visualização de pipeline',
            'description' => 'Foi realizada a visualização das informações da pipeline ' . Pipelines::find($id)->statusCode == 1 ? "Disparo de e-mail" : (Pipelines::find($id)->statusCode == 2 ? "Google Sheets" : ( Pipelines::find($id)->RelationIntegrations ? Pipelines::find($id)->RelationIntegrations->name : "" )) . ' do lead ' . Pipelines::find($id)->RelationLeads->name . '.',
            'action' => 'show',
            'users_id' => Auth::user()->id
        ]);

        return view('leads.pipelines.show')->with('pipeline', Pipelines::find($id));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function retryAll()
    { 
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
}
