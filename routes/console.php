<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Companies;
use App\Models\UsersRoles;
use App\Models\Users;
use App\Models\Buildings;
use App\Models\BuildingsKeys;
use App\Models\LeadsOrigins;
use App\Jobs\ProcessBuildingJobs;

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

Artisan::command('factory', function () {
    $companies = Companies::factory()->create();
    $usersroles = UsersRoles::factory()->create();
    $users = Users::factory()->create();
    $buildings = Buildings::factory()->create();
    $buildingsKeys = BuildingsKeys::factory()->create();
    $leadsorigins = LeadsOrigins::factory()->create();
})->purpose('Executar configurações default do sistema.');

Artisan::command('buildings', function () {
    ProcessBuildingJobs::dispatch(2);    
})->purpose('Testar processo de integração por lead.');