<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ApiLeadsRqt;
use App\Models\Companies;
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
    public function store(ApiLeadsRqt $request, $company)
    {   

        Log::build([ 
            'driver' => 'single',
            'path' => storage_path('logs/api/' . date("d-m-Y") . '.log'),
        ])->info('Dados do lead recebido: ' . json_encode($request->all()) );

        /**
         * Params required
        */
        
            /** Empresa origem **/
            if( $company && Companies::find( $company )) {
                $companies_id = $company;
            } else {
                 return response()->json([
                    'status' => false,
                    'message' => 'Empresa não identificada.',
                ], 406);
            }
            
            /** Nome **/
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
            
            /** Telefone **/
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

            /** E-mail **/
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

            /** Empreendimento **/
            $array = [
                'building' => BuildingsKeys::join('buildings_partners', 'buildings_partners.buildings_id', 'buildings_keys.buildings_id')
                                            ->where('buildings_partners.companies_id', $companies_id)
                                            ->where('buildings_partners.main', 1)
                                            ->where('buildings_keys.value', $request->building)
                                            ->where('buildings_keys.active', 1)
                                            ->first(),
                'empreendimento' => BuildingsKeys::join('buildings_partners', 'buildings_partners.buildings_id', 'buildings_keys.buildings_id')
                                            ->where('buildings_partners.companies_id', $companies_id)
                                            ->where('buildings_partners.main', 1)
                                            ->where('buildings_keys.value', $request->empreendimento)
                                            ->where('buildings_keys.active', 1)
                                            ->first(),
                'originListingId' => BuildingsKeys::join('buildings_partners', 'buildings_partners.buildings_id', 'buildings_keys.buildings_id')
                                            ->where('buildings_partners.companies_id', $companies_id)
                                            ->where('buildings_partners.main', 1)
                                            ->where('buildings_keys.value', $request->originListingId)
                                            ->where('buildings_keys.active', 1)
                                            ->first(),
                'codigoDoAnunciante' => BuildingsKeys::join('buildings_partners', 'buildings_partners.buildings_id', 'buildings_keys.buildings_id')
                                            ->where('buildings_partners.companies_id', $companies_id)
                                            ->where('buildings_partners.main', 1)
                                            ->where('buildings_keys.value', $request->codigoDoAnunciante)
                                            ->where('buildings_keys.active', 1)
                                            ->first(),
                'idNavplat' => BuildingsKeys::join('buildings_partners', 'buildings_partners.buildings_id', 'buildings_keys.buildings_id')
                                            ->where('buildings_partners.companies_id', $companies_id)
                                            ->where('buildings_partners.main', 1)
                                            ->where('buildings_keys.value', $request->idNavplat)
                                            ->where('buildings_keys.active', 1)
                                            ->first(),
            ];
            foreach($array as $ar){
                if( $ar ){
                    $building = $ar->buildings_id;
                    break;
                }
            }
            $bdefault = BuildingsKeys::join('buildings_partners', 'buildings_partners.buildings_id', 'buildings_keys.buildings_id')
                                            ->where('buildings_partners.companies_id', $companies_id)
                                            ->where('buildings_partners.main', 1)
                                            ->where('buildings_keys.value', 'like', '%default%')
                                            ->where('buildings_keys.active', 1)
                                            ->first();
            $building = isset($building) ? $building : $bdefault->buildings_id;


            /** Origin **/
            $array = [
                'origin' => LeadsOrigins::where('companies_id', $companies_id)
                                            ->where('slug', $request->origin)->first(),
                'origem' => LeadsOrigins::where('companies_id', $companies_id)
                                                ->where('slug', $request->origem)->first(),
                'leadOrigin' => LeadsOrigins::where('companies_id', $companies_id)
                                                ->where('slug', $request->leadOrigin)->first(),
            ];

            

            foreach($array as $ar){
                var_dump($ar);
                die();
                if( $ar ){
                    $origin = $ar->id;
                    break;
                }
            }
            $odefault = LeadsOrigins::where('companies_id', $companies_id)->where('slug', 'like', '%default%')->first();
            $origin = isset($origin) ? $origin : $odefault->id;
        /**
         * End Params required
        */


        /**
         * Params optinals
        */
            /** Url_params **/
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

                /** utm_source **/
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
 
                /** utm_campaign **/
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

                /** utm_medium **/
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

                /** utm_content **/
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

                /** utm_term **/
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

            /** sobrenome **/
            $array = [
                'sobrenome' => $request->sobrenome,
            ];
            foreach($array as $ar){
                if( $ar ){
                    $name = $name . ' ' . $ar;
                }
            }

            /** message **/
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

            /** url **/
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

            /** pp **/
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

            /** com **/
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
        /**
         * End Params optinals
        */
        
        
        /**
         * Validate lead test for name and email
        */
            if( (stripos($name, 'teste') !== false || stripos($email, 'teste') !== false) ) {
                $empreendimento = Buildings::find($building);
                if( $empreendimento->buildings_id ){
                    $building = $empreendimento->buildings_id;
                    $fields['nameField'][] = 'building_origin';
                    $fields['valueField'][] = $empreendimento->name;
                }
            }
         /**
         * End Validate lead test for name and email
        */

        
        /**
         * Defined partner responsible
        */
            $companies_id = $this->partners( $building );
            $originSlug = $request->origin ? $request->origin : $request->origem;
            $origin = LeadsOrigins::where('companies_id', $companies_id)->where('slug', $originSlug)->first();
            if ( $origin ) {
                $origin = $origin->id;
            } else {
                $origin = LeadsOrigins::where('companies_id', $companies_id)->where('slug', 'like', '%default%')->first()->id;
            }
        /**
         * End Defined partner responsible
        */

       
         /**
         * Create new lead
        */
            $lead = Leads::create([
                'api' => true, 
                'name' => $name, 
                'phone' => $phone, 
                'email' => $email,
                'buildings_id' => $building,
                'leads_origins_id' => $origin,
                'companies_id' => $companies_id,
            ]);

            // Create custom fields
            if(isset($fields)){
                foreach($fields["nameField"] as $index => $field) {
                    LeadsFields::create([
                        'name' => $fields["nameField"][$index], 
                        'value' => $fields["valueField"][$index],
                        'leads_id' => $lead->id,
                    ]);
                }
            }
         /**
         * End Create new lead
        */


         /**
         * Send in integrations
        */ 
            ProcessBuildingJobs::dispatch($lead->id);  
        /**
         * End Send in integrations
        */


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

    /**
     * Define partner responsible 
     */
    private function partners( $building ) {
        $companies_id = '';
        $partners = BuildingsPartners::where( 'buildings_id', $building )->orderBy('created_at', 'desc')->get();
        if( $partners->first() ){
            foreach( $partners as $partner ){
                if( $partner->leads == 99 ){
                    $companies_id = $partner->companies_id;
                    break;
                } else {
                    $countAllPartners = BuildingsPartners::where( 'buildings_id', $building )->select('leads')->sum('leads');
                    $leads = Leads::where( 'buildings_id', $building )->orderBy('created_at', 'desc')->take( $countAllPartners - 1)->get();
                    $leadsPartner = $leads->sortBy('created_at')->where( 'companies_id', $partner->companies_id )->count();

                    if( $leadsPartner < $partner->leads ){
                        $companies_id = $partner->companies_id;
                        break;
                    }
                }
            }
        } 
        return $companies_id;
    }
}