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
        Schema::create('leads_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('value');
            $table->unsignedBigInteger('leads_id');
            $table->foreign('leads_id')->references('id')->on('leads');
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads_fields');
    }
};
