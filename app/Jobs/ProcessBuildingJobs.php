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
use App\Mail\ErrorLead;
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
                if( $lead->companies_id === $integration->companies_id ){
                    $listOfJobs[] = new ProcessIntegrationJob($lead, $integration);
                }
            }
        }

        // Adicionando ao lote os jobs de sheets
        if($lead->RelationBuildings->RelationSheets->first()) {
            $sheets = $lead->RelationBuildings->RelationSheets;
            foreach( $sheets as $sheet ){
                $listOfJobs[] = new ProcessSheetJob($lead, $sheet);
            }
        }

        // Adicionando ao lote os jobs de envio de e-mail
        if($lead->RelationBuildings->RelationDestinatarios->first()) {
            $listOfJobs[] = new ProcessMailJob($lead);
        }

        if( !empty( $listOfJobs ) ){
            Bus::batch([ $listOfJobs ])
            ->before(function (Batch $batch) use ($lead) {
                Leads::find($lead->id)->update([ 'batches_id' => $batch->id ]);
            })
            ->catch(function (Batch $batch, Throwable $e) use ($lead) {
                $usersRole = UsersRoles::where('name', "like", "%admin%")->first();
                $users = Users::where('users_roles_id', $usersRole->id)->get();
                foreach($users as $user){
                    Mail::to( $user )->send(new ErrorLead( $user, $lead, $e->getMessage() ));
                }
            })
            ->name('Processos do empreendimento')
            ->onQueue('buildings')
            ->dispatch();
        }
    }
}
