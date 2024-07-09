<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('email', 220)->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->json('organs')->nullable();
            $table->json('schools')->nullable();
            $table->integer('profile');
            $table->json('modules')->nullable();
            $table->boolean('passchange')->default(false);
            $table->integer('status');
            $table->string('recover_token')->nullable();
            $table->rememberToken();
            $table->dateTime('lastlogin')->nullable();
            $table->dateTime('nowlogin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
