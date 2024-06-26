<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BuildingsKeysRqt;
use App\Models\Buildings;
use App\Models\BuildingsKeys;
use App\Models\Companies;
use App\Models\UsersLogs;

class BuildingsKeysCtrl extends Controller
{
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('buildings.keys.index')->with('keys', BuildingsKeys::join('buildings', 'buildings.id', '=', 'buildings_keys.building_id')->select("buildings_keys.*")->orderBy('buildings_keys.active', 'desc')->orderBy('buildings.name', 'asc')->get());
    }

    public function create()
    {     
        $companies = Companies::where('active', 1)->orderBy('name', 'asc')->get();
        $buildings = Buildings::where('active', 1)->orderBy('name', 'asc')->get();
        
        foreach($companies as $companie){
            foreach($buildings as $building){ 
                if($companie->id == $building->companie_id){
                    $array[$companie->name][] = $building;
                }
            }
        } 

        return view('buildings.keys.create')->with('array', isset($array) ? $array : null);
    }

    public function store(BuildingsKeysRqt $request)
    {      
        BuildingsKeys::create([
            'value' => $request->value, 
            'building_id' => $request->building, 
            'active' => $request->active,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de nova chave',
            'description' => 'Foi realizado o cadastro de uma nova chave: ' . $request->value . '.',
            'action' => 'create',
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('buildings.keys.index')->with('create', true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        $companies = Companies::where('active', 1)->orderBy('name', 'asc')->get();
        $buildings = Buildings::where('active', 1)->orderBy('name', 'asc')->get();

        foreach($companies as $companie){
            foreach($buildings as $building){ 
                if($companie->id == $building->companie_id){
                    $array[$companie->name][] = $building;
                }
            }
        } 

        return view('buildings.keys.edit')->with('key', BuildingsKeys::find($id))->with('array', isset($array) ? $array : null);
    } 

    public function update(BuildingsKeysRqt $request, string $id)
    {
        BuildingsKeys::find($id)->update([
            'value' => $request->value, 
            'building_id' => $request->building, 
            'active' => $request->active,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização das informações da chave',
            'description' => 'Foi realizado a atualização das informações da chave: ' . $request->value . '.',
            'action' => 'update',
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('buildings.keys.index')->with('edit', true);
    }

    public function destroy(string $id)
    {     
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão de chave',
            'description' => 'Foi realizado a exclusão da chave: ' .  BuildingsKeys::find($id)->value . '.',
            'action' => 'destroy',
            'user_id' => Auth::user()->id
        ]);

        BuildingsKeys::find($id)->delete();
        return redirect()->route('buildings.keys.index')->with('destroy', true);
    }
}
