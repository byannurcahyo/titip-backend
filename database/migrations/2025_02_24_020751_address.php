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
        Schema::create('address', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->text('address');
            $table->string('city', 50);
            $table->string('province', 50);
            $table->string('postal_code', 10);
            $table->string('phone', 15);
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('city');
            $table->index('province');
            $table->index('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address');
    }
};
