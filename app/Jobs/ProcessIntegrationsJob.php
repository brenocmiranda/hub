<?php

namespace App\Jobs;

use App\Models\Leads;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class ProcessIntegrationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $id)  
    { 
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {   
        $lead = Leads::find($this->id);
        $integrations = $lead->RelationBuildings->RelationIntegrations;   

        foreach($integrations as $integration) {
            $listOfJobs[] = new ProcessIntegrationJob($lead, $integration);
        }

        Bus::chain($listOfJobs)->onQueue('integrations')->dispatch();
    }
}
