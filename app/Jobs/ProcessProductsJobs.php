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

class ProcessProductJobs implements ShouldQueue
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
        if($lead->RelationProducts->RelationIntegrations->first()) {
            $integrations = $lead->RelationProducts->RelationIntegrations;   
            foreach($integrations as $integration) {
                if( $lead->companies_id === $integration->companies_id ){
                    $listOfJobs[] = new ProcessIntegrationJob($lead, $integration);
                }
            }
        }

        // Adicionando ao lote os jobs de sheets
        if($lead->RelationProducts->RelationSheets->first()) {
            $sheets = $lead->RelationProducts->RelationSheets;
            foreach( $sheets as $sheet ){
                $listOfJobs[] = new ProcessSheetJob($lead, $sheet);
            }
        }

        // Adicionando ao lote os jobs de envio de e-mail
        if($lead->RelationProducts->RelationDestinatarios->first()) {
            $listOfJobs[] = new ProcessMailJob($lead);
        }

        if( !empty( $listOfJobs ) ){
            Bus::batch([ $listOfJobs ])
            ->before(function (Batch $batch) use ($lead) {
                Leads::find($lead->id)->update([ 'batches_id' => $batch->id ]);
            })
            ->catch(function (Batch $batch, Throwable $e) use ($lead) {
                $usersRole = UsersRoles::whereLike('name', "%admin%")->first();
                $users = Users::where('users_roles_id', $usersRole->id)->get();
                foreach($users as $user){
                    Mail::to( $user )->send(new ErrorLead( $user, $lead, $e->getMessage() ));
                }
            })
            ->name('Processos do produto')
            ->onQueue('products')
            ->dispatch();
        }
    }
}
