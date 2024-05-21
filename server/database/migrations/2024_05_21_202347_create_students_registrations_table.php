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
        Schema::create('students_registrations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('organ_id')->constrained('organs');
            $table->foreignId('school_id')->constrained('schools');
            $table->foreignId('serie_id')->constrained('series');
            $table->foreignId('classe_id')->constrained('classes');
            $table->foreignId('student_id')->constrained('students');
            $table->string('year', 5);
            $table->integer('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students_registrations');
    }
};
