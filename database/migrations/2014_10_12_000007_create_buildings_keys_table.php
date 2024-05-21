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
        Schema::create('buildings_keys', function (Blueprint $table) {
            $table->id();
            $table->boolean('active');
            $table->string('value');
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
        Schema::dropIfExists('buildings_keys');
    }
};
