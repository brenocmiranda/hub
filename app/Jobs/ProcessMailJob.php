<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Mail;
use App\Mail\Lead;
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
        $emails = BuildingsDestinatarios::where('buildings_id', $this->lead->buildings_id)->get();
        foreach( $emails as $email ){
            $emails[] = $email->email;
        } 
        $toMail = Mail::to( $emails )->send( new Lead( $this->lead ) );

        // Salvando a pipeline de execução da integração
        $pipeline = Pipelines::create([
            'statusCode' => 1,
            'attempts' => $this->attempts(),
            'leads_id' => $this->lead->id,
            'buildings_id' => $this->lead->buildings_id,
            'integrations_id' => null
        ]);
        PipelinesLog::create([
            'header' => 'Envio de e-mail aos destinatários',
            'response' => json_encode( $toMail ) . json_encode( $emails ),
            'pipelines_id' => $pipeline->id
        ]);
    }
}
