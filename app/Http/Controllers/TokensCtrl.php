<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TokensRqt;
use App\Models\UsersLogs;

class TokensCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
        $this->middleware('can:tokens_show', ['only' => ['index', 'data', 'show']]);
        $this->middleware('can:tokens_create', ['only' => ['create', 'store']]);
        $this->middleware('can:tokens_update', ['only' => ['edit', 'update']]);
        $this->middleware('can:tokens_destroy', ['only' => ['destroy']]);
	}
    
    public function index()
    {   
        return view('system.tokens.index')->with('tokens', Auth::user()->tokens);
    }

    public function create()
    {      
        return view('system.tokens.create');
    }

    public function store(TokensRqt $request)
    {      
        $token = Auth::user()->createToken($request->name);

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de novo token',
            'description' => 'Foi realizado o cadastro de um novo token: ' . $request->name . '.',
            'action' => 'create',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('tokens.index')->with('create', true)->with('token', $token->plainTextToken) ;
    }

    public function show(string $id)
    {
        //
    }
    
    public function edit(string $id)
    {      
        //
    } 

    public function update(TokensRqt $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {      
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão da token',
            'description' => 'Foi realizado a exclusão do token: ' .  Auth::user()->tokens()->where('id', $id)->first()->name . '.',
            'action' => 'destroy',
            'users_id' => Auth::user()->id
        ]);

        Auth::user()->tokens()->where('id', $id)->delete();
        return redirect()->route('tokens.index')->with('destroy', true);
    }
}
