<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Users;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {   
        $access = [ 'dashboards_show', 'leads_show', 'leads_create', 'leads_update', 'leads_destroy', 'leads_retry', 'leads_resend', 'origins_show', 'origins_create', 'origins_update', 'origins_destroy', 'pipelines_show', 'pipelines_resetAll', 'companies_show', 'companies_create', 'companies_update', 'companies_destroy', 'buildings_show', 'buildings_create', 'buildings_update', 'buildings_destroy', 'buildings_duplicate', 'keys_show', 'keys_create', 'keys_update', 'keys_destroy', 'integrations_show', 'integrations_create', 'integrations_update', 'integrations_destroy', 'reports_show', 'reports_create', 'reports_destroy', 'imports_show', 'imports_create', 'imports_destroy', 'users_show', 'users_create', 'users_update', 'users_destroy', 'users_recovery', 'roles_show', 'roles_create', 'roles_update', 'roles_destroy', 'tokens_show', 'tokens_create', 'tokens_destroy' ];

        // Acessos por função
        foreach($access as $ability) {
            Gate::define( $ability, function (Users $user) use ($ability){
                return strpos( $user->RelationRoles->roles, $ability ) !== false;
            });
        }

        // Komuh
        Gate::define( 'access_komuh', function (Users $user) {
            return $user->RelationCompanies->slug === 'komuh';
        });
    } 
}