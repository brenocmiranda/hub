<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Requests\LeadsOriginsRqt;
use App\Models\Companies;
use App\Models\LeadsOrigins;
use App\Models\UsersLogs;

class LeadsOriginsCtrl extends Controller
{
    public function __construct(){
		$this->middleware('auth');
        $this->middleware('can:origins_show', ['only' => ['index', 'data', 'show']]);
        $this->middleware('can:origins_create', ['only' => ['create', 'store']]);
        $this->middleware('can:origins_update', ['only' => ['edit', 'update']]);
        $this->middleware('can:origins_destroy', ['only' => ['destroy']]);
	}
    
    public function index()
    {
        return view('leads.origins.index');
    }

    public function data(Request $request)
    {   
        // Page Length
        $pageLength = $request->limit;
        $skip       = $request->offset;

        // Get data from leads origins all
        if( Gate::check('access_komuh') ) {
            $origins = LeadsOrigins::orderBy('leads_origins.name', 'asc')
                                    ->join('companies', 'leads_origins.companies_id', '=', 'companies.id')
                                    ->select('leads_origins.*', 'companies.name as companie');
        } else {
            $origins = LeadsOrigins::orderBy('leads_origins.name', 'asc')
                                    ->where('companies_id', Auth::user()->companies_id);
        }
        $recordsTotal = LeadsOrigins::count();

        // Search
        $search = $request->search;
        $origins = $origins->where( function($origins) use ($search){
            $origins->orWhere('leads_origins.name', 'like', "%".$search."%");
            $origins->orWhere('leads_origins.slug', 'like', "%".$search."%");
            
            if( Gate::check('access_komuh') ) {
                $origins->orWhere('companies.name', 'like', "%".$search."%");
            }
        });

        // Apply Length and Capture RecordsFilters
        $recordsFiltered = $recordsTotal = $origins->count();
        $origins = $origins->skip($skip)->take($pageLength)->get();

        if( $origins->first() ){
            foreach($origins as $origin) {
                // Status
                if( $origin->active ) {
                    $status = '<span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">Ativo</span>';
                } else { 
                    $status = '<span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">Desativado</span>';
                } 
            
                // Operações
                $operations = '';
                if (Gate::any(['origins_update', 'origins_destroy'])) {
                    $operations .= '<div class="d-flex justify-content-center align-items-center gap-2">';

                    if( Gate::check('origins_update') ) {
                        $operations .= '<a href="'. route('leads.origins.edit', $origin->id ) .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a>';
                    }
                    if( Gate::check('origins_destroy') ) {
                        $operations .= '<a href="'. route('leads.origins.destroy', $origin->id ) .'" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a>';
                    }

                    $operations .= '</div>';
                } else {
                    $operations = '-';
                }
                
                // Array do emp
                $array[] = [
                    'name' => $origin->name,
                    'companie' => Gate::check('access_komuh') ? $origin->companie : '-',
                    'slug' => $origin->slug,
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
        return view('leads.origins.create')->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get());
    }

    public function store(LeadsOriginsRqt $request)
    {      
        LeadsOrigins::create([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
            'companies_id' => Gate::check('access_komuh') ? $request->companie : Auth::user()->companies_id,  
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de nova origem',
            'description' => 'Foi realizado o cadastro de uma nova origem: ' . $request->name . '.',
            'action' => 'create',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('leads.origins.index')->with('create', true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        return view('leads.origins.edit')->with('origin', LeadsOrigins::find($id))->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get());
    } 

    public function update(LeadsOriginsRqt $request, string $id)
    {
        LeadsOrigins::find($id)->update([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
            'companies_id' => Gate::check('access_komuh') ? $request->companie : Auth::user()->companies_id,  
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização das informações da origem',
            'description' => 'Foi realizado a atualização das informações da origem: ' . $request->name . '.',
            'action' => 'update',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('leads.origins.index')->with('edit', true);
    }

    public function destroy(string $id)
    {      
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão da origem',
            'description' => 'Foi realizado a exclusão da origem: ' .  LeadsOrigins::find($id)->name . '.',
            'action' => 'destroy',
            'users_id' => Auth::user()->id
        ]);

        LeadsOrigins::find($id)->delete();
        return redirect()->route('leads.origins.index')->with('destroy', true);
    }
}
