<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\BuildingsRqt;
use Illuminate\Http\Request;
use App\Models\Buildings;
use App\Models\BuildingsIntegrations;
use App\Models\BuildingsIntegrationsFields;
use App\Models\Companies;
use App\Models\Integrations;

class BuildingsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('buildings.index')->with('buildings', Buildings::orderBy('name', 'asc')->get());
    }

    public function create()
    {      
        return view('buildings.create')->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get())->with('integrations', Integrations::where('active', 1)->orderBy('name', 'asc')->get());
    }

    public function store(Request $request)
    {    

        $integrations = $request->input('integration');

        $building = Buildings::create([
            'name' => $request->name, 
            'active' => $request->active,
            'companie_id' => $request->companie, 
        ]);

        $buildingIntegration = BuildingsIntegrations::create([
            'building_id' => $building->id, 
            'integration_id' => $integrations['nameIntegration'],
        ]);

        foreach($integrations['nameField'] as $index => $field) {
            BuildingsIntegrationsFields::create([
                'name' => $integrations['nameField'][$index], 
                'value' => $integrations['valueField'][$index],
                'buildings_has_integrations_id' => $buildingIntegration->id,
            ]);
        }
        
        return redirect()->route('index.buildings')->with('create', true);
    }

    public function edit($id)
    {      
        return view('buildings.edit')->with('building', Buildings::find($id))->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get())->with('integrations', Integrations::where('active', 1)->orderBy('name', 'asc')->get());
    }

    public function update(Request $request, $id)
    {
        return redirect( route('index.buildings') );
    }

    public function destroy($id)
    {      
        Buildings::find($id)->update([ 'active' => 0 ]);
        return redirect()->route('index.buildings')->with('destroy', true);
    }
}
