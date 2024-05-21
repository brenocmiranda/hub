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
use App\Http\Controllers\RelatoriosCtrl;
use App\Http\Controllers\UsersCtrl;
use App\Http\Controllers\UsersRolesCtrl;
use App\Http\Controllers\UsersTokensCtrl;

#---------------------------------------------------------------------
# Área não logada
#---------------------------------------------------------------------
Route::group(['prefix' => '/'], function () {
    // Funções externas
    Route::get('/', [PublicCtrl::class, 'login'])->name('login');
    Route::post('authentication', [PublicCtrl::class, 'authentication'])->name('authentication');
    Route::group(['prefix' => 'password'], function () {
        // Recuperação de senha
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

    // Funções internas
    Route::get('home', [PrivateCtrl::class, 'home'])->name('home');
    Route::get('logout', [PrivateCtrl::class, 'logout'])->name('logout');

    // Atividades
    Route::get('activities', [PrivateCtrl::class, 'activities'])->name('activities');

    // Perfil
    Route::singleton('profile', ProfileCtrl::class);
    
    // Dashboard
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardsCtrl::class, 'index'])->name('dashboard.index');
    });

    // Leads
    Route::resource('leads', LeadsCtrl::class)->only([ 'index', 'create', 'store', 'destroy', 'show' ]);
    Route::any('leads/all/search', [LeadsCtrl::class, 'search'])->name('leads.search');
    Route::get('retryAll', [LeadsCtrl::class, 'retryAll'])->name('leads.retryAll');
    Route::get('retry/{id}', [LeadsCtrl::class, 'retry'])->name('leads.retry');
    Route::get('resend/{id}', [LeadsCtrl::class, 'resend'])->name('leads.resend');

    // Leads (Origins)
    Route::resource('leads/all/origins', LeadsOriginsCtrl::class)->names([
        'index' => 'leads.origins.index',
        'create' => 'leads.origins.create',
        'store' => 'leads.origins.store',
        'edit' => 'leads.origins.edit',
        'update' => 'leads.origins.update',
        'destroy' => 'leads.origins.destroy',
        'show' => 'leads.origins.show'
    ]);

    // Leads (Pipelines)
    Route::resource('leads/all/pipelines', PipelinesCtrl::class)->names([
        'index' => 'leads.pipelines.index',
        'create' => 'leads.pipelines.create',
        'store' => 'leads.pipelines.store',
        'edit' => 'leads.pipelines.edit',
        'update' => 'leads.pipelines.update',
        'destroy' => 'leads.pipelines.destroy',
        'show' => 'leads.pipelines.show'
    ]);

    // Companies
    Route::resource('companies', CompaniesCtrl::class);

    // Buildings
    Route::resource('buildings', BuildingsCtrl::class);
    Route::any('leads/all/duplicate/{id}', [BuildingsCtrl::class, 'duplicate'])->name('buildings.duplicate');

    // Buildings (Keys)
    Route::resource('buildings/all/keys', BuildingsKeysCtrl::class)->names([
        'index' => 'buildings.keys.index',
        'create' => 'buildings.keys.create',
        'store' => 'buildings.keys.store',
        'edit' => 'buildings.keys.edit',
        'update' => 'buildings.keys.update',
        'destroy' => 'buildings.keys.destroy'
    ]);

    // Integrations
    Route::resource('integrations', IntegrationsCtrl::class);

    // Relatórios
    Route::group(['prefix' => 'reports'], function () {
        Route::group(['prefix' => 'leads'], function () {
            Route::get('/', [RelatoriosCtrl::class, 'reportsLeads'])->name('reports.leads');
            Route::any('generate', [RelatoriosCtrl::class, 'reportsLeadsGenerate'])->name('reports.leads.generate');
        });
        Route::group(['prefix' => 'buildings'], function () {
            Route::get('/', [RelatoriosCtrl::class, 'reportsBuildings'])->name('reports.buildings');
            Route::any('generate', [RelatoriosCtrl::class, 'reportsBuildingsGenerate'])->name('reports.buildings.generate');
        });
        Route::group(['prefix' => 'integrations'], function () {
            Route::get('/', [RelatoriosCtrl::class, 'reportsIntegrations'])->name('reports.integrations');
            Route::any('generate', [RelatoriosCtrl::class, 'reportsIntegrationsGenerate'])->name('reports.integrations.generate');
        });
    });

    // Usuários
    Route::resource('users', UsersCtrl::class);
    Route::any('recovery/{id}', [UsersCtrl::class, 'recovery'])->name('users.recovery');

    // Usuários (Roles)
    Route::resource('users/all/roles', UsersRolesCtrl::class)->names([
        'index' => 'users.roles.index',
        'create' => 'users.roles.create',
        'store' => 'users.roles.store',
        'edit' => 'users.roles.edit',
        'update' => 'users.roles.update',
        'destroy' => 'users.roles.destroy'
    ]);

    // Usuários (Tokens)
    Route::resource('users/all/tokens', UsersTokensCtrl::class)->names([
        'index' => 'users.tokens.index',
        'create' => 'users.tokens.create',
        'store' => 'users.tokens.store',
        'edit' => 'users.tokens.edit',
        'update' => 'users.tokens.update',
        'destroy' => 'users.tokens.destroy'
    ])->only([ 'index', 'create', 'store', 'destroy' ]);

})->middleware('auth');

