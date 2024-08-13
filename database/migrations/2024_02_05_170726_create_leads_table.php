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
            $table->uuid('id')->primary();
            $table->boolean('api');
            $table->string('name', 200);
            $table->string('phone', 200);
            $table->string('email', 200);
            $table->string('batches_id')->nullable();
            $table->foreignUuid('companies_id')->constrained();
            $table->foreignUuid('leads_origins_id')->constrained();
            $table->foreignUuid('buildings_id')->constrained();
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
