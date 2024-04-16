<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BuildingsRqt;
use App\Models\Buildings;
use App\Models\BuildingsIntegrations;
use App\Models\BuildingsIntegrationsFields;
use App\Models\BuildingsDestinatarios;
use App\Models\BuildingsSheets;
use App\Models\Companies;
use App\Models\Integrations;
use App\Models\UsersLogs;

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

        // Cadastrando novos destinatários
        $destinatarios = $request->input('email');
        if(isset($destinatarios)){
            foreach($destinatarios as $destinatario) {
                $buildingDestinatario = BuildingsDestinatarios::create([
                    'building_id' => $building->id, 
                    'email' => $destinatario,
                ]);
            }
        }

        // Cadastrando novos sheets
        $sheets = $request->input('spreadsheetID');
        if(isset($sheets)){
            foreach($sheets as $in => $sheet) {

                // Upload file
                if($request->hasFile('file.'.$in)){
                    $filenameWithExt = $request->file('file')[$in]->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('file')[$in]->getClientOriginalExtension();
                    $fileNameToStore = $filename.'_'.time().'.'.$extension;
                    $path = $request->file('file')[$in]->storeAs('public/google', $fileNameToStore);
                }

                $buildingSheet = BuildingsSheets::create([
                    'building_id' =>$building->id, 
                    'sheet' => $request->input('sheet')[$in],
                    'spreadsheetID' => $request->input('spreadsheetID')[$in],
                    'file' => $fileNameToStore,
                ]);
            }
        }

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
        
        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de novo empreendimento',
            'description' => 'Foi realizado o cadastro de um novo empreendimento: ' . $request->name . '.',
            'action' => 'create',
            'user_id' => Auth::user()->id
        ]);

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
        BuildingsDestinatarios::where('building_id', $id)->forceDelete();
        BuildingsSheets::where('building_id', $id)->forceDelete();
        BuildingsIntegrations::where('building_id', $id)->delete();
        BuildingsIntegrationsFields::where('buildings_has_integrations_building_id', $id)->forceDelete();

        // Atualizando os dados do empreendimento
        Buildings::find($id)->update([
            'name' => $request->name, 
            'active' => $request->active,
            'companie_id' => $request->companie, 
        ]);

        // Cadastrando novos destinatários
        $destinatarios = $request->input('email');
        if(isset($destinatarios)){
            foreach($destinatarios as $destinatario) {
                $buildingDestinatario = BuildingsDestinatarios::create([
                    'building_id' => $id, 
                    'email' => $destinatario,
                ]);
            }
        }

        // Cadastrando novos sheets
        $sheets = $request->input('spreadsheetID');
        if(isset($sheets)){
            foreach($sheets as $in => $sheet) {

                // Upload file
                if($request->hasFile('file.'.$in)){
                    $filenameWithExt = $request->file('file')[$in]->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('file')[$in]->getClientOriginalExtension();
                    $fileNameToStore = $filename.'_'.time().'.'.$extension;
                    $path = $request->file('file')[$in]->storeAs('public/google', $fileNameToStore);

                    $buildingSheet = BuildingsSheets::create([
                        'building_id' => $id,
                        'sheet' => $request->input('sheet')[$in],
                        'spreadsheetID' => $request->input('spreadsheetID')[$in],
                        'file' => $fileNameToStore,
                    ]);
                } else {
                    $buildingSheet = BuildingsSheets::create([
                        'building_id' => $id,
                        'sheet' => $request->input('sheet')[$in],
                        'spreadsheetID' => $request->input('spreadsheetID')[$in],
                        'file' => $request->input('fileexists')[$in],
                    ]);
                }

                
            }
        }

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

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização das informações do empreendimento',
            'description' => 'Foi realizado a atualização das informações do empreendimento: ' . $request->name . '.',
            'action' => 'update',
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('buildings.index')->with('edit', true);
    }

    public function destroy(string $id)
    {   
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão de empreendimento',
            'description' => 'Foi realizado a exclusão do empreendimento: ' .  Buildings::find($id)->name . '.',
            'action' => 'destroy',
            'user_id' => Auth::user()->id
        ]);
        
        Buildings::find($id)->delete();
        return redirect()->route('buildings.index')->with('destroy', true);
    }

    public function duplicate(string $id){
        $building = Buildings::find($id);
        $buildingIntegrations = BuildingsIntegrations::where('building_id', $id)->get();
        $buildingDestinatarios = BuildingsDestinatarios::where('building_id', $id)->get();
        $buildingSheets = BuildingsSheets::where('building_id', $id)->get();
        $buildingIntegrationsFields = BuildingsIntegrationsFields::where('buildings_has_integrations_building_id', $id)->get();

        // Cadastrando novo empreendimento
        $buildingNew = Buildings::create([
            'name' => $building->name . '_Copy', 
            'active' => $building->active,
            'companie_id' => $building->companie_id, 
        ]);

        // Cadastrando novos destinatários
        if(isset($buildingDestinatarios)){
            foreach($buildingDestinatarios as $destinatario) {
                $buildingDestinatario = BuildingsDestinatarios::create([
                    'building_id' => $buildingNew->id, 
                    'email' => $destinatario,
                ]);
            }
        }

         // Cadastrando novos sheets
        $sheets = $request->input('spreadsheetID');
        if(isset($sheets)){
            foreach($sheets as $in => $sheet) {

                // Upload file
                if($request->hasFile('file')[$in]){
                    $filenameWithExt = $request->file('file')[$in]->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('file')[$in]->getClientOriginalExtension();
                    $fileNameToStore = $filename.'_'.time().'.'.$extension;
                    $path = $request->file('file')[$in]->storeAs('public/google', $fileNameToStore);
                }

                $buildingSheet = BuildingsSheets::create([
                    'building_id' => $buildingNew->id,
                    'sheet' => $request->input('sheet')[$in],
                    'spreadsheetID' => $request->input('spreadsheetID')[$in],
                    'file' => $fileNameToStore,
                ]);
            }
        }

        // Cadastrando novas integrações e novos campos
        if(isset($buildingIntegrations)){
            foreach($buildingIntegrations as $integration) {
                $buildingIntegration = BuildingsIntegrations::create([
                    'building_id' => $buildingNew->id, 
                    'integration_id' => $integration->integration_id,
                ]);

                foreach($buildingIntegrationsFields as $index => $field) {
                    if($field->buildings_has_integrations_integration_id == $integration->integration_id) {
                        BuildingsIntegrationsFields::create([
                            'name' => $field->name, 
                            'value' => $field->value,
                            'buildings_has_integrations_building_id' => $buildingNew->id,
                            'buildings_has_integrations_integration_id' => $integration->integration_id,
                        ]);
                    } 
                }
            }
        }

        // Salvando log
        UsersLogs::create([
            'title' => 'Duplicação do empreendimento',
            'description' => 'Foi realizada o duplicação do empreendimento ' . $building->name . ' com o nome ' . $buildingNew->name . '.',
            'action' => 'duplicate',
            'user_id' => Auth::user()->id
        ]);
        
        return redirect()->route('buildings.index')->with('duplicate', true);
    }
}
