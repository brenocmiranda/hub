<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\UsersRqt;
use App\Models\Companies;
use App\Models\Users;
use App\Models\UsersRoles;
use App\Models\UsersLogs;
use App\Mail\FirstAccess;
use App\Mail\RecoveryPassword;

class UsersCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
        $this->middleware('can:users_show', ['only' => ['index', 'data', 'show']]);
        $this->middleware('can:users_create', ['only' => ['create', 'store']]);
        $this->middleware('can:users_update', ['only' => ['edit', 'update']]);
        $this->middleware('can:users_destroy', ['only' => ['destroy']]);
        $this->middleware('can:users_recovery', ['only' => ['recovery']]);
	}

    public function index()
    {
        return view('users.index');
    }   

    public function data(Request $request)
    {   
        // Page Length
        $pageLength = $request->limit;
        $skip       = $request->offset;

        // Get data from companies all
        if( Gate::check('access_komuh') ) {
            $users = Users::orderBy('created_at', 'desc')
                                ->join('companies', 'users.companies_id', '=', 'companies.id')
                                ->join('users_roles', 'users.users_roles_id', '=', 'users_roles.id')
                                ->where('users.id', '!=', Auth::user()->id)
                                ->select('users.*', 'companies.name as companie', 'users_roles.name as role');
        } else {
            $users = Users::orderBy('created_at', 'desc')
                                ->join('users_roles', 'users.users_roles_id', '=', 'users_roles.id')
                                ->where('users.id', '!=', Auth::user()->id)
                                ->where('users.companies_id', Auth::user()->companies_id)
                                ->select('users.*', 'users_roles.name as role');
        }                        
        $recordsTotal = Users::count();

        // Search
        $search = $request->search;
        $users = $users->where( function($users) use ($search){
            $users->orWhere('users.name', 'like', "%".$search."%");
            $users->orWhere('users.email', 'like', "%".$search."%");
            $users->orWhere('users_roles.name', 'like', "%".$search."%");

            if( Gate::check('access_komuh') ) {
                $users->orWhere('companies.name', 'like', "%".$search."%");
            }
        });

        // Apply Length and Capture RecordsFilters
        $recordsFiltered = $recordsTotal = $users->count();
        $users = $users->skip($skip)->take($pageLength)->get();

        if( $users->first() ){
            foreach($users as $user) {
                // Status
                if( $user->active ) {
                    $status = '<span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">Ativo</span>';
                } else { 
                    $status = '<span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">Desativado</span>';
                } 
            
                // Operações
                $operations = '';
                if (Gate::any(['users_update', 'users_recovery', 'users_destroy'])) {
                    $operations .= '<div class="d-flex justify-content-center align-items-center gap-2">';

                    if( Gate::check('users_update') ) {
                        $operations .= '<a href="'. route('users.edit', $user->id ) .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a>';
                    }
                    if( Gate::check('users_recovery') ) {
                        $operations .= '<a href="'. route('users.recovery', $user->id ) .'" class="btn btn-outline-secondary px-2 py-1 recovery" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Redefinir senha"><i class="bi bi-envelope-arrow-up"></i></i></a>';
                    }
                    if( Gate::check('users_destroy') ) {
                        $operations .= '<a href="'. route('users.destroy', $user->id ) .'" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a>';
                    }

                    $operations .= '</div>';
                } else {
                    $operations = '-';
                }
                
                // Array do emp
                $array[] = [
                    'name' => $user->name,
                    'empresa' => Gate::check('access_komuh') ? $user->companie : '-',
                    'role' => $user->role,
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
        if( Gate::check('access_komuh') ) {
            $companies = Companies::where('active', 1)->orderBy('name', 'asc')->get();
        } else {
            $companies = Companies::where('id', Auth::user()->companies_id)->get();
        }

        $roles = UsersRoles::where('active', 1)->orderBy('name', 'asc')->get();

        foreach($companies as $companie){
            foreach($roles as $role){ 
                if( $companie->id == $role->companies_id ){
                    $array[$companie->name][] = $role;
                }
            }
        } 

        return view('users.create')->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get())->with('roles', isset($array) ? $array : null);
    }

    public function store(UsersRqt $request)
    {      
        $user = Users::create([
            'name' => $request->name, 
            'email' => $request->email, 
            'password' => Hash::make('komuh@220'), 
            'active' => $request->active,
            'companies_id' => Gate::check('access_komuh') ? $request->companie : Auth::user()->companies_id,  
            'users_roles_id' => $request->roles,
            'remember_token' => Str::random(10),
            'attempts' => 0,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de novo usuário',
            'description' => 'Foi realizado o cadastro de um novo usuário: ' . $request->name . '.',
            'action' => 'create',
            'users_id' => Auth::user()->id
        ]);
        Mail::to( $user->email )->send(new FirstAccess($user));

        return redirect()->route('users.index')->with('create', true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        if( Gate::check('access_komuh') ) {
            $companies = Companies::where('active', 1)->orderBy('name', 'asc')->get();
        } else {
            $companies = Companies::where('id', Auth::user()->companies_id)->get();
        }

        $roles = UsersRoles::where('active', 1)->orderBy('name', 'asc')->get();

        foreach($companies as $companie){
            foreach($roles as $role){ 
                if( $companie->id == $role->companies_id ){
                    $array[$companie->name][] = $role;
                }
            }
        } 

        return view('users.edit')->with('user', Users::find($id))->with('companies', Companies::where('active', 1)->get())->with('roles', isset($array) ? $array : null);
    }

    public function update(UsersRqt $request, string $id)
    {
        Users::find($id)->update([
            'name' => $request->name, 
            'email' => $request->email, 
            'active' => $request->active,
            'companies_id' => Gate::check('access_komuh') ? $request->companie : Auth::user()->companies_id,  
            'users_roles_id' => $request->roles,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização das informações do usuário',
            'description' => 'Foi realizado a atualização das informações do usuário: ' . $request->name . '.',
            'action' => 'update',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('users.index')->with('edit', true);
    }

    public function destroy(string $id)
    {   
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão de usuário',
            'description' => 'Foi realizado a exclusão da usuário: ' .  Users::find($id)->name . '.',
            'action' => 'destroy',
            'users_id' => Auth::user()->id
        ]);

        Users::find($id)->delete();
        return redirect()->route('users.index')->with('destroy', true);
    }

    public function recovery(string $id)
    {    
        $user = Users::find($id);

        // Salvando log
        UsersLogs::create([
            'title' => 'Enviando redefinição de senha',
            'description' => 'Foi realizado o envio do link de redefinição de senha para o usuário.',
            'action' => 'recovery',
            'users_id' => $user->id
        ]);

        Mail::to( $user->email )->send(new RecoveryPassword($user));

        return redirect()->route('users.index')->with('recovery', true);
    }
}
