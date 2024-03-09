<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuildingsCtrl;
use App\Http\Controllers\BuildingsKeysCtrl;
use App\Http\Controllers\CompaniesCtrl;
use App\Http\Controllers\DashboardsCtrl;
use App\Http\Controllers\IntegrationsCtrl;
use App\Http\Controllers\LeadsCtrl;
use App\Http\Controllers\LeadsOriginsCtrl;
use App\Http\Controllers\SystemCtrl;
use App\Http\Controllers\UsersCtrl;
use App\Http\Controllers\UsersRolesCtrl;
use App\Http\Controllers\UsersTokensCtrl;

#---------------------------------------------------------------------
# Área não logada
#---------------------------------------------------------------------
Route::group(['prefix' => '/'], function () {
    // Funções externas
    Route::get('/', [SystemCtrl::class, 'login'])->name('login');
    Route::post('authentication', [SystemCtrl::class, 'authentication'])->name('authentication');
    Route::group(['prefix' => 'password'], function () {
        // Recuperação de senha
        Route::get('/', [SystemCtrl::class, 'recovery'])->name('recovery.password');
        Route::post('recovering', [SystemCtrl::class, 'recovering'])->name('recovering.password');
        Route::get('verify/{token}', [SystemCtrl::class, 'verify'])->name('verify.password');
        Route::post('reset', [SystemCtrl::class, 'reset'])->name('reset.password');
    });
});

