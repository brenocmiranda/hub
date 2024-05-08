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
use App\Notifications\Lead;
use App\Models\BuildingsDestinatarios;
use App\Models\Pipelines;
use App\Models\PipelinesLog;
use Throwable;

class ProcessMailJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 60;

    public function __construct(protected $lead)
    {
        $this->lead = $lead;
    }

    public function handle(): void
    {   
        $emails = BuildingsDestinatarios::where('building_id', $this->lead->building_id)->get();
        foreach( $emails as $email ){
            $email->notify(new Lead( $this->lead ));
            $emt[] = $email->email;
        } 

        // Salvando a pipeline de execução da integração
        $pipeline = Pipelines::create([
            'statusCode' => 1,
            'attempts' => $this->attempts(),
            'lead_id' => $this->lead->id,
            'buildings_has_integrations_building_id' => $this->lead->RelationBuildings->id,
            'buildings_has_integrations_integration_id' => null
        ]);
        PipelinesLog::create([
            'header' => 'Envio de e-mail aos destinatários',
            'response' => json_encode($emt),
            'pipeline_id' => $pipeline->id
        ]);
    }
}
