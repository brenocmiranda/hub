<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Companies;
use App\Models\UsersRoles;
use App\Models\Users;
use App\Models\Leads;
use App\Models\LeadsOrigins;
use App\Jobs\ProcessBuildingJobs;
use App\Notifications\Lead;
use Illuminate\Support\Facades\Notification;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('new', function () {
    $companies = Companies::factory()->create();
    $usersRoles = UsersRoles::factory()->create();
    $users = Users::factory()->create();
    $leadsorigins = LeadsOrigins::factory()->create();
})->purpose('Executar configurações default do sistema.');

Artisan::command('buildings/{id}', function ($id) {
    ProcessBuildingJobs::dispatch($id);    
})->purpose('Testar processo de integração por lead.');

Artisan::command('email', function () {
    $usersRole = UsersRoles::where('name', "like", "%admin%")->first();
    $users = Users::where('user_role_id', $usersRole->id)->get();
    $lead = Leads::find(1);
    foreach($users as $user){
        $user->notify(new Lead( $lead ));
    }
})->purpose('Testar processo de envio de email.');