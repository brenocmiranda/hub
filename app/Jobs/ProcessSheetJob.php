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
use App\Models\BuildingsSheets;
use Revolution\Google\Sheets\Facades\Sheets;
use App\Models\Pipelines;
use App\Models\PipelinesLog;
use Throwable;

class ProcessSheetJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 60;

    public function __construct(protected $lead, protected $sheet)
    {
        $this->lead = $lead;
        $this->sheet = $sheet;
    }

    public function handle(): void
    {
        config([
            'google.service.enable' => true,
            'google.service.file' => storage_path('app/public/google/'. $this->sheet->file),
        ]);

        $data = $this->lead->created_at->format("d/m/Y H:i:s");
        $origin = $this->lead->RelationOrigins->name;
        $ticket = $this->lead->RelationFields->where('name', 'SrNumber')->first() ? $this->lead->RelationFields->where('name', 'SrNumber')->first()->value : "";
        $companie = $this->lead->RelationCompanies->name;
        $building = $this->lead->RelationBuildings->name;
        $contact = $this->lead->RelationFields->where('name', 'PartyNumber')->first() ? $this->lead->RelationFields->where('name', 'PartyNumber')->first()->value : "";
        $name = $this->lead->name;
        $email = $this->lead->email;
        $phone = $this->lead->phone;
        $utm_source = $this->lead->RelationFields->where('name', 'utm_source')->first() ? $this->lead->RelationFields->where('name', 'utm_source')->first()->value : '';
        $utm_medium = $this->lead->RelationFields->where('name', 'utm_medium')->first() ? $this->lead->RelationFields->where('name', 'utm_medium')->first()->value : '';
        $utm_campaign = $this->lead->RelationFields->where('name', 'utm_campaign')->first() ? $this->lead->RelationFields->where('name', 'utm_campaign')->first()->value : '';
        $utm_content = $this->lead->RelationFields->where('name', 'utm_content')->first() ? $this->lead->RelationFields->where('name', 'utm_content')->first()->value : '';
        $utm_term = $this->lead->RelationFields->where('name', 'utm_term')->first() ? $this->lead->RelationFields->where('name', 'utm_term')->first()->value : '';
        $url_params = ($utm_source ? 'utm_source=' . $utm_source : "") . ($utm_medium ? '&utm_medium=' . $utm_medium : "") . ($utm_campaign ? '&utm_campaign=' . $utm_campaign : "") . ($utm_content ? '&utm_content=' . $utm_content : "") . ($utm_term ? '&utm_term=' . $utm_term : "");
        $url = $this->lead->RelationFields->where('name', 'url')->first() ? $this->lead->RelationFields->where('name', 'url')->first()->value : '';

        $sl = Sheets::spreadsheet($this->sheet->spreadsheetID)->sheet($this->sheet->sheet)->append([
            [
                $data,
                $origin,
                $ticket,
                $companie, 
                $building,
                $contact,
                $name,
                $email,
                $phone,
                $utm_source,
                $utm_medium,
                $utm_campaign,
                $utm_content,
                $utm_term,
                $url_params,
                $url
            ]
        ]);

        // Salvando a pipeline de execução da integração
        $pipeline = Pipelines::create([
            'statusCode' => 2,
            'attempts' => $this->attempts(),
            'leads_id' => $this->lead->id,
            'buildings_id' => $this->lead->buildings_id,
            'integrations_id' => null
        ]);
        PipelinesLog::create([
            'header' => 'Envio dos dados para o sheets',
            'response' => json_encode($sl),
            'pipelines_id' => $pipeline->id
        ]);
    }
}
