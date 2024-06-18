<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
	}

    public function index()
    {
        return view('users.index')->with('users', Users::orderBy('active', 'desc')->orderBy('name', 'asc')->where('id', '!=', Auth::user()->id)->get());
    }

    public function create()
    {      
        return view('users.create')->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get())->with('roles', UsersRoles::where('active', 1)->orderBy('name', 'asc')->get());
    }

    public function store(UsersRqt $request)
    {      
        $user = Users::create([
            'name' => $request->name, 
            'email' => $request->email, 
            'password' => Hash::make('komuh@220'), 
            'active' => $request->active,
            'companies_id' => $request->companies,
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
        return view('users.edit')->with('user', Users::find($id))->with('companies', Companies::where('active', 1)->get())->with('roles', UsersRoles::where('active', 1)->get());
    }

    public function update(UsersRqt $request, string $id)
    {
        Users::find($id)->update([
            'name' => $request->name, 
            'email' => $request->email, 
            'active' => $request->active,
            'companies_id' => $request->companies,
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
