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
        Schema::create('benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facilities_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('account');
            $table->integer('last_balance_a');
            $table->integer('last_balance_d');
            $table->integer('last_year_a');
            $table->integer('last_year_d');
            $table->integer('two_years_a');
            $table->integer('two_years_d');
            $table->integer('three_years_a');
            $table->integer('three_years_d');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benefits');
    }
};
