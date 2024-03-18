<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LeadsRqt;
use App\Models\Buildings;
use App\Models\Companies;
use App\Models\Leads;
use App\Models\LeadsFields;
use App\Models\LeadsOrigins;
use App\Models\UsersLogs;

class LeadsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('leads.index')->with('leads', Leads::orderBy('created_at', 'desc')->get());
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

        return view('leads.create')->with('origins', LeadsOrigins::where('active', 1)->orderBy('name', 'asc')->get())->with('array', isset($array) ? $array : null);
    }

    public function store(LeadsRqt $request)
    {      
        $lead = Leads::create([
            'api' => false, 
            'name' => $request->name, 
            'phone' => $request->phone, 
            'email' => $request->email,
            'building_id' => $request->building,
            'leads_origin_id' => $request->origin,
        ]);

        // Cadastrando novas integrações e novos campos
        $fields = $request->input('array');
        if(isset($fields)){
            foreach($fields["nameField"] as $index => $field) {
                LeadsFields::create([
                    'name' => $fields["nameField"][$index], 
                    'value' => $fields["valueField"][$index],
                    'leads_id' => $lead->id,
                ]);
            }
        }

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastrado de nova lead',
            'action' => 'Foi realizado o cadastro de um novo lead: ' . $request->name . '.',
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('leads.index')->with('create', true);
    }

    public function show(string $id)
    {
        // Salvando log
        UsersLogs::create([
            'title' => 'Visualização de lead',
            'action' => 'Foi realizada a visualização das informações do lead: ' . Leads::find($id)->name . '.',
            'user_id' => Auth::user()->id
        ]);

        return view('leads.show')->with('lead', Leads::find($id));
    }

    public function edit(string $id)
    {      
        //
    } 

    public function update(LeadsRqt $request, string $id)
    {
       //
    }

    public function destroy(string $id)
    {      
        //
    }
}