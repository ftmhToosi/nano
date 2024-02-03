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
        Schema::create('active_w_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facilities_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('amount');
            $table->string('subject');
            $table->string('institution');
            $table->string('type_w');
            $table->string('type_collateral');
            $table->string('deposit_amount');
            $table->date('received');
            $table->date('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_w_s');
    }
};
