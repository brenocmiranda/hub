<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UsersTokensRqt;

class UsersTokensCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {   
        return view('users.tokens.index')->with('tokens', Auth::user()->tokens);
    }

    public function create()
    {      
        return view('users.tokens.create');
    }

    public function store(UsersTokensRqt $request)
    {      
        $token = Auth::user()->createToken($request->name);
        return redirect()->route('users.tokens.index')->with('create', true)->with('token', $token->plainTextToken) ;
    }

    public function show(string $id)
    {
        //
    }
    
    public function edit(string $id)
    {      
        //
    } 

    public function update(UsersTokensRqt $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {      
        Auth::user()->tokens()->where('id', $id)->delete();
        return redirect()->route('users.tokens.index')->with('destroy', true);
    }
}
