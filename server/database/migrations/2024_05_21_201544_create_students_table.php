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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('organ')->constrained('organs');
            $table->string('name');
            $table->date('birth');
            $table->string('cpf', 20)->nullable();
            $table->string('nis', 20)->nullable();
            $table->string('sige', 20);
            $table->string('censo', 20)->nullable();
            $table->integer('race')->nullable();
            $table->integer('sex')->nullable();
            $table->string('father')->nullable();
            $table->string('mother')->nullable();
            $table->string('street')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('cep', 15)->nullable();
            $table->string('phone', 24)->nullable();
            $table->string('email')->nullable();
            $table->integer('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
