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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_genuine_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('address');
            $table->string('postal_code');
            $table->string('phone');
            $table->string('namabar')->nullable();
            $table->string('work_address');
            $table->string('work_postal_code');
            $table->string('work_phone');
            $table->string('work_namabar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
