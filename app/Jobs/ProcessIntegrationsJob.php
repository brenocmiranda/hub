<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Batch;
use App\Notifications\ErrorLead;
use App\Models\Users;
use App\Models\UsersRoles;
use App\Models\UsersLogs;
use App\Models\Leads;
use Throwable;

class ProcessIntegrationsJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected $id)  
    { 
        $this->id = $id;
    }

    public function handle(): void
    {   
        $lead = Leads::find($this->id);
        $integrations = $lead->RelationBuildings->RelationIntegrations;   

        foreach($integrations as $integration) {
            $listOfJobs[] = new ProcessIntegrationJob($lead, $integration);
        }

        Bus::batch([ $listOfJobs ])
        ->catch(function (Batch $batch, Throwable $e) use ($lead) {
            $usersRole = UsersRoles::where('name', "like", "%admin%")->first();
            $users = Users::where('user_role_id', $usersRole->id)->get();
            foreach($users as $user){
                $user->notify(new ErrorLead( $user, $lead, $e->getMessage() ));
            }

            $batch->release(60);
        })
        ->name('Processo de integração')
        ->onQueue('integrations')
        ->dispatch();
    }
}
