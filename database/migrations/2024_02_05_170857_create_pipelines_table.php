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
            $table->unsignedBigInteger('leads_id');
            $table->foreign('leads_id')->references('id')->on('leads');
            $table->unsignedBigInteger('buildings_id');
            $table->foreign('buildings_id')->references('id')->on('buildings');
            $table->unsignedBigInteger('integrations_id')->nullable();
            $table->foreign('integrations_id')->references('id')->on('integrations');
            $table->softDeletes($column = 'deleted_at', $precision = 0);
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
