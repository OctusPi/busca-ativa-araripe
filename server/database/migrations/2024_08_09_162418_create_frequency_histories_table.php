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
        Schema::create('frequency_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('date_send');
            $table->foreignId('teacher')->constrained('teachers');
            $table->foreignId('grid')->constrained('grids');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frequency_histories');
    }
};
