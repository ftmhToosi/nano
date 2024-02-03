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
        Schema::create('active_f_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facilities_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('year');
            $table->string('name');
            $table->string('amount_f');
            $table->string('type_f');
            $table->string('rate');
            $table->string('type_collateral');
            $table->integer('n_refunds');
            $table->integer('n_remaining');
            $table->string('amount_installment');
            $table->string('remaining_f');
            $table->date('settlement_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_f_s');
    }
};
