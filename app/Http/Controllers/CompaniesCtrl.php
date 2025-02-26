<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Requests\CompaniesRqt;
use App\Models\Companies;
use App\Models\Buildings;
use App\Models\BuildingsPartners;
use App\Models\BuildingsKeys;
use App\Models\LeadsOrigins;
use App\Models\UsersLogs;

class CompaniesCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
        $this->middleware('can:access_komuh');
        $this->middleware('can:companies_show', ['only' => ['index', 'data', 'show']]);
        $this->middleware('can:companies_create', ['only' => ['create', 'store']]);
        $this->middleware('can:companies_update', ['only' => ['edit', 'update']]);
        $this->middleware('can:companies_destroy', ['only' => ['destroy']]);
	}
    
    public function index()
    {
        return view('companies.index');
    }

    public function data(Request $request)
    {   
        // Page Length
        $pageLength = $request->limit;
        $skip       = $request->offset;

        // Get data from companies all
        $companies = Companies::orderBy('name', 'asc');
        $recordsTotal = Companies::count();

        // Search
        $search = $request->search;
        $companies = $companies->where( function($companies) use ($search){
            $companies->orWhere('companies.name', 'like', "%".$search."%");
            $companies->orWhere('companies.slug', 'like', "%".$search."%");
        });

        // Apply Length and Capture RecordsFilters
        $recordsFiltered = $recordsTotal = $companies->count();
        $companies = $companies->skip($skip)->take($pageLength)->get();

        if( $companies->first() ){
            foreach($companies as $company) {
                // Status
                if( $company->active ) {
                    $status = '<span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">Ativo</span>';
                } else { 
                    $status = '<span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">Desativado</span>';
                } 
            
                // Operações
                $operations = '';
                if (Gate::any(['companies_update', 'companies_destroy'])) {
                    $operations .= '<div class="d-flex justify-content-center align-items-center gap-2">';

                    if( Gate::check('companies_update') ) {
                        $operations .= '<a href="'. route('companies.edit', $company->id ) .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a>';
                    }
                    if( Gate::check('companies_destroy') ) {
                        $operations .= '<a href="'. route('companies.destroy', $company->id ) .'" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a>';
                    }

                    $operations .= '</div>';
                } else {
                    $operations = '-';
                }

                // Array do emp
                $array[] = [
                    'id' => $company->id,
                    'name' => $company->name,
                    'slug' => $company->slug,
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
        return view('companies.create');
    }

    public function store(CompaniesRqt $request)
    {      
        $company = Companies::create([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
        ]);

        LeadsOrigins::create([
            'active' => 1,
            'name' => 'Default',
            'slug' => 'default',
            'companies_id' => $company->id,
        ]);

        $building = Buildings::create([
            'active' => 1,
            'name' => 'Default', 
            'test_buildings_id' => null,
        ]);

        BuildingsPartners::create([
            'main' => 1, 
            'leads' => 99, 
            'companies_id' => $company->id, 
            'buildings_id' => $building->id, 
        ]);

        BuildingsKeys::create([
            'active' => 1,
            'value' => 'default'.$request->slug, 
            'buildings_id' => $building->id, 
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de nova empresa',
            'description' => 'Foi realizado o cadastro de uma nova empresa: ' . $request->name . '.',
            'action' => 'create',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('companies.index')->with('create', true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        return view('companies.edit')->with('company', Companies::find($id));
    } 

    public function update(CompaniesRqt $request, string $id)
    {
        Companies::find($id)->update([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização das informações da empresa',
            'description' => 'Foi realizado a atualização das informações da empresa: ' . $request->name . '.',
            'action' => 'update',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('companies.index')->with('edit', true);
    }

    public function destroy(string $id)
    {     
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão da empresa',
            'description' => 'Foi realizado a exclusão da empresa: ' .  Companies::find($id)->name . '.',
            'action' => 'destroy',
            'users_id' => Auth::user()->id
        ]);

        Companies::find($id)->delete();
        return redirect()->route('companies.index')->with('destroy', true);
    }
}
