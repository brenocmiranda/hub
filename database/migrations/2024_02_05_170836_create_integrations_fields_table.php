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
        Schema::create('integrations_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('value');
            $table->string('type');
            $table->boolean('required');
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
        Schema::dropIfExists('integrations_fields');
    }
};
