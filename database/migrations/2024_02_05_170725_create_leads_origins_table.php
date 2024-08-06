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
        Schema::create('leads_origins', function (Blueprint $table) {
            $table->id();
            $table->boolean('active');
            $table->string('name', 200);
            $table->string('slug');
            $table->unsignedBigInteger('companies_id');
            $table->foreign('companies_id')->references('id')->on('companies');
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads_origins');
    }
};
