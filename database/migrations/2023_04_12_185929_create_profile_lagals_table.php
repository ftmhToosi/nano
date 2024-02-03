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
        Schema::create('profile_lagals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('type_legal');
            $table->string('place_registration');
            $table->date('establishment');
            $table->string('signed_right');
            $table->string('initial_investment');
            $table->string('fund');
            $table->string('subject_activity');
            $table->string('name_representative');
            $table->string('landline_phone');
            $table->string('phone');
            $table->string('email');
            $table->string('site')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_lagals');
    }
};
