<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BuildingsRqt;
use App\Models\Buildings;
use App\Models\BuildingsPartners;
use App\Models\BuildingsDestinatarios;
use App\Models\BuildingsSheets;
use App\Models\BuildingsIntegrations;
use App\Models\BuildingsIntegrationsFields;
use App\Models\BuildingsKeys;
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
        return view('buildings.index')->with('buildings', Buildings::orderBy('active', 'desc')->orderBy('name', 'asc')->get());
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
        ]);

        // Cadastrando novos parceiros
        $partners = $request->input('partner');
        if(isset($partners)){
            foreach($partners as $index => $partner) {
                $buildingsPartners = BuildingsPartners::create([
                    'main' => $request->input('main')[$index], 
                    'leads' => $request->input('leads')[$index], 
                    'companies_id' => $request->input('partner')[$index], 
                    'buildings_id' => $building->id, 
                ]);
            }
        }

        // Cadastrando novos destinatários
        $destinatarios = $request->input('email');
        if(isset($destinatarios)){
            foreach($destinatarios as $destinatario) {
                $buildingDestinatario = BuildingsDestinatarios::create([
                    'email' => $destinatario,
                    'buildings_id' => $building->id, 
                ]);
            }
        }

        // Cadastrando novos sheets
        $sheets = $request->input('spreadsheetID');
        if(isset($sheets)){
            foreach($sheets as $index1 => $sheet) {
                if($request->hasFile('file.'.$index1)){
                    // Upload file
                    $filenameWithExt = $request->file('file')[$index1]->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('file')[$index1]->getClientOriginalExtension();
                    $fileNameToStore = $filename.'_'.time().'.'.$extension;
                    $path = $request->file('file')[$index1]->storeAs('public/google', $fileNameToStore);
                }
                $buildingSheet = BuildingsSheets::create([
                    'sheet' => $request->input('sheet')[$index1],
                    'spreadsheetID' => $request->input('spreadsheetID')[$index1],
                    'file' => $fileNameToStore,
                    'buildings_id' => $building->id, 
                ]);
            }
        }

        // Cadastrando novas integrações e novos campos
        $integrations = $request->input('array');
        if(isset($integrations)){
            foreach($integrations as $integration) {
                $buildingIntegration = BuildingsIntegrations::create([
                    'buildings_id' => $building->id, 
                    'integrations_id' => $integration['nameIntegration'],
                ]);
                foreach($integration['nameField'] as $index2 => $field) {
                    BuildingsIntegrationsFields::create([
                        'name' => $integration['nameField'][$index2], 
                        'value' => $integration['valueField'][$index2],
                        'buildings_has_integrations_buildings_id' => $building->id,
                        'buildings_has_integrations_integrations_id' => $integration['nameIntegration'],
                    ]);
                }
            }
        }
        
        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de novo empreendimento',
            'description' => 'Foi realizado o cadastro de um novo empreendimento: ' . $request->name . '.',
            'action' => 'create',
            'users_id' => Auth::user()->id
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
        BuildingsPartners::where('buildings_id', $id)->forceDelete();
        BuildingsDestinatarios::where('buildings_id', $id)->forceDelete();
        BuildingsSheets::where('buildings_id', $id)->forceDelete();
        BuildingsIntegrationsFields::where('buildings_has_integrations_buildings_id', $id)->forceDelete();
        BuildingsIntegrations::where('buildings_id', $id)->forceDelete();

        // Atualizando os dados do empreendimento
        Buildings::find($id)->update([
            'name' => $request->name, 
            'active' => $request->active, 
        ]);

        // Cadastrando novos parceiros
        $partners = $request->input('partner');
        if(isset($partners)){
            foreach($partners as $index => $partner) {
                $buildingsPartners = BuildingsPartners::create([
                    'main' => $request->input('main')[$index], 
                    'leads' => $request->input('leads')[$index], 
                    'companies_id' => $request->input('partner')[$index], 
                    'buildings_id' => $id, 
                ]);
            }
        }

        // Cadastrando novos destinatários
        $destinatarios = $request->input('email');
        if(isset($destinatarios)){
            foreach($destinatarios as $destinatario) {
                $buildingDestinatario = BuildingsDestinatarios::create([
                    'email' => $destinatario,
                    'buildings_id' => $id, 
                ]);
            }
        }

        // Cadastrando novos sheets
        $sheets = $request->input('spreadsheetID');
        if(isset($sheets)){
            foreach($sheets as $index1 => $sheet) {
                if($request->hasFile('file.'.$index1)){
                    // Upload file
                    $filenameWithExt = $request->file('file')[$index1]->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('file')[$index1]->getClientOriginalExtension();
                    $fileNameToStore = $filename.'_'.time().'.'.$extension;
                    $path = $request->file('file')[$index1]->storeAs('public/google', $fileNameToStore);

                    $buildingSheet = BuildingsSheets::create([
                        'sheet' => $request->input('sheet')[$index1],
                        'spreadsheetID' => $request->input('spreadsheetID')[$index1],
                        'file' => $fileNameToStore,
                        'buildings_id' => $id,
                    ]);
                } else {
                    $buildingSheet = BuildingsSheets::create([
                        'sheet' => $request->input('sheet')[$index1],
                        'spreadsheetID' => $request->input('spreadsheetID')[$index1],
                        'file' => $request->input('fileexists')[$index1],
                        'buildings_id' => $id,
                    ]);
                }
            }
        }

        // Cadastrando novas integrações e novos campos
        $integrations = $request->input('array');
        if(isset($integrations)){
            foreach($integrations as $integration) {
                $buildingIntegration = BuildingsIntegrations::create([
                    'buildings_id' => $id, 
                    'integrations_id' => $integration['nameIntegration'],
                ]);
                if( isset($integration['nameField'][0]) ){
                    foreach($integration['nameField'] as $index2 => $field) {
                        BuildingsIntegrationsFields::create([
                            'name' => $integration['nameField'][$index2], 
                            'value' => $integration['valueField'][$index2],
                            'buildings_has_integrations_buildings_id' => $id,
                            'buildings_has_integrations_integrations_id' => $integration['nameIntegration'],
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
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('buildings.index')->with('edit', true);
    }

    public function destroy(string $id)
    {   
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão de empreendimento',
            'description' => 'Foi realizado a exclusão do empreendimento: ' . Buildings::find($id)->name . '.',
            'action' => 'destroy',
            'users_id' => Auth::user()->id
        ]);
        
        BuildingsKeys::where('buildings_id', $id)->delete();
        Buildings::find($id)->delete();
        return redirect()->route('buildings.index')->with('destroy', true);
    }

    public function duplicate(string $id)
    {
        $building = Buildings::find($id);
        $buildingsPartners = BuildingsPartners::where('buildings_id', $id)->get();
        $buildingIntegrations = BuildingsIntegrations::where('buildings_id', $id)->get();
        $buildingDestinatarios = BuildingsDestinatarios::where('buildings_id', $id)->get();
        $buildingSheets = BuildingsSheets::where('buildings_id', $id)->get();
        $buildingIntegrationsFields = BuildingsIntegrationsFields::where('buildings_has_integrations_buildings_id', $id)->get();

        // Cadastrando novo empreendimento
        $buildingNew = Buildings::create([
            'name' => $building->name . '_Copy', 
            'active' => $building->active,
        ]);
        
        // Cadastrando novos parceiros
        if(isset($buildingsPartners)){
            foreach($buildingsPartners as $index => $partner) {
                $buildingsPartner = BuildingsPartners::create([
                    'main' => $partner->main, 
                    'leads' => $partner->leads, 
                    'companies_id' => $partner->companies_id, 
                    'buildings_id' => $buildingNew->id, 
                ]);
            }
        }

        // Cadastrando novos destinatários
        if(isset($buildingDestinatarios)){
            foreach($buildingDestinatarios as $destinatario) {
                $buildingDestinatario = BuildingsDestinatarios::create([
                    'email' => $destinatario->email,
                    'buildings_id' => $buildingNew->id, 
                ]);
            }
        }

        // Cadastrando novos sheets
        if(isset($buildingSheets)){
            foreach($buildingSheets as $in => $sheet) {
                $buildingSheet = BuildingsSheets::create([
                    'sheet' => $sheet->sheet,
                    'spreadsheetID' => $sheet->spreadsheetID,
                    'file' => $sheet->file,
                    'buildings_id' => $buildingNew->id,
                ]);
            }
        }

        // Cadastrando novas integrações e novos campos
        if(isset($buildingIntegrations)){
            foreach($buildingIntegrations as $integration) {
                $buildingIntegration = BuildingsIntegrations::create([
                    'buildings_id' => $buildingNew->id, 
                    'integrations_id' => $integration->integrations_id,
                ]);

                foreach($buildingIntegrationsFields as $index => $field) {
                    if($field->buildings_has_integrations_integrations_id == $integration->integrations_id) {
                        BuildingsIntegrationsFields::create([
                            'name' => $field->name, 
                            'value' => $field->value,
                            'buildings_has_integrations_buildings_id' => $buildingNew->id,
                            'buildings_has_integrations_integrations_id' => $integration->integrations_id,
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
            'users_id' => Auth::user()->id
        ]);
        
        return redirect()->route('buildings.index')->with('duplicate', true);
    }
}
