<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CompaniesRqt;
use App\Models\Companies;
use App\Models\UsersLogs;

class CompaniesCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
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
        $companies = Companies::orderBy('created_at', 'desc');
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
            foreach($companies as $companie) {
                // Status
                if( $companie->active ) {
                    $status = '<span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">Ativo</span>';
                } else { 
                    $status = '<span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">Desativado</span>';
                } 
            
                // Operações
                $operations = '<div class="d-flex justify-content-center align-items-center gap-2"><a href="'. route('companies.edit', $companie->id ) .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a><a href="'. route('companies.destroy', $companie->id ) .'" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a></div>';
                
                // Array do emp
                $array[] = [
                    'name' => $companie->name,
                    'slug' => $companie->slug,
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
        Companies::create([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
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
        return view('companies.edit')->with('companie', Companies::find($id));
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
