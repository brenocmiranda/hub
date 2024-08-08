<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Requests\IntegrationsRqt;
use App\Models\Integrations;
use App\Models\Companies;
use App\Models\UsersLogs;

class IntegrationsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
        $this->middleware('can:integrations_show', ['only' => ['index', 'data', 'show']]);
        $this->middleware('can:integrations_create', ['only' => ['create', 'store']]);
        $this->middleware('can:integrations_update', ['only' => ['edit', 'update']]);
        $this->middleware('can:integrations_destroy', ['only' => ['destroy']]);
	}
    
    public function index()
    {
        return view('integrations.index');
    }

    public function data(Request $request)
    {   
        // Page Length
        $pageLength = $request->limit;
        $skip       = $request->offset;

        // Get data from integrations all
        if( Gate::check('access_komuh') ) {
            $integrations = Integrations::orderBy('companies.name', 'asc')->orderBy('integrations.name', 'asc')
                                            ->join('companies', 'integrations.companies_id', '=', 'companies.id')
                                            ->select('integrations.*', 'companies.name as companie');
        } else {
            $integrations = Integrations::orderBy('integrations.name', 'asc')
                                            ->where('companies_id', Auth::user()->companies_id);                  
        }
        $recordsTotal = Integrations::count();

        // Search
        $search = $request->search;
        $integrations = $integrations->where( function($integrations) use ($search){
            $integrations->orWhere('integrations.name', 'like', "%".$search."%");
            $integrations->orWhere('integrations.type', 'like', "%".$search."%");
            $integrations->orWhere('integrations.url', 'like', "%".$search."%");
            
            if( Gate::check('access_komuh') ) {
                $integrations->orWhere('companies.name', 'like', "%".$search."%");
            }
        });

        // Apply Length and Capture RecordsFilters
        $recordsFiltered = $recordsTotal = $integrations->count();
        $integrations = $integrations->skip($skip)->take($pageLength)->get();

        if( $integrations->first() ){
            foreach($integrations as $integration) {
                // Status
                if( $integration->active ) {
                    $status = '<span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">Ativo</span>';
                } else { 
                    $integration = '<span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">Desativado</span>';
                } 
            
                // Operações
                $operations = '';
                if (Gate::any(['integrations_update', 'integrations_destroy'])) {
                    $operations .= '<div class="d-flex justify-content-center align-items-center gap-2">';

                    if( Gate::check('integrations_update') ) {
                        $operations .= '<a href="'. route('integrations.edit', $integration->id ) .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a>';
                    }
                    if( Gate::check('integrations_destroy') ) {
                        $operations .= '<a href="'. route('integrations.destroy', $integration->id ) .'" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a>';
                    }

                    $operations .= '</div>';
                } else {
                    $operations = '-';
                }
                
                // Array do emp
                $array[] = [
                    'name' => $integration->name,
                    'companie' => Gate::check('access_komuh') ? $integration->companie : '-',
                    'type' => $integration->type,
                    'url' => mb_strimwidth($integration->url, 0, 100, "..."),
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
        return view('integrations.create')->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get());
    }

    public function store(IntegrationsRqt $request)
    {      
        Integrations::create([
            'type' => $request->type, 
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
            'encoded' => $request->encoded, 
            'url' => $request->url,
            'user' => $request->user ? $request->user : "",
            'password' => $request->password ? $request->password : "",
            'token' => $request->token ? $request->token : "",
            'header' => $request->header ? $request->header : "",
            'companies_id' => Gate::check('access_komuh') ? $request->companie : Auth::user()->companies_id,  
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de nova integração',
            'description' => 'Foi realizado o cadastro de uma nova integração: ' . $request->name . '.',
            'action' => 'create',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('integrations.index')->with('create', true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        return view('integrations.edit')->with('integration', Integrations::find($id))->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get());
    } 

    public function update(IntegrationsRqt $request, string $id)
    {
        Integrations::find($id)->update([
            'type' => $request->type, 
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
            'encoded' => $request->encoded, 
            'url' => $request->url,
            'user' => $request->user ? $request->user : "",
            'password' => $request->password ? $request->password : "",
            'token' => $request->token ? $request->token : "",
            'header' => $request->header ? $request->header : "",
            'companies_id' => Gate::check('access_komuh') ? $request->companie : Auth::user()->companies_id,  
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização das informações da integração',
            'description' => 'Foi realizado a atualização das informações da integração: ' . $request->name . '.',
            'action' => 'update',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('integrations.index')->with('edit', true);
    }

    public function destroy(string $id)
    {      
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão da integração',
            'description' => 'Foi realizado a exclusão da integração: ' .  Integrations::find($id)->name . '.',
            'action' => 'destroy',
            'users_id' => Auth::user()->id
        ]);
        
        Integrations::find($id)->delete();
        return redirect()->route('integrations.index')->with('destroy', true);
    }
}
