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
            $table->string('batches_id')->nullable();
            $table->unsignedBigInteger('companies_id');
            $table->foreign('companies_id')->references('id')->on('companies');
            $table->unsignedBigInteger('leads_origins_id');
            $table->foreign('leads_origins_id')->references('id')->on('leads_origins');
            $table->unsignedBigInteger('buildings_id');
            $table->foreign('buildings_id')->references('id')->on('buildings');
            $table->softDeletes($column = 'deleted_at', $precision = 0);
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
