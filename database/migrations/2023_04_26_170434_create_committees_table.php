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
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_accepted')->default(false);
            $table->string('path1');
            $table->string('file_name1');
            $table->string('path2');
            $table->string('file_name2');
            $table->string('path3');
            $table->string('file_name3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
