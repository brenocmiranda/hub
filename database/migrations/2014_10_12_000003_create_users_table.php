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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->boolean('active');
            $table->string('name', 200);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('attempts');
            $table->string('src')->nullable();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->unsignedBigInteger('user_role_id');
            $table->foreign('user_role_id')->references('id')->on('users_roles');
            $table->unsignedBigInteger('companie_id');
            $table->foreign('companie_id')->references('id')->on('companies');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
