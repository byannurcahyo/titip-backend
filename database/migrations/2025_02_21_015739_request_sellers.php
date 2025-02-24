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
        Schema::create('request_sellers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');
            $table->timestamp('reviewed_at')
                ->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_sellers');
    }
};
