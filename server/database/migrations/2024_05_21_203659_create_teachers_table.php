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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('organ')->constrained('organs');
            $table->string('name');
            $table->string('registration')->nullable();
            $table->string('cpf');
            $table->string('phone', 24)->nullable();
            $table->string('email')->nullable();
            $table->string('qualification')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
