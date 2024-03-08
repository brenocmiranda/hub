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
        Schema::create('buildings_has_integrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_id');
            $table->foreign('building_id')->references('id')->on('buildings');
            $table->unsignedBigInteger('integration_id');
            $table->foreign('integration_id')->references('id')->on('integrations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings_has_integrations');
    }
};
