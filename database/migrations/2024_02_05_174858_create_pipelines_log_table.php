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
        Schema::create('pipelines_log', function (Blueprint $table) {
            $table->id();
            $table->longText('header');
            $table->longText('response');
            $table->unsignedBigInteger('pipeline_id');
            $table->foreign('pipeline_id')->references('id')->on('pipelines');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pipelines_log');
    }
};
