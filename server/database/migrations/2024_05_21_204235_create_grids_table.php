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
        Schema::create('grids', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('organ')->constrained('organs');
            $table->foreignId('school')->constrained('schools');
            $table->foreignId('serie')->constrained('series');
            $table->foreignId('classe')->constrained('classes');
            $table->foreignId('subject')->constrained('subjects');
            $table->foreignId('teacher')->constrained('teachers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grids');
    }
};
