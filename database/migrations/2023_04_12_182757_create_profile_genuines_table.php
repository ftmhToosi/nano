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
        Schema::create('profile_genuines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('image')->nullable();
            $table->string('father_name');
            $table->string('number_certificate');
            $table->date('birth_day');
            $table->string('place_issue');
            $table->string('series_certificate');
            $table->string('nationality');
            $table->enum('gender', ['male', 'female']);
            $table->enum('marital',['married', 'single']);
            $table->enum('residential', ['resident', 'non_resident']);
            $table->string('study');
            $table->string('education');
            $table->string('job');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_genuines');
    }
};
