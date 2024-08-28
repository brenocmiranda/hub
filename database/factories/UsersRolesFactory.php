<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UsersRoles;
use App\Models\Companies;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UsersRolesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $companies = Companies::where('slug', 'like', '%kgroup%')->first();

        return [
            'active' => 1,
            'name' => 'Administrador',
            'roles' => 'dashboards_show,leads_show,leads_create,leads_destroy,leads_retry,leads_resend,origins_show,origins_create,origins_update,origins_destroy,pipelines_show,pipelines_resetAll,companies_show,companies_create,companies_update,companies_destroy,buildings_show,buildings_create,buildings_update,buildings_destroy,buildings_duplicate,keys_show,keys_create,keys_update,keys_destroy,integrations_show,integrations_create,integrations_update,integrations_destroy,reports_show,reports_create,reports_destroy,imports_show,imports_create,imports_destroy,users_show,users_create,users_update,users_destroy,users_recovery,roles_show,roles_create,roles_update,roles_destroy,tokens_show,tokens_create,tokens_destroy',
            'companies_id' => $companies->id,
        ];
    }
}
