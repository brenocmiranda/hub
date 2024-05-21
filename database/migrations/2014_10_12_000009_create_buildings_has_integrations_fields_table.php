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
            $table->unsignedBigInteger('buildings_has_integrations_buildings_id');
            $table->foreign('buildings_has_integrations_buildings_id', 'fk_buildings_has_integrations_buildings_id_fields')->references('buildings_id')->on('buildings_has_integrations');
            $table->unsignedBigInteger('buildings_has_integrations_integrations_id');
            $table->foreign('buildings_has_integrations_integrations_id', 'fk_buildings_has_integrations_integrations_id_fields')->references('integrations_id')->on('buildings_has_integrations');
            $table->softDeletes($column = 'deleted_at', $precision = 0);
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
