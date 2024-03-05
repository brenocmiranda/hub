<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\BuildingsKeysRqt;
use App\Models\Buildings;
use App\Models\BuildingsKeys;

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
        return view('buildings.keys.create')->with('buildings', Buildings::where('active', 1)->orderBy('name', 'asc')->get());
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
        return view('buildings.keys.edit')->with('key', BuildingsKeys::find($id))->with('buildings', Buildings::where('active', 1)->get());
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
