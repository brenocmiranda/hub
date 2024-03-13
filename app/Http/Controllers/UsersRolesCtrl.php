<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\UsersRolesRqt;
use App\Models\UsersRoles;

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

        return redirect()->route('index.users.roles')->with('create', true);
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

        return redirect()->route('index.users.roles')->with('edit', true);
    }

    public function destroy(string $id)
    {      
        UsersRoles::find($id)->delete();
        return redirect()->route('index.users.roles')->with('destroy', true);
    }
}
