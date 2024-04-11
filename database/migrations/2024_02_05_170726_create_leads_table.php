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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->boolean('api');
            $table->string('name', 200);
            $table->string('phone', 200);
            $table->string('email', 200);
            $table->unsignedBigInteger('batches_id')->nullable();
            $table->foreign('batches_id')->references('id')->on('job_batches');
            $table->unsignedBigInteger('leads_origin_id');
            $table->foreign('leads_origin_id')->references('id')->on('leads_origins');
            $table->unsignedBigInteger('building_id');
            $table->foreign('building_id')->references('id')->on('buildings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
