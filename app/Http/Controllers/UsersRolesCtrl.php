<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UsersRolesRqt;
use App\Models\UsersRoles;
use App\Models\UsersLogs;

class UsersRolesCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {   
        return view('users.roles.index')->with('roles', UsersRoles::orderBy('name', 'asc')->get());
    }

    public function create()
    {      
        return view('users.roles.create');
    }

    public function store(UsersRolesRqt $request)
    {      
        UsersRoles::create([
            'name' => $request->name, 
            'value' => $request->value, 
            'active' => $request->active,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastrado de nova regra',
            'action' => 'Foi realizado o cadastro de uma nova regra: ' . $request->name . '.',
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('users.roles.index')->with('create', true);
    }
    
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        return view('users.roles.edit')->with('role', UsersRoles::find($id));
    } 

    public function update(UsersRolesRqt $request, string $id)
    {
        UsersRoles::find($id)->update([
            'name' => $request->name, 
            'value' => $request->value, 
            'active' => $request->active,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização das informações da regra',
            'action' => 'Foi realizado a atualização das informações da regra: ' . $request->name . '.',
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('users.roles.index')->with('edit', true);
    }

    public function destroy(string $id)
    {      
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão da regra',
            'action' => 'Foi realizado a exclusão da regra: ' .  UsersRoles::find($id)->name . '.',
            'user_id' => Auth::user()->id
        ]);

        UsersRoles::find($id)->delete();
        return redirect()->route('users.roles.index')->with('destroy', true);
    }
}
