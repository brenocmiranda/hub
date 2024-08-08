<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuildingsCtrl;
use App\Http\Controllers\BuildingsKeysCtrl;
use App\Http\Controllers\CompaniesCtrl;
use App\Http\Controllers\DashboardsCtrl;
use App\Http\Controllers\IntegrationsCtrl;
use App\Http\Controllers\LeadsCtrl;
use App\Http\Controllers\LeadsOriginsCtrl;
use App\Http\Controllers\PrivateCtrl;
use App\Http\Controllers\PublicCtrl;
use App\Http\Controllers\ProfileCtrl;
use App\Http\Controllers\PipelinesCtrl;
use App\Http\Controllers\UsersCtrl;
use App\Http\Controllers\UsersRolesCtrl;
use App\Http\Controllers\TokensCtrl;
use App\Http\Controllers\ImportsCtrl;
use App\Http\Controllers\ReportsCtrl;

#---------------------------------------------------------------------
# Área não logada
#---------------------------------------------------------------------
Route::group(['prefix' => '/'], function () {
    // Login e Auth
    Route::get('/', [PublicCtrl::class, 'login'])->name('login');
    Route::post('authentication', [PublicCtrl::class, 'authentication'])->name('authentication');

    // Recuperação de senha
    Route::group(['prefix' => 'password'], function () {
        Route::get('/', [PublicCtrl::class, 'recovery'])->name('recovery.password');
        Route::post('recovering', [PublicCtrl::class, 'recovering'])->name('recovering.password');
        Route::get('verify/{token}', [PublicCtrl::class, 'verify'])->name('verify.password');
        Route::post('reset', [PublicCtrl::class, 'reset'])->name('reset.password');
    });
});

