<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BuildingsPartners>
 */
class BuildingsPartnersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'main' => 1,
            'leads' => 99,
            'companies_id' => 1,
            'buildings_id' => 1,
        ];
    }
}
