<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use App\Http\Requests\ApiLeadsRqt;
use App\Models\BuildingsKeys;
use App\Models\Leads;
use App\Models\LeadsFields;
use App\Models\LeadsOrigins;

class ApiLeadsCtrl extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leads = Leads::all();
        return response()->json([
            'status' => true,
            'leads' => $leads,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApiLeadsRqt $request, $originLead = null)
    {       
        /** Params requeried **/
            // Nome
            if($request->name || $request->nome){
                if($request->name) {
                    $name = $request->name;
                }elseif($request->nome) {
                    $name = $request->nome;
                }else{
                    $name = "Não recebido.";
                }
            }

            // Telefone
            if($request->phone || $request->telefone){
                if($request->phone) {
                    $phone = $request->phone;
                }elseif($request->telefone) {
                    $phone = $request->telefone;
                }else{
                    $phone = "Não recebido.";
                }
            }

            // E-mail
            if($request->email){
                $email = $request->email ? $request->email : "naoidentificado@komuh.com";
            }
            
            // return for building
            if($request->building || $request->empreendimento){
                if($request->building) {
                    $buildingNow = BuildingsKeys::where('value', $request->building)->first();
                    $bdefault = BuildingsKeys::where('value', 'default')->first();
                    $building = $buildingNow ? $buildingNow->building_id : $bdefault->building_id;
                }elseif($request->empreendimento) {
                    $buildingNow = BuildingsKeys::where('value', $request->empreendimento)->first();
                    $bdefault = BuildingsKeys::where('value', 'default')->first();
                    $building = $buildingNow ? $buildingNow->building_id : $bdefault->building_id;
                }else{
                    $bdefault = BuildingsKeys::where('value', 'default')->first();
                    $building = $b ? $b->building_id : $bdefault->building_id;
                }
            }

            // return for origin
            if($originLead){
                $odefault = LeadsOrigins::where('slug', 'default')->first();
                $originNow = LeadsOrigins::where('slug', $originLead)->first();
                $origin = $originNow ? $originNow->id : $odefault->id;
            } else {
                $odefault = LeadsOrigins::where('slug', 'default')->first();
                $origin = $odefault ? $odefault->id : "";
            }

        /** Params optional **/
            // utm_source
            if($request->utm_source || $request->plataforma){
                $fields['nameField'][] = 'utm_source';
                if($request->utm_source) {
                    $fields['valueField'][] = $request->utm_source;
                }elseif($request->plataforma) {
                    if($request->plataforma === 'fb'){
                        $fields['valueField'][] = 'facebook';
                    }
                    if($request->plataforma === 'ig'){
                        $fields['valueField'][] = 'instagram';
                    }
                }else{
                    $fields['valueField'][] = "";
                }
            }  

            // utm_campaign
            if($request->utm_campaign || $request->campanha){
                $fields['nameField'][] = 'utm_campaign';
                if($request->utm_campaign) {
                    $fields['valueField'][] = $request->utm_campaign;
                }elseif($request->campanha) {
                    $fields['valueField'][] = $request->campanha;
                }else{
                    $fields['valueField'][] = "";
                }
            }

            // utm_medium
            if($request->utm_medium || $request->ad_name){
                $fields['nameField'][] = 'utm_medium';
                if($request->utm_medium) {
                    $fields['valueField'][] = $request->utm_medium;
                }elseif($request->ad_name) {
                    $fields['valueField'][] = $request->ad_name;
                }else{
                    $fields['valueField'][] = "";
                }
            }  

            // utm_content
            if($request->utm_content || $request->nome_form){
                $fields['nameField'][] = 'utm_content';
                if($request->utm_content) {
                    $fields['valueField'][] = $request->utm_content;
                }elseif($request->nome_form) {
                    $fields['valueField'][] = $request->nome_form;
                }else{
                    $fields['valueField'][] = "";
                }
            }

            // utm_term
            if($request->utm_term){
                $fields['nameField'][] = 'utm_term';
                $fields['valueField'][] = $request->utm_term;
            }

            // gclid
            if($request->gclid){
                $fields['nameField'][] = 'gclid';
                $fields['valueField'][] = $request->gclid;
            }
            
            // fbclid
            if($request->fbclid){
                $fields['nameField'][] = 'fbclid';
                $fields['valueField'][] = $request->fbclid;
            }
            
            // pp
            if($request->pp){
                $fields['nameField'][] = 'pp';
                $fields['valueField'][] = $request->pp;
            }

            // com
            if($request->com){
                $fields['nameField'][] = 'com';
                $fields['valueField'][] = $request->com;
            }

            // url
            if($request->pp){
                $fields['nameField'][] = 'url';
                $fields['valueField'][] = $request->url;
            }

            // message
            if($request->message){
                $fields['nameField'][] = 'message';
                $fields['valueField'][] = $request->message;
            }

            // plataforma
            if($request->plataforma){
                $fields['nameField'][] = 'plataforma';
                $fields['valueField'][] = $request->plataforma;
            }
        
        // Create new lead
        $lead = Leads::create([
            'name' => $name, 
            'phone' => $phone, 
            'email' => $email,
            'building_id' => $building,
            'leads_origin_id' => $origin,
        ]);

        // Create fields optional
        if(isset($fields)){
            foreach($fields["nameField"] as $index => $field) {
                LeadsFields::create([
                    'name' => $fields["nameField"][$index], 
                    'value' => $fields["valueField"][$index],
                    'leads_id' => $lead->id,
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => "Lead cadastrado com sucesso!",
            'lead' => $lead
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Function no config.',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ApiLeadsRqt $request, string $id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Function no config.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Function no config.',
        ]);
    }

    /**
     * Criando token de segurança
     */
    /*
    public function login(LoginRqt $request){
        $user = Users::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
        ]);
    }*/
}
