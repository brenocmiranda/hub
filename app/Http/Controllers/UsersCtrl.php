<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UsersRqt;
use App\Models\Companies;
use App\Models\UsersRoles;
use App\Models\Users;

class UsersCtrl extends Controller
{
    public function index()
    {
        return view('users.index')->with('users', Users::orderBy('name', 'asc')->get());
    }

    public function create()
    {      
        return view('users.create')->with('companies', Companies::where('active', 1)->get())->with('roles', UsersRoles::where('active', 1)->get());
    }

    public function store(UsersRqt $request)
    {      
        Users::create([
            'name' => $request->name, 
            'email' => $request->email, 
            'password' => Hash::make('komuh@220'), 
            'active' => $request->active,
            'companie_id' => $request->companies,
            'user_role_id' => $request->roles,
            'attempts' => 0,
        ]);

        return redirect()->route('index.users')->with('create', true);
    }

    public function edit($id)
    {      
        return view('users.edit')->with('user', Users::find($id))->with('companies', Companies::where('active', 1)->get())->with('roles', UsersRoles::where('active', 1)->get());
    }

    public function update(UsersRqt $request, $id)
    {
        Users::find($id)->update([
            'name' => $request->name, 
            'email' => $request->email, 
            'active' => $request->active,
            'companie_id' => $request->companies,
            'user_role_id' => $request->roles,
        ]);

        return redirect()->route('index.users')->with('edit', true);
    }

    public function destroy($id)
    {      
        Users::find($id)->update([ 'active' => 0 ]);
        return redirect()->route('index.users')->with('destroy', true);
    }
}
