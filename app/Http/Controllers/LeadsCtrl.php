<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\LeadsRqt;
use App\Models\Buildings;
use App\Models\Companies;
use App\Models\Leads;
use App\Models\LeadsFields;
use App\Models\LeadsOrigins;

class LeadsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('leads.index')->with('leads', Leads::orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        return view('leads.show')->with('lead', Leads::find($id));
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

        return redirect()->route('index.leads')->with('create', true);
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

        return view('leads.edit')->with('origins', LeadsOrigins::where('active', 1)->orderBy('name', 'asc')->get())->with('array', $array)->with('lead', Leads::find($id));
    } 

    public function update(LeadsRqt $request, $id)
    {
        Leads::find($id)->update([
            'name' => $request->name, 
            'phone' => $request->phone, 
            'email' => $request->email,
            'building_id' => $request->building,
            'leads_origin_id' => $request->origin,
        ]);

        return redirect()->route('index.leads')->with('edit', true);
    }

    public function destroy($id)
    {      
        Leads::find($id)->delete();
        return redirect()->route('index.leads')->with('destroy', true);
    }
}