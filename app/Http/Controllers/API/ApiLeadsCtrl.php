<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use App\Http\Requests\ApiLeadsRqt;
use App\Models\BuildingsKeys;
use App\Models\Leads;
use App\Models\LeadsFields;
use App\Models\LeadsOrigins;
use App\Jobs\ProcessIntegrationsJob;

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
            if($request->name) {
                $name = $request->name;
            }elseif($request->nome) {
                $name = $request->nome;
            }else{
                $name = "Não recebido.";
            }

            // Telefone
            if($request->telefone) {
                $tel = preg_replace( '/\D/', '', str_replace( '+55', '', $request->telefone ));
	            $phone = strlen( $tel ) < 10  ? substr( $tel . str_repeat( '9', 11 ), 0, 11 ) : $tel;
            }elseif($request->celular) {
                $tel = preg_replace( '/\D/', '', str_replace( '+55', '', $request->celular ));
	            $phone = strlen( $tel ) < 10  ? substr( $tel . str_repeat( '9', 11 ), 0, 11 ) : $tel;
            }elseif($request->phoneNumber) {
                $tel = preg_replace( '/\D/', '', str_replace( '+55', '', $request->phoneNumber ));
	            $phone = strlen( $tel ) < 10  ? substr( $tel . str_repeat( '9', 11 ), 0, 11 ) : $tel;
            }elseif($request->phone) {
                $tel = preg_replace( '/\D/', '', str_replace( '+55', '', $request->phone ));
	            $phone = strlen( $tel ) < 10  ? substr( $tel . str_repeat( '9', 11 ), 0, 11 ) : $tel;
            }else{
                $phone = "Não recebido.";
            }

            /*
                3198612535
                28999799035
                21981045682
                3183745735
                31993553210
                2121973643477
                31999206192
                21997180803
                3198286066
                31993436990
                21987875454
                62992505564
                */
                
            // E-mail
            if($request->email){
                $email = $request->email ? $request->email : "naoidentificado@komuh.com";
            }
            
            // return for building
            $bdefault = BuildingsKeys::where('value', 'default')->first();
            if($request->building) {
                $buildingNow = BuildingsKeys::where('value', $request->building)->first();
                $building = $buildingNow ? $buildingNow->building_id : $bdefault->building_id;
            }elseif($request->empreendimento) {
                $buildingNow = BuildingsKeys::where('value', $request->empreendimento)->first();
                $building = $buildingNow ? $buildingNow->building_id : $bdefault->building_id;
            }elseif($request->originListingId) {
                $buildingNow = BuildingsKeys::where('value', $request->originListingId)->first();
                $building = $buildingNow ? $buildingNow->building_id : $bdefault->building_id;
            }elseif($request->codigoDoAnunciante) {
                $buildingNow = BuildingsKeys::where('value', $request->codigoDoAnunciante)->first();
                $building = $buildingNow ? $buildingNow->building_id : $bdefault->building_id;
            }else{
                $bdefault = BuildingsKeys::where('value', 'default')->first();
                $building = $b ? $b->building_id : $bdefault->building_id;
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

            // url_params
            if($request->url_params){

                parse_str( $request->url_params, $output );

                $fields['nameField'][] = 'utm_source';
                $fields['valueField'][] = $output['utm_source'];

                $fields['nameField'][] = 'utm_campaign';
                $fields['valueField'][] = $output['utm_campaign'];

                $fields['nameField'][] = 'utm_medium';
                $fields['valueField'][] = $output['utm_medium'];

                $fields['nameField'][] = 'utm_content';
                $fields['valueField'][] = $output['utm_content'];

                $fields['nameField'][] = 'utm_term';
                $fields['valueField'][] = $output['utm_term'];

            }else {

                // utm_source
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
                }elseif($request->leadOrigin) {
                    if($request->leadOrigin === 'VivaReal'){
                        $fields['valueField'][] = 'vivareal';
                    }
                    if($request->leadOrigin === 'Zap'){
                        $fields['valueField'][] = 'zapimoveis';
                    }
                }else{
                    $fields['valueField'][] = "";
                }

                // utm_campaign
                $fields['nameField'][] = 'utm_campaign';
                if($request->utm_campaign) {
                    $fields['valueField'][] = $request->utm_campaign;
                }elseif($request->campanha) {
                    $fields['valueField'][] = $request->campanha;
                }elseif($request->codigoImobiliaria) {
                    $fields['valueField'][] = $request->codigoImobiliaria;
                }else{
                    $fields['valueField'][] = "";
                }

                // utm_medium
                $fields['nameField'][] = 'utm_medium';
                if($request->utm_medium) {
                    $fields['valueField'][] = $request->utm_medium;
                }elseif($request->nome_form) {
                    $fields['valueField'][] = $request->nome_form;
                }elseif($request->clientListingId) {
                    $fields['valueField'][] = $request->clientListingId;
                }elseif($request->planoDePublicacao) {
                    $fields['valueField'][] = $request->planoDePublicacao;
                }else{
                    $fields['valueField'][] = "";
                }

                // utm_content
                $fields['nameField'][] = 'utm_content';
                if($request->utm_content) {
                    $fields['valueField'][] = $request->utm_content;
                }elseif($request->ad_name) {
                    $fields['valueField'][] = $request->ad_name;
                }else{
                    $fields['valueField'][] = "";
                }

                // utm_term
                $fields['nameField'][] = 'utm_term';
                if($request->utm_term) {
                    $fields['valueField'][] = $request->utm_term;
                }elseif($request->adset_name) {
                    $fields['valueField'][] = $request->adset_name;
                }else{
                    $fields['valueField'][] = "";
                }
            }

            // sobrenome
            if($request->sobrenome){
                $name = $name . ' ' . $request->sobrenome;
            }

            // message
            if($request->message){
                $fields['nameField'][] = 'message';
                $fields['valueField'][] = $request->message;
            }elseif($request->mensagem){
                $fields['nameField'][] = 'message';
                $fields['valueField'][] = $request->mensagem;
            }

            // url
            if($request->pp){
                $fields['nameField'][] = 'url';
                $fields['valueField'][] = $request->url;
            }

            // com
            if($request->com){
                $fields['nameField'][] = 'com';
                $fields['valueField'][] = $request->com ? 'Y' : 'N';
            }elseif($request->comunicacao){ 
                $fields['nameField'][] = 'com';
                $fields['valueField'][] = $request->comunicacao ? 'Y' : 'N';
            }

            // pp
            if($request->pp){
                $fields['nameField'][] = 'pp';
                $fields['valueField'][] = $request->com ? 'Y' : 'N';
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

            // plataforma
            if($request->plataforma){
                $fields['nameField'][] = 'plataforma';
                $fields['valueField'][] = $request->plataforma;
            }
        
        // Create new lead
        $lead = Leads::create([
            'api' => true, 
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

        // Enviando para as execução das integrações
        ProcessIntegrationsJob::dispatch($lead->id);   

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
}
