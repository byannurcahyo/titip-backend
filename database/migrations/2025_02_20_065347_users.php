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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->string('email', 50);
            $table->string('password', 255);
            $table->enum('role', ['admin', 'seller','user'])
                ->default('user');
            $table->string('phone_number', 25)
                ->default(null)
                ->nullable();
            $table->string('photo', 100)
                ->nullable();
            $table->string('google_id')
                ->nullable();
            $table->string('updated_security')
                ->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('email');
            $table->index('role');
            $table->index('updated_security');
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
