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
        if(isset($integrations)){
            foreach($integrations as $integration) {
                $buildingIntegration = BuildingsIntegrations::create([
                    'building_id' => $building->id, 
                    'integration_id' => $integration['nameIntegration'],
                ]);

                foreach($integration['nameField'] as $index => $field) {
                    BuildingsIntegrationsFields::create([
                        'name' => $integration['nameField'][$index], 
                        'value' => $integration['valueField'][$index],
                        'buildings_has_integrations_building_id' => $building->id,
                        'buildings_has_integrations_integration_id' => $integration['nameIntegration'],
                    ]);
                }
            }
        }
        
        return redirect()->route('buildings.index')->with('create', true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        return view('buildings.edit')->with('building', Buildings::find($id))->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get())->with('integrations', Integrations::where('active', 1)->orderBy('name', 'asc')->get());
    }

    public function update(BuildingsRqt $request, $id)
    {
        // Removendo os registros anteriores
        BuildingsIntegrationsFields::where('buildings_has_integrations_building_id', $id)->forceDelete();
        BuildingsIntegrations::where('building_id', $id)->forceDelete();

        // Atualizando os dados do empreendimento
        Buildings::find($id)->update([
            'name' => $request->name, 
            'active' => $request->active,
            'companie_id' => $request->companie, 
        ]);

        // Cadastrando novas integrações e novos campos
        $integrations = $request->input('array');
        if(isset($integrations)){
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
                            'buildings_has_integrations_building_id' => $id,
                            'buildings_has_integrations_integration_id' => $integration['nameIntegration'],
                        ]);
                    }
                }
            }
        }

        return redirect()->route('buildings.index')->with('edit', true);
    }

    public function destroy(string $id)
    {      
        Buildings::find($id)->delete();
        return redirect()->route('buildings.index')->with('destroy', true);
    }
}
