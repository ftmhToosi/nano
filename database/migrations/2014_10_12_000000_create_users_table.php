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
            $table->string('name');
            $table->string('family');
            $table->string('national_code')->unique();
            $table->string('phone');
            $table->string('company_name');
            $table->string('national_company')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('password_confirmation');
            $table->enum('type', ['genuine', 'legal', 'expert', 'admin'])->default('genuine');
            $table->boolean('is_confirmed')->default(false);
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