#---------------------------------------------------------------------
# Aplicação
#---------------------------------------------------------------------
Route::group(['prefix' => 'app'], function () {
    // Funções internas
    Route::get('home', [SystemCtrl::class, 'home'])->name('home');
    Route::get('logout', [SystemCtrl::class, 'logout'])->name('logout');

    // Dashboard
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardsCtrl::class, 'index'])->name('index.dashboard');
    });

    // Leads
    Route::group(['prefix' => 'leads'], function () {
        Route::get('/', [LeadsCtrl::class, 'index'])->name('index.leads');
        Route::any('show/{id}', [LeadsCtrl::class, 'show'])->name('show.leads');
        Route::get('create/', [LeadsCtrl::class, 'create'])->name('create.leads');
        Route::post('store/', [LeadsCtrl::class, 'store'])->name('store.leads');
        Route::get('edit/{id}', [LeadsCtrl::class, 'edit'])->name('edit.leads');
        Route::post('update/{id}', [LeadsCtrl::class, 'update'])->name('update.leads');
        Route::any('destroy/{id}', [LeadsCtrl::class, 'destroy'])->name('destroy.leads');
        // Origins
        Route::group(['prefix' => 'origins'], function () {
            Route::get('/', [LeadsOriginsCtrl::class, 'index'])->name('index.leads.origins');
            Route::get('create/', [LeadsOriginsCtrl::class, 'create'])->name('create.leads.origins');
            Route::post('store/', [LeadsOriginsCtrl::class, 'store'])->name('store.leads.origins');
            Route::get('edit/{id}', [LeadsOriginsCtrl::class, 'edit'])->name('edit.leads.origins');
            Route::post('update/{id}', [LeadsOriginsCtrl::class, 'update'])->name('update.leads.origins');
            Route::any('destroy/{id}', [LeadsOriginsCtrl::class, 'destroy'])->name('destroy.leads.origins');
        });
    });

    // Companies
    Route::group(['prefix' => 'companies'], function () {
        Route::get('/', [CompaniesCtrl::class, 'index'])->name('index.companies');
        Route::get('create/', [CompaniesCtrl::class, 'create'])->name('create.companies');
        Route::post('store/', [CompaniesCtrl::class, 'store'])->name('store.companies');
        Route::get('edit/{id}', [CompaniesCtrl::class, 'edit'])->name('edit.companies');
        Route::post('update/{id}', [CompaniesCtrl::class, 'update'])->name('update.companies');
        Route::any('destroy/{id}', [CompaniesCtrl::class, 'destroy'])->name('destroy.companies');
    });

    // Buildings
    Route::group(['prefix' => 'buildings'], function () {
        Route::get('/', [BuildingsCtrl::class, 'index'])->name('index.buildings');
        Route::get('create/', [BuildingsCtrl::class, 'create'])->name('create.buildings');
        Route::post('store/', [BuildingsCtrl::class, 'store'])->name('store.buildings');
        Route::get('edit/{id}', [BuildingsCtrl::class, 'edit'])->name('edit.buildings');
        Route::post('update/{id}', [BuildingsCtrl::class, 'update'])->name('update.buildings');
        Route::any('destroy/{id}', [BuildingsCtrl::class, 'destroy'])->name('destroy.buildings');
        // Keys
        Route::group(['prefix' => 'keys'], function () {
            Route::get('/', [BuildingsKeysCtrl::class, 'index'])->name('index.buildings.keys');
            Route::get('create/', [BuildingsKeysCtrl::class, 'create'])->name('create.buildings.keys');
            Route::post('store/', [BuildingsKeysCtrl::class, 'store'])->name('store.buildings.keys');
            Route::get('edit/{id}', [BuildingsKeysCtrl::class, 'edit'])->name('edit.buildings.keys');
            Route::post('update/{id}', [BuildingsKeysCtrl::class, 'update'])->name('update.buildings.keys');
            Route::any('destroy/{id}', [BuildingsKeysCtrl::class, 'destroy'])->name('destroy.buildings.keys');
        });
    });

    // Integrations
    Route::group(['prefix' => 'integrations'], function () {
        Route::get('/', [IntegrationsCtrl::class, 'index'])->name('index.integrations');
        Route::get('create/', [IntegrationsCtrl::class, 'create'])->name('create.integrations');
        Route::post('store/', [IntegrationsCtrl::class, 'store'])->name('store.integrations');
        Route::get('edit/{id}', [IntegrationsCtrl::class, 'edit'])->name('edit.integrations');
        Route::post('update/{id}', [IntegrationsCtrl::class, 'update'])->name('update.integrations');
        Route::any('destroy/{id}', [IntegrationsCtrl::class, 'destroy'])->name('destroy.integrations');
    });

    // Usuários
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UsersCtrl::class, 'index'])->name('index.users');
        Route::get('create/', [UsersCtrl::class, 'create'])->name('create.users');
        Route::post('store/', [UsersCtrl::class, 'store'])->name('store.users');
        Route::get('edit/{id}', [UsersCtrl::class, 'edit'])->name('edit.users');
        Route::post('update/{id}', [UsersCtrl::class, 'update'])->name('update.users');
        Route::any('destroy/{id}', [UsersCtrl::class, 'destroy'])->name('destroy.users');
        Route::any('recovery/{id}', [UsersCtrl::class, 'recovery'])->name('recovery.users');
        // Roles
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', [UsersRolesCtrl::class, 'index'])->name('index.users.roles');
            Route::get('create/', [UsersRolesCtrl::class, 'create'])->name('create.users.roles');
            Route::post('store/', [UsersRolesCtrl::class, 'store'])->name('store.users.roles');
            Route::get('edit/{id}', [UsersRolesCtrl::class, 'edit'])->name('edit.users.roles');
            Route::post('update/{id}', [UsersRolesCtrl::class, 'update'])->name('update.users.roles');
            Route::any('destroy/{id}', [UsersRolesCtrl::class, 'destroy'])->name('destroy.users.roles');
        });
        // Token
        Route::group(['prefix' => 'tokens'], function () {
            Route::get('/', [UsersTokensCtrl::class, 'index'])->name('index.users.tokens');
            Route::get('create/', [UsersTokensCtrl::class, 'create'])->name('create.users.tokens');
            Route::post('store/', [UsersTokensCtrl::class, 'store'])->name('store.users.tokens');
            Route::any('destroy/{id}', [UsersTokensCtrl::class, 'destroy'])->name('destroy.users.tokens');
        });
    });

})->middleware('auth');

