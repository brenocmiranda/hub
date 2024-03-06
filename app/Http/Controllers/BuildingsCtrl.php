<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\BuildingsRqt;
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

    public function store(BuildingsRqt $request)
    {    
        // Cadastrando novo empreendimento
        $building = Buildings::create([
            'name' => $request->name, 
            'active' => $request->active,
            'companie_id' => $request->companie, 
        ]);

        // Cadastrando novas integrações e novos campos
        $integrations = $request->input('array');
        foreach($integrations as $integration) {
            $buildingIntegration = BuildingsIntegrations::create([
                'building_id' => $building->id, 
                'integration_id' => $integration['nameIntegration'],
            ]);

            foreach($integration['nameField'] as $index => $field) {
                BuildingsIntegrationsFields::create([
                    'name' => $integration['nameField'][$index], 
                    'value' => $integration['valueField'][$index],
                    'buildings_has_integrations_id' => $buildingIntegration->id,
                ]);
            }
        }
        
        return redirect()->route('index.buildings')->with('create', true);
    }

    public function edit($id)
    {      
        $integrations = BuildingsIntegrations::where('building_id', $id)->get();
        $fields[] = "";
        foreach($integrations as $index => $integration){
            $fields[$integration->id][] = BuildingsIntegrationsFields::where('buildings_has_integrations_id', $integration->id)->get();
        }
        
        return view('buildings.edit')->with('building', Buildings::find($id))->with('buildingIntegrations', $integrations)->with('buildingIntegrationsFields', $fields)->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get())->with('integrations', Integrations::where('active', 1)->orderBy('name', 'asc')->get());
    }

    public function update(BuildingsRqt $request, $id)
    {
        // Removendo os registros anteriores
        $buildingIntegrations = BuildingsIntegrations::where('building_id', $id)->get();
        foreach($buildingIntegrations as $buildingIntegration){
            BuildingsIntegrationsFields::where('buildings_has_integrations_id', $buildingIntegration->id)->delete();
        }
        BuildingsIntegrations::where('building_id', $id)->delete();

        // Atualizando os dados do empreendimento
        Buildings::find($id)->update([
            'name' => $request->name, 
            'active' => $request->active,
            'companie_id' => $request->companie, 
        ]);

        // Cadastrando novas integrações e novos campos
        $integrations = $request->input('array');
        if(isset($integrations[0])){
            foreach($integrations as $integration) {
                $buildingIntegration = BuildingsIntegrations::create([
                    'building_id' => $id, 
                    'integration_id' => $integration['nameIntegration'],
                ]);

                if( isset($integration['nameField'][0]) ){
                    foreach($integration['nameField'] as $index => $field) {
                        BuildingsIntegrationsFields::create([
                            'name' => $integration['nameField'][$index], 
                            'value' => $integration['valueField'][$index],
                            'buildings_has_integrations_id' => $buildingIntegration->id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('index.buildings')->with('edit', true);
    }

    public function destroy($id)
    {      
        Buildings::find($id)->update([ 'active' => 0 ]);
        return redirect()->route('index.buildings')->with('destroy', true);
    }
}
