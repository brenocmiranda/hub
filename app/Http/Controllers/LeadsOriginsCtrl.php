<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LeadsOriginsRqt;
use App\Models\LeadsOrigins;
use App\Models\UsersLogs;

class LeadsOriginsCtrl extends Controller
{
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('leads.origins.index')->with('origins', LeadsOrigins::orderBy('active', 'desc')->orderBy('name', 'asc')->get());
    }

    public function create()
    {      
        return view('leads.origins.create');
    }

    public function store(LeadsOriginsRqt $request)
    {      
        LeadsOrigins::create([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de nova origem',
            'description' => 'Foi realizado o cadastro de uma nova origem: ' . $request->name . '.',
            'action' => 'create',
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('leads.origins.index')->with('create', true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        return view('leads.origins.edit')->with('origin', LeadsOrigins::find($id));
    } 

    public function update(LeadsOriginsRqt $request, string $id)
    {
        LeadsOrigins::find($id)->update([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização das informações da origem',
            'description' => 'Foi realizado a atualização das informações da origem: ' . $request->name . '.',
            'action' => 'update',
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('leads.origins.index')->with('edit', true);
    }

    public function destroy(string $id)
    {      
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão da origem',
            'description' => 'Foi realizado a exclusão da origem: ' .  LeadsOrigins::find($id)->name . '.',
            'action' => 'destroy',
            'user_id' => Auth::user()->id
        ]);

        LeadsOrigins::find($id)->delete();
        return redirect()->route('leads.origins.index')->with('destroy', true);
    }
}
