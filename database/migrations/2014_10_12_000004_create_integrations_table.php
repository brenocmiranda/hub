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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->boolean('active');
            $table->string('type', 200);
            $table->string('name', 200);
            $table->string('slug', 200);
            $table->longText('url', 200);
            $table->boolean('encoded');
            $table->string('user', 200)->nullable();
            $table->string('password', 200)->nullable();
            $table->string('token', 200)->nullable();
            $table->longText('header', 200)->nullable();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
