<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ApiLeadsRqt;
use App\Models\Buildings;
use App\Models\BuildingsKeys;
use App\Models\BuildingsPartners;
use App\Models\Leads;
use App\Models\LeadsFields;
use App\Models\LeadsOrigins;
use App\Jobs\ProcessBuildingJobs;

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

        Log::build([ 
            'driver' => 'single',
            'path' => storage_path('logs/api/' . date("d-m-Y") . '.log'),
        ])->info('Dados do lead recebido: ' . json_encode($request->all()) );

        /**
         * Params required
        */
            // Nome
            $array = [
                'name' => $request->name,
                'nome' => $request->nome,
            ];
            foreach($array as $ar){
                if( $ar ){
                    $name = ucwords( strtolower($ar) );
                    break;
                }
            }
            $name = $name ? $name : "Não recebido.";
            
            // Telefone
            $array = [
                'telefone' => $request->telefone,
                'celular' => $request->celular,
                'phoneNumber' => $request->phoneNumber,
                'phone' => $request->phone,
            ];
            foreach($array as $ar){
                if( $ar ){
                    $tel = preg_replace( '/\D/', '', str_replace( '+55', '', ltrim($ar, "0") ));
                    $ddd = substr( $tel, 0, 2 );
                    $number = substr( $tel, 2 );
                    $number = strlen( $number ) <= 8  ? substr( str_repeat( '9', 9 - strlen( $number ) ) . $number, 0, 9 ) : $number;
                    $phone = $ddd . $number;
                    break;
                }
            }
            $phone = $phone ? $phone : "99999999999";

            // E-mail
            $array = [
                'email' => str_replace(' ', '', $request->email),
            ];
            foreach($array as $ar){
                if( $ar ){
                    $email = strtolower($ar);
                    break;
                }
            }
            $email = $email ? $email : "naoidentificado@komuh.com";
            
            // Empreendimento
            $array = [
                'building' => BuildingsKeys::where('active', 1)->where('value', $request->building)->first(),
                'empreendimento' => BuildingsKeys::where('active', 1)->where('value', $request->empreendimento)->first(),
                'originListingId' => BuildingsKeys::where('active', 1)->where('value', $request->originListingId)->first(),
                'codigoDoAnunciante' => BuildingsKeys::where('active', 1)->where('value', $request->codigoDoAnunciante)->first(),
            ];
            foreach($array as $ar){
                if( $ar ){
                    $building = $ar->buildings_id;
                    break;
                }
            }
            $bdefault = BuildingsKeys::where('value', 'default')->first();
            $building = isset($building) ? $building : $bdefault->buildings_id;

            // Origin
            $companies_id = Buildings::join('buildings_partners', 'buildings_partners.buildings_id', 'buildings.id')->where('buildings.id', $building)->where('buildings_partners.main', 1)->first()->companies_id;
            $array = [
                'origin' => LeadsOrigins::where('companies_id', $companies_id)->where('slug', $request->origin)->first(),
                'originLead' => LeadsOrigins::where('companies_id', $companies_id)->where('slug', $originLead)->first(),
            ];
            foreach($array as $ar){
                if( $ar ){
                    $origin = $ar->id;
                    break;
                }
            }
            $odefault = LeadsOrigins::where('companies_id', $companies_id)->where('slug', 'default')->first();
            $origin = isset($origin) ? $origin : $odefault->id;


        /**
         * Params optinals
        */
            // url_params
            if($request->url_params){

                parse_str( $request->url_params, $output );

                if( !empty($output['utm_source']) ) {
                    $fields['nameField'][] = 'utm_source';
                    if($output['utm_source'] === 'fb'){
                        $fields['valueField'][] = 'facebook';
                    }
                    else if($output['utm_source'] === 'ig'){
                        $fields['valueField'][] = 'instagram';
                    }
                    else if($output['utm_source'] === 'VivaReal'){
                        $fields['valueField'][] = 'vivareal';
                    }
                    else if($output['utm_source'] === 'Zap'){
                        $fields['valueField'][] = 'zapimoveis';
                    }
                    else{
                        $fields['valueField'][] = $output['utm_source'];
                    }
                }

                if( !empty($output['utm_campaign']) ) {
                    $fields['nameField'][] = 'utm_campaign';
                    $fields['valueField'][] = $output['utm_campaign'];
                }
                
                if( !empty($output['utm_medium']) ) {
                    $fields['nameField'][] = 'utm_medium';
                    $fields['valueField'][] = $output['utm_medium'];
                }

                if( !empty($output['utm_content']) ) {
                    $fields['nameField'][] = 'utm_content';
                    $fields['valueField'][] = $output['utm_content'];
                }

                if( !empty($output['utm_term']) ) {
                    $fields['nameField'][] = 'utm_term';
                    $fields['valueField'][] = $output['utm_term'];
                }

            }else {

                // utm_source
                $array = [
                    'utm_source' => $request->utm_source,
                    'plataforma' => $request->plataforma,
                    'leadOrigin' => $request->leadOrigin,
                ];
                foreach($array as $ar){
                    if( $ar ){
                        $array2 = [
                            'fb' => 'facebook',
                            'ig' => 'instagram',
                            'in' => 'linkedin',
                            'an' => 'audience-network',
                            'VivaReal' => 'vivareal',
                            'Zap' => 'zapimoveis',
                            'Grupo OLX' => 'zapimoveis',
                        ];
                        $field = "";
                        foreach($array2 as $data => $ar2){
                            if( $ar == $data ){
                                $field = $ar2;
                                break;
                            }
                        }
                        if( $field ) {
                            $fields['nameField'][] = 'utm_source';
                            $fields['valueField'][] = $field;
                        }else {
                            $fields['nameField'][] = 'utm_source';
                            $fields['valueField'][] = $ar;
                        } 
                    } 
                }
 
                // utm_campaign
                $array = [
                    'utm_campaign' => $request->utm_campaign,
                    'campanha' => $request->campanha,
                    'codigoImobiliaria' => $request->codigoImobiliaria,
                ];
                foreach($array as $ar){
                    if( $ar ){
                        $fields['nameField'][] = 'utm_campaign';
                        $fields['valueField'][] = $ar;
                        break;
                    }
                }

                // utm_medium
                $array = [
                    'utm_medium' => $request->utm_medium,
                    'nome_form' => $request->nome_form,
                    'clientListingId' => $request->clientListingId,
                    'planoDePublicacao' => $request->planoDePublicacao,
                ];
                foreach($array as $ar){
                    if( $ar ){
                        $fields['nameField'][] = 'utm_medium';
                        $fields['valueField'][] = $ar;
                        break;
                    }
                }

                // utm_content
                $array = [
                    'utm_content' => $request->utm_content,
                    'ad_name' => $request->ad_name,
                ];
                foreach($array as $ar){
                    if( $ar ){
                        $fields['nameField'][] = 'utm_content';
                        $fields['valueField'][] = $ar;
                        break;
                    }
                }

                // utm_term
                $array = [
                    'utm_term' => $request->utm_term,
                    'adset_name' => $request->adset_name,
                ];
                foreach($array as $ar){
                    if( $ar ){
                        $fields['nameField'][] = 'utm_term';
                        $fields['valueField'][] = $ar;
                        break;
                    }
                }
            }

            // sobrenome
            $array = [
                'sobrenome' => $request->sobrenome,
            ];
            foreach($array as $ar){
                if( $ar ){
                    $name = $name . ' ' . $ar;
                }
            }

            // message
            $array = [
                'message' => $request->message,
                'mensagem' => $request->mensagem,
            ];
            foreach($array as $ar){
                if( $ar ){
                    $fields['nameField'][] = 'message';
                    $fields['valueField'][] = $ar;
                    break;
                }
            }

            // url
            $array = [
                'url' => $request->url,
            ];
            foreach($array as $ar){
                if( $ar ){
                    $fields['nameField'][] = 'url';
                    $fields['valueField'][] = $ar;
                    break;
                }
            }

            // pp
            $array = [
                'pp' => $request->pp,
            ];
            foreach($array as $ar){
                if( $ar ){
                    $fields['nameField'][] = 'pp';
                    $fields['valueField'][] = $ar;
                    break;
                }
            }

            // com
            $array = [
                'com' => $request->com,
                'comunicacao' => $request->comunicacao,
            ];
            foreach($array as $ar){
                if( $ar ){
                    $fields['nameField'][] = 'com';
                    $fields['valueField'][] = $ar;
                    break;
                }
            }
            
            // gclid
            $array = [
                'gclid' => $request->gclid,
            ];
            foreach($array as $ar){
                if( $ar ){
                    $fields['nameField'][] = 'gclid';
                    $fields['valueField'][] = $ar;
                    break;
                }
            }

            // fbclid
            $array = [
                'fbclid' => $request->fbclid,
            ];
            foreach($array as $ar){
                if( $ar ){
                    $fields['nameField'][] = 'fbclid';
                    $fields['valueField'][] = $ar;
                    break;
                }
            }

        // Defined partner responsible
        $partners = BuildingsPartners::where( 'buildings_id', $building )->orderBy('created_at', 'desc')->get();
        if( $partners->first() ){
            foreach( $partners as $partner ){
                if( $partner->leads == 99 ){
                    $companie = $partner->companies_id;
                    break;
                } else {
                    $countAllPartners = BuildingsPartners::where( 'buildings_id', $building )->select('leads')->sum('leads');
                    $leads = Leads::where( 'buildings_id', $building )->orderBy('created_at', 'desc')->take( $countAllPartners - 1)->get();
                    $leadsPartner = $leads->sortBy('created_at')->where( 'companies_id', $partner->companies_id )->count();
 
                    if( $leadsPartner < $partner->leads ){
                        $companie = $partner->companies_id;
                        break;
                    }
                }
            }
        }   
        $companie = isset($companie) ? $companie : BuildingsPartners::where( 'buildings_id', $building )->where('main', 1)->first()->companies_id;
        
        // Create new lead
        $lead = Leads::create([
            'api' => true, 
            'name' => $name, 
            'phone' => $phone, 
            'email' => $email,
            'buildings_id' => $building,
            'leads_origins_id' => $origin,
            'companies_id' => $companie,
        ]);

        // Validate lead test for name and email
        $builg = Buildings::find($building);
        if( (stripos($name, 'teste') !== false || stripos($email, 'teste') !== false) && $builg->test_buildings_id ) {
            // Defined partner responsible
            $partners = BuildingsPartners::where( 'buildings_id', $builg->test_buildings_id )->orderBy('created_at', 'desc')->get();
            if( $partners->first() ){
                foreach( $partners as $partner ){
                    if( $partner->leads == 99 ){
                        $companieTest = $partner->companies_id;
                        break;
                    } else {
                        $countAllPartners = BuildingsPartners::where( 'buildings_id', $builg->test_buildings_id )->select('leads')->sum('leads');
                        $leads = Leads::where( 'buildings_id', $builg->id )->orderBy('created_at', 'desc')->take( $countAllPartners - 1)->get();
                        $leadsPartner = $leads->sortBy('created_at')->where( 'companies_id', $partner->companies_id )->count();
    
                        if( $leadsPartner < $partner->leads ){
                            $companieTest = $partner->companies_id;
                            break;
                        }
                    }
                }
            }   
            $companieTest = isset($companieTest) ? $companieTest : BuildingsPartners::where( 'buildings_id', $builg->test_buildings_id )->where('main', 1)->first()->companies_id;

            // Update
            Leads::find($lead->id)->update([ 
                'buildings_id' => $builg->test_buildings_id,  
                'companies_id' => $companieTest,  
            ]);

            LeadsFields::create([
                'name' => 'building_origin', 
                'value' => $builg->name,
                'leads_id' => $lead->id,
            ]);
        }

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
        if ( stripos( $name, 'teste' ) === false ) {
            ProcessBuildingJobs::dispatch($lead->id);  
        }

        return response()->json([
            'status' => true,
            'message' => "Lead cadastrado com sucesso!",
            //'lead' => $lead
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
