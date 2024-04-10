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

class ProcessBuildingJobs implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected $id)  
    { 
        $this->id = $id;
    }

    public function handle(): void
    {   
        $lead = Leads::find($this->id);
        
        // Adicionando ao lote os jobs de integração
        if($lead->RelationBuildings->RelationIntegrations->first()) {
            $integrations = $lead->RelationBuildings->RelationIntegrations;   
            foreach($integrations as $integration) {
                $listOfJobs[] = new ProcessIntegrationJob($lead, $integration);
            }
        }

        // Adicionando ao lote os jobs de sheets
        if($lead->RelationBuildings->RelationSheets->first()) {
            $listOfJobs[] = new ProcessSheetJob($lead);
        }

        // Adicionando ao lote os jobs de envio de e-mail
        if($lead->RelationBuildings->RelationDestinatarios->first()) {
            $listOfJobs[] = new ProcessMailJob($lead);
        }

        Bus::batch([ $listOfJobs ])
        ->catch(function (Batch $batch, Throwable $e) use ($lead) {
            $usersRole = UsersRoles::where('name', "like", "%admin%")->first();
            $users = Users::where('user_role_id', $usersRole->id)->get();
            foreach($users as $user){
                $user->notify(new ErrorLead( $user, $lead, $e->getMessage() ));
            }
        })
        ->name('Processos do empreendimento')
        ->onQueue('buildings')
        ->dispatch();
    }
}
