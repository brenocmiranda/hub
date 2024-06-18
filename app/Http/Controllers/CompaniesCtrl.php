<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CompaniesRqt;
use App\Models\Companies;
use App\Models\UsersLogs;

class CompaniesCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('companies.index')->with('companies', Companies::orderBy('active', 'desc')->orderBy('name', 'asc')->get());
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
            'user_id' => Auth::user()->id
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
            'user_id' => Auth::user()->id
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
            'user_id' => Auth::user()->id
        ]);

        Companies::find($id)->delete();
        return redirect()->route('companies.index')->with('destroy', true);
    }
}
