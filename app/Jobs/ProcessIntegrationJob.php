<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Batch;
use App\Models\LeadsFields;
use App\Models\Pipelines;
use App\Models\PipelinesLog;
use Throwable;

class ProcessIntegrationJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 60;

    public function __construct(protected $lead, protected $integration)
    {
        $this->lead = $lead;
        $this->integration = $integration;
    }

    public function handle(): void
    {   
        // Criando body da integração
        $bodyFields = $this->lead->RelationBuildings->RelationIntegrationsFields;
        foreach($bodyFields as $bodyField) {
            if( $bodyField->integrations_id === $this->integration->id ){

                $arr_nome = explode(' ', $this->lead->name);
                $array = [
                    '$nomeCompleto' => $this->lead->name,
                    '$nomeInicial' => $arr_nome[0],
                    '$nomeFinal' => $arr_nome[ count( $arr_nome ) - 1 ],
                    '$telefoneCompleto' => $this->lead->phone,
                    '$dda' => '55',
                    '$ddd' => substr( $this->lead->phone, 0, 2 ),
                    '$number' => substr( $this->lead->phone, 2 ),
                    '$email' => $this->lead->email,
                    '$origin' => $this->lead->RelationOrigins->name,
                    '$nomeEmpreendimento' => $this->lead->RelationBuildings->name,
                    '$pp' => $this->lead->RelationFields->where('name', 'pp')->last() ? $this->lead->RelationFields->where('name', 'pp')->last()->value : 'Y',
                    '$com' => $this->lead->RelationFields->where('name', 'com')->last() ? 'Y' : 'N',
                    '$utm_source' => $this->lead->RelationFields->where('name', 'utm_source')->last() ? $this->lead->RelationFields->where('name', 'utm_source')->last()->value : '',
                    '$utm_xrm' => $this->lead->RelationFields->where('name', 'utm_source')->last() ? self::get_utm_source_by_valor( $this->lead->RelationFields->where('name', 'utm_source')->last()->value ) : self::get_utm_source_by_valor( "default" ),
                    '$utm_medium' => $this->lead->RelationFields->where('name', 'utm_medium')->last() ? $this->lead->RelationFields->where('name', 'utm_medium')->last()->value : '',
                    '$utm_campaign' => $this->lead->RelationFields->where('name', 'utm_campaign')->last() ? $this->lead->RelationFields->where('name', 'utm_campaign')->last()->value : '',
                    '$utm_content' => $this->lead->RelationFields->where('name', 'utm_content')->last() ? $this->lead->RelationFields->where('name', 'utm_content')->last()->value : '',
                    '$utm_term' => $this->lead->RelationFields->where('name', 'utm_term')->last() ? $this->lead->RelationFields->where('name', 'utm_term')->last()->value : '',
                    '$message' => $this->lead->RelationFields->where('name', 'message')->last() ? $this->lead->RelationFields->where('name', 'message')->last()->value : '',
                    '$PartyNumber' => $this->lead->RelationFields->where('name', 'PartyNumber')->last() ? $this->lead->RelationFields->where('name', 'PartyNumber')->last()->value : '',
                    '$SrNumber' => $this->lead->RelationFields->where('name', 'SrNumber')->last() ? $this->lead->RelationFields->where('name', 'SrNumber')->last()->value : '',
                ];

                foreach($array as $index => $element){
                    if ( strpos($bodyField->value, $index) !== false ){
                        $bodyField->value = str_replace($index, $element, $bodyField->value);
                    } 
                }

                $body[$bodyField->name] = $bodyField->value;
            }
        }

        // Convertando name em array    
        $body = self::dotKeyToArray( $body );

        // Salvando a pipeline com o body sending
        $pipeline = Pipelines::create([
            'statusCode' => 0,
            'attempts' => $this->attempts(),
            'leads_id' => $this->lead->id,
            'buildings_id' => $this->lead->buildings_id,
            'integrations_id' => $this->integration->id
        ]);
        PipelinesLog::create([
            'header' => 'Campos enviados',
            'response' => json_encode($body),
            'pipelines_id' => $pipeline->id
        ]);
        
        $url = $this->integration->url;
        $glue = strpos($url, '?') !== false ? '&' : '?';
        $header[] = $this->integration->header;
        $body = $this->integration->encoded ? http_build_query($body) : $body;

        switch( $this->integration->type ) {
            case 'POST':
                if( !empty($this->integration->token) ){
                    $response = Http::withHeaders($header)
                                ->timeout(100)
                                ->withToken($this->integration->token)
                                ->post($url, $body);
                } elseif( !empty($this->integration->user) && !empty($this->integration->password) ){
                    $response = Http::withHeaders($header)
                                ->timeout(100)
                                ->withBasicAuth($this->integration->user, $this->integration->password)
                                ->post($url, $body);
                } else {
                    $response = Http::withHeaders($header)
                                ->timeout(100)
                                ->post($url, $body);
                }
                break;
            
            case 'GET':
                if( !empty($this->integration->token) ){
                    $response = Http::withHeaders($header)
                                ->timeout(100)
                                ->withToken($this->integration->token)
                                ->get($url . $glue . $body);
                } elseif( !empty($this->integration->user) && !empty($this->integration->password) ){
                    $response = Http::withHeaders($header)
                                ->timeout(100)
                                ->withBasicAuth($this->integration->user, $this->integration->password)
                                ->get($url . $glue . $body);
                } else {
                    $response = Http::withHeaders($header)
                                ->timeout(100)
                                ->get($url . $glue . $body);
                }
                break;
        }

        // Salvando a pipeline de execução da integração
        $pipeline = Pipelines::create([
            'statusCode' => $response->status(),
            'attempts' => $this->attempts(),
            'leads_id' => $this->lead->id,
            'buildings_id' => $this->lead->buildings_id,
            'integrations_id' => $this->integration->id
        ]);
        PipelinesLog::create([
            'header' => json_encode($response->headers()),
            'response' => json_encode($response->body()),
            'pipelines_id' => $pipeline->id
        ]);
                        
        // Validando se obtivemos sucesso no HTTP Client 
        if( $response->successful() ){
            // Retornando dados da integração vinculando ao lead
            $result = json_decode($response->body(), true);
            if($this->integration->slug === 'xrm-contatos-create' || $this->integration->slug === 'xrm-contatos') {
                $PartyNumber = $this->lead->RelationFields->where('name', 'PartyNumber')->last() ? $this->lead->RelationFields->where('name', 'PartyNumber')->last()->value : null;
                if( empty($PartyNumber) ){
                    LeadsFields::create([
                        'name' => 'PartyNumber',
                        'value' => $result['PartyNumber'] ? $result['PartyNumber'] : '-',
                        'leads_id' => $this->lead->id
                    ]);
                }else {
                    LeadsFields::where('leads_id', $this->lead->id)->where('name', 'PartyNumber')->update([
                        'value' => $result['PartyNumber'] ? $result['PartyNumber'] : '-',
                    ]);
                }
            }elseif($this->integration->slug === 'xrm-tickets-create' || $this->integration->slug === 'xrm-tickets') {
                LeadsFields::create([
                    'name' => 'SrNumber',
                    'value' => $result['SrNumber'] ? $result['SrNumber'] : '-',
                    'leads_id' => $this->lead->id
                ]);
            }elseif( $this->integration->slug === 'capys' ) { 
                if( isset($result['idCaso']) ){
                    LeadsFields::create([
                        'name' => 'idCaso',
                        'value' => $result['idCaso'],
                        'leads_id' => $this->lead->id
                    ]);
                } 
            }
        } else {
            throw new \Exception('Erro ' . $response->status() . ' na execução da integração. <br /> ' . $response->body(), true);
        }
    }

    /** 
     * Function de utm_source_xrm 
    */
    public function get_utm_source_by_valor( $source ){
        $sources = [
            'acaopromocional' => 'PER_IMPACMID_ACAOPROMO',
            'acao-promocional' => 'PER_IMPACMID_ACAOPROMO',
            'bing' => 'PER_IMPACMID_BING',
            'evento-feira' => 'PER_IMPACMID_EVENTOFEIRA',
            'evento' => 'PER_IMPACMID_EVENTOFEIRA',
            'feira' => 'PER_IMPACMID_EVENTOFEIRA',
            'instagram' => 'PER_IMPACMID_INSTAGRAM',
            'ig' => 'PER_IMPACMID_INSTAGRAM',
            'facebook' => 'PER_IMPACMID_FACEBOOK',
            'fb' => 'PER_IMPACMID_FACEBOOK',
            'meta-ads' => 'PER_IMPACMID_FACEBOOK',
            'meta' => 'PER_IMPACMID_FACEBOOK',
            'linkedin' => 'PER_IMPACMID_LINKEDIN',
            'google' => 'PER_IMPACMID_GOOGLE',
            'gads' => 'PER_IMPACMID_GOOGLE',
            'google-ads' => 'PER_IMPACMID_GOOGLE',
            'display' => 'PER_IMPACMID_GOOGLE',
            'search' => 'PER_IMPACMID_GOOGLE',
            'max-performance' => 'PER_IMPACMID_GOOGLE',
            'zapimoveis' => 'PER_IMPACMID_ZAPIMOVEIS',
            'zap-imoveis' => 'PER_IMPACMID_ZAPIMOVEIS',
            'vivareal' => 'PER_IMPACMID_VIVAREAL',
            'viva-real' => 'PER_IMPACMID_VIVAREAL',
            'imovelweb' => 'PER_IMPACMID_IMOVELWEB',
            'indicacao' => 'PER_IMPACMID_INDICACAOINTERNA',
            'influenciador' => 'PER_IMPACMID_INFLUENCIADOR',
            'interacao-nas-redes-sociais-instagram-facebook' => 'PER_IMPACMID_INTREDESSOCIAIS',
            'redes-sociais' => 'PER_IMPACMID_INTREDESSOCIAIS',
            'outdoor' => 'PER_IMPACMID_OUTDOOR',
            'ooh' => 'PER_IMPACMID_OUTDOOR',
            'tapume' => 'PER_IMPACMID_OUTDOOR',
            'outros' => 'PER_IMPACMID_OUTROS',
            'publya' => 'PER_IMPACMID_MIDIAPROGRA',
            'publya-display' => 'PER_IMPACMID_MIDIAPROGRA',
            'programatica' => 'PER_IMPACMID_MIDIAPROGRA',
            'portais-imobiliarios' => 'PER_IMPACMID_PORTAISIMOB',
            'prospeccao-e-divulgacao-do-corretor-imobiliaria' => 'PER_IMPACMID_PROSPECCORRETOR',
            'prospeccao-corretor' => 'PER_IMPACMID_PROSPECCORRETOR',
            'divulgacao-corretor' => 'PER_IMPACMID_PROSPECCORRETOR',
            'prospeccao-imobiliaria' => 'PER_IMPACMID_PROSPECCORRETOR',
            'divulgacao-imobiliaria' => 'PER_IMPACMID_PROSPECCORRETOR',
            'revista' => 'PER_IMPACMID_REVISTA',
            'radio' => 'PER_IMPACMID_RADIO',
            'site' => 'PER_IMPACMID_SITE',
            'sites' => 'PER_IMPACMID_SITE',
            'stand' => 'PER_IMPACMID_STAND',
            'tv' => 'PER_IMPACMID_TV',
            'youtube' => 'PER_IMPACMID_YOUTUBE',
            'twitter' => 'PER_IMPACMID_INTREDESSOCIAIS',
            'portais-verticais' => 'PER_IMPACMID_PORTAISIMOB'
        ];
        $source = self::slugify( $source );
        if( !$source || !isset( $sources[ $source ] ) ) return 'PER_IMPACMID_SITE';
        return $sources[ $source ];
    }

    /** 
     * Function de clean string 
    */
    public function slugify( $str = '', $divider = '-', $extras = [] ){
        if( empty( $str ) ){
            return '';
        }

        $str = strtolower( $str );

        // transliterate
        $str = iconv('utf-8', 'us-ascii//TRANSLIT', $str );

        // remove unwanted characters
        #$str = preg_replace('~[^-\w]+~', '', $str );
        $list = array_merge( [ 'š' => 's', 'đ' => 'dj', 'ž' => 'z', 'č' => 'c', 'ć' => 'c', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'ŕ' => 'r', '/' => $divider, ' ' => $divider, '.' => $divider, '@' => 'a', '^' => '', '~' => '', '´' => '', '`' => '', '\'' => '' ], $extras );
        $str = strtr( $str, $list );

        // replace non letter or digits by divider
        $str = preg_replace( '~[^\pL\d]+~u', $divider, $str );

        // trim
        $str = trim( $str, $divider );

        // remove duplicate divider
        $str = preg_replace('~-+~', $divider, $str );

        // lowercase

        return $str;
    }

    /** 
     * Function de convert string in array (extras.conversionOrigin.source)  
    */
    function assignArrayByPath( &$arr, $path, $value, $separator = '.' ){
		$keys = explode( $separator, $path );
		foreach( $keys as $key ){
			$arr = &$arr[$key];
		}
		$arr = $value;
	}
	function dotKeyToArray( $arr, $separator = '.' ){
        $arr2 = json_decode( json_encode( $arr ), true );
		foreach( $arr2 as $key => $value ){
            if ( strpos($key, '.') !== false ){
				self::assignArrayByPath( $arr2, $key, $value, '.' );
				unset( $arr2[ $key ] );
			}
		}
		return $arr2;
	}
}
