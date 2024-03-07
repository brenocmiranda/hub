<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\BuildingsKeysRqt;
use App\Models\Buildings;
use App\Models\BuildingsKeys;
use App\Models\Companies;

class BuildingsKeysCtrl extends Controller
{
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('buildings.keys.index')->with('keys', BuildingsKeys::orderBy('name', 'asc')->get());
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
            'name' => $request->name, 
            'value' => $request->value, 
            'building_id' => $request->building, 
            'active' => $request->active,
        ]);

        return redirect()->route('index.buildings.keys')->with('create', true);
    }

    public function edit($id)
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

    public function update(BuildingsKeysRqt $request, $id)
    {
        BuildingsKeys::find($id)->update([
            'name' => $request->name, 
            'value' => $request->value, 
            'building_id' => $request->building, 
            'active' => $request->active,
        ]);

        return redirect()->route('index.buildings.keys')->with('edit', true);
    }

    public function destroy($id)
    {      
        BuildingsKeys::find($id)->update([ 'active' => 0 ]);
        return redirect()->route('index.buildings.keys')->with('destroy', true);
    }
}
