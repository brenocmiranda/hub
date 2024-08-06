<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UsersRolesRqt;
use App\Models\Companies;
use App\Models\UsersRoles;
use App\Models\UsersLogs;

class UsersRolesCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {   
        return view('users.roles.index')->with('roles', UsersRoles::orderBy('active', 'desc')->orderBy('name', 'asc')->get());
    }

    public function data(Request $request)
    {   
        // Page Length
        $pageLength = $request->limit;
        $skip       = $request->offset;

        // Get data from companies all
        $roles = UsersRoles::orderBy('created_at', 'desc')
                                ->join('companies', 'users_roles.companies_id', '=', 'companies.id')
                                ->select('users_roles.*', 'companies.name as companie');
        $recordsTotal = UsersRoles::count();

        // Search
        $search = $request->search;
        $roles = $roles->where( function($roles) use ($search){
            $roles->orWhere('users_roles.name', 'like', "%".$search."%");
            $roles->orWhere('users_roles.roles', 'like', "%".$search."%");
            $roles->orWhere('companies.name', 'like', "%".$search."%");
        });

        // Apply Length and Capture RecordsFilters
        $recordsFiltered = $recordsTotal = $roles->count();
        $roles = $roles->skip($skip)->take($pageLength)->get();

        if( $roles->first() ){
            foreach($roles as $role) {
                // Status
                if( $role->active ) {
                    $status = '<span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">Ativo</span>';
                } else { 
                    $status = '<span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">Desativado</span>';
                } 
            
                // Operações
                $operations = '<div class="d-flex justify-content-center align-items-center gap-2"><a href="'. route('users.roles.edit', $role->id ) .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a><a href="'. route('users.roles.destroy', $role->id ) .'" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a></div>';
                
                // Array do emp
                $array[] = [
                    'name' => $role->name,
                    'companie' => $role->companie,
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
        return view('users.roles.create')->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get());
    }

    public function store(UsersRolesRqt $request)
    {      
        UsersRoles::create([
            'name' => $request->name, 
            'roles' => implode(',', $request->roles), 
            'active' => $request->active,
            'companies_id' => $request->companie, 
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de nova regra',
            'description' => 'Foi realizado o cadastro de uma nova regra: ' . $request->name . '.',
            'action' => 'create',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('users.roles.index')->with('create', true);
    }
    
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        return view('users.roles.edit')->with('role', UsersRoles::find($id))->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get());
    } 

    public function update(UsersRolesRqt $request, string $id)
    {
        UsersRoles::find($id)->update([
            'name' => $request->name, 
            'roles' => implode(',', $request->roles), 
            'active' => $request->active,
            'companies_id' => $request->companie, 
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização das informações da regra',
            'description' => 'Foi realizado a atualização das informações da regra: ' . $request->name . '.',
            'action' => 'update',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('users.roles.index')->with('edit', true);
    }

    public function destroy(string $id)
    {   
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão da regra',
            'description' => 'Foi realizado a exclusão da regra: ' .  UsersRoles::find($id)->name . '.',
            'action' => 'destroy',
            'users_id' => Auth::user()->id
        ]);

        UsersRoles::find($id)->delete();
        return redirect()->route('users.roles.index')->with('destroy', true);
    }
}
