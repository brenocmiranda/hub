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
        Schema::create('buildings_partners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('main');
            $table->integer('leads');
            $table->foreignUuid('companies_id')->constrained();
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
        Schema::dropIfExists('buildings_partners');
    }
};
