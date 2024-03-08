<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buildings_has_integrations_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('value');
            $table->unsignedBigInteger('buildings_has_integrations_building_id');
            $table->foreign('buildings_has_integrations_building_id', 'fk_buildings_has_integrations_building_id_fields')->references('building_id')->on('buildings_has_integrations');
            $table->unsignedBigInteger('buildings_has_integrations_integration_id');
            $table->foreign('buildings_has_integrations_integration_id', 'fk_buildings_has_integrations_integration_id_fields')->references('integration_id')->on('buildings_has_integrations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings_has_integrations_fields');
    }
};
