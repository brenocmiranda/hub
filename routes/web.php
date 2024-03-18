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
    Route::resource('leads', LeadsCtrl::class)->only([ 'index', 'create', 'store', 'show' ]);

    // Leads (Origins)
    Route::resource('leads/all/origins', LeadsOriginsCtrl::class)->names([
        'index' => 'leads.origins.index',
        'create' => 'leads.origins.create',
        'store' => 'leads.origins.store',
        'edit' => 'leads.origins.edit',
        'update' => 'leads.origins.update',
        'destroy' => 'leads.origins.destroy'
    ]);

    // Companies
    Route::resource('companies', CompaniesCtrl::class);

    // Buildings
    Route::resource('buildings', BuildingsCtrl::class);

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

