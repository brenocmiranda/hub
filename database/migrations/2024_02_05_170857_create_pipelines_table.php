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
        Schema::create('pipelines', function (Blueprint $table) {
            $table->id();
            $table->integer('statusCode');
            $table->integer('attempts');
            $table->unsignedBigInteger('lead_id');
            $table->foreign('lead_id')->references('id')->on('leads');
            $table->unsignedBigInteger('buildings_has_integrations_building_id');
            $table->foreign('buildings_has_integrations_building_id', 'fk_buildings_has_integrations_building_id_pipelines')->references('building_id')->on('buildings_has_integrations');
            $table->unsignedBigInteger('buildings_has_integrations_integration_id')->nullable();
            $table->foreign('buildings_has_integrations_integration_id', 'fk1_buildings_has_integrations_integration_id_pipelines')->references('integration_id')->on('buildings_has_integrations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pipelines');
    }
};
