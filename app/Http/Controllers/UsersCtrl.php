<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\UsersRqt;
use App\Models\Companies;
use App\Models\UsersRoles;
use App\Models\Users;
use App\Notifications\FirstAccess;
use App\Notifications\RecoveryPassword;

class UsersCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}

    public function index()
    {
        return view('users.index')->with('users', Users::orderBy('name', 'asc')->where('id', '!=', Auth::user()->id)->get());
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
            'companie_id' => $request->companies,
            'user_role_id' => $request->roles,
            'remember_token' => Str::random(10),
            'attempts' => 0,
        ]);

        $user->notify(new FirstAccess($user));

        return redirect()->route('index.users')->with('create', true);
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
            'companie_id' => $request->companies,
            'user_role_id' => $request->roles,
        ]);

        return redirect()->route('index.users')->with('edit', true);
    }

    public function destroy(string $id)
    {      
        Users::find($id)->delete();
        return redirect()->route('index.users')->with('destroy', true);
    }

    public function recovery(string $id)
    {    
        $user = Users::find($id);
        $user->notify(new RecoveryPassword($user));
        return redirect()->route('index.users')->with('recovery', true);
    }
}