#---------------------------------------------------------------------
# Aplicação
#---------------------------------------------------------------------
Route::group(['prefix' => 'app'], function () {

    // Home and Logout
    Route::get('home', [PrivateCtrl::class, 'home'])->name('home');
    Route::get('logout', [PrivateCtrl::class, 'logout'])->name('logout');

    // Perfil
    Route::singleton('profile', ProfileCtrl::class);

    // Tokens
    Route::resource('tokens', TokensCtrl::class)->names([
        'index' => 'tokens.index',
        'create' => 'tokens.create',
        'store' => 'tokens.store',
        'edit' => 'tokens.edit',
        'update' => 'tokens.update',
        'destroy' => 'tokens.destroy'
    ])->only([ 'index', 'create', 'store', 'destroy' ]);
    
    // Atividades
    Route::get('activities', [PrivateCtrl::class, 'activities'])->name('activities');

    // Dashboard
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('all', [DashboardsCtrl::class, 'index'])->name('all.index');
        Route::get('buildings', [DashboardsCtrl::class, 'index'])->name('buildings.index');
    })->middleware('can:dashboard_show');

    // Leads - Ajax
    Route::resource('leads', LeadsCtrl::class)->only([ 'index', 'create', 'store', 'destroy', 'show' ]);
    Route::group(['prefix' => 'leads/all/'], function () {
        Route::get('data', [LeadsCtrl::class, 'data'])->name('leads.data');
        Route::get('search', [LeadsCtrl::class, 'search'])->name('leads.search');
        Route::get('retry/{id}', [LeadsCtrl::class, 'retry'])->name('leads.retry');
        Route::get('resend/{id}', [LeadsCtrl::class, 'resend'])->name('leads.resend');
    });    

    // Leads (Origins) - Ajax
    Route::resource('leads/all/origins', LeadsOriginsCtrl::class)->names([
        'index' => 'leads.origins.index',
        'create' => 'leads.origins.create',
        'store' => 'leads.origins.store',
        'edit' => 'leads.origins.edit',
        'update' => 'leads.origins.update',
        'destroy' => 'leads.origins.destroy',
        'show' => 'leads.origins.show'
    ]);
    Route::group(['prefix' => 'leads/all/origins/all/'], function () {
        Route::get('data', [LeadsOriginsCtrl::class, 'data'])->name('leads.origins.data');
    });

    // Leads (Pipelines) - Ajax
    Route::resource('leads/all/pipelines', PipelinesCtrl::class)->names([
        'index' => 'leads.pipelines.index',
        'create' => 'leads.pipelines.create',
        'store' => 'leads.pipelines.store',
        'edit' => 'leads.pipelines.edit',
        'update' => 'leads.pipelines.update',
        'destroy' => 'leads.pipelines.destroy',
        'show' => 'leads.pipelines.show'
    ]);
    Route::group(['prefix' => 'leads/all/pipelines/all/'], function () {
        Route::get('retryAll', [PipelinesCtrl::class, 'retryAll'])->name('leads.pipelines.retryAll');
        Route::get('data', [PipelinesCtrl::class, 'data'])->name('leads.pipelines.data');
    });

    // Companies - Ajax
    Route::resource('companies', CompaniesCtrl::class);
    Route::group(['prefix' => 'companies/all/'], function () {
        Route::get('data', [CompaniesCtrl::class, 'data'])->name('companies.data');
    });

    // Buildings - Ajax
    Route::resource('buildings', BuildingsCtrl::class);
    Route::group(['prefix' => 'buildings/all/'], function () {
        Route::get('data', [BuildingsCtrl::class, 'data'])->name('buildings.data');
        Route::any('duplicate/{id}', [BuildingsCtrl::class, 'duplicate'])->name('buildings.duplicate');
    });

    // Buildings (Keys) - Ajax
    Route::resource('buildings/all/keys', BuildingsKeysCtrl::class)->names([
        'index' => 'buildings.keys.index',
        'create' => 'buildings.keys.create',
        'store' => 'buildings.keys.store',
        'edit' => 'buildings.keys.edit',
        'update' => 'buildings.keys.update',
        'destroy' => 'buildings.keys.destroy'
    ]);
    Route::group(['prefix' => 'buildings/all/keys/all/'], function () {
        Route::get('data', [BuildingsKeysCtrl::class, 'data'])->name('buildings.keys.data');
    });

    // Integrations - Ajax
    Route::resource('integrations', IntegrationsCtrl::class);
    Route::group(['prefix' => 'integrations/all/'], function () {
        Route::get('data', [IntegrationsCtrl::class, 'data'])->name('integrations.data');
    });

    // Relatórios - Ajax
    Route::resource('reports', ReportsCtrl::class)->only([ 'index', 'create', 'store', 'destroy' ]);
    Route::group(['prefix' => 'reports/all/'], function () {
        Route::get('data', [ReportsCtrl::class, 'data'])->name('reports.data');
    });

    // Importações - Ajax
    Route::resource('imports', ImportsCtrl::class)->only([ 'index', 'create', 'store', 'destroy' ]);
    Route::group(['prefix' => 'imports/all/'], function () {
        Route::get('data', [ImportsCtrl::class, 'data'])->name('imports.data');
    });

    // Usuários - Ajax
    Route::resource('users', UsersCtrl::class);
    Route::group(['prefix' => 'users/all/'], function () {
        Route::get('data', [UsersCtrl::class, 'data'])->name('users.data');
        Route::any('recovery/{id}', [UsersCtrl::class, 'recovery'])->name('users.recovery');
    });

    // Usuários (Roles)
    Route::resource('users/all/roles', UsersRolesCtrl::class)->names([
        'index' => 'users.roles.index',
        'create' => 'users.roles.create',
        'store' => 'users.roles.store',
        'edit' => 'users.roles.edit',
        'update' => 'users.roles.update',
        'destroy' => 'users.roles.destroy'
    ]);
    Route::group(['prefix' => 'users/all/roles/all/'], function () {
        Route::get('data', [UsersRolesCtrl::class, 'data'])->name('users.roles.data');
    });

})->middleware('auth');

