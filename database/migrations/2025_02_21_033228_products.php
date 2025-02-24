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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('seller_id');
            $table->string('name', 100);
            $table->text('description');
            $table->double('price', 15, 2);
            $table->tinyInteger('stock');
            $table->string('photo', 100)
                ->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('seller_id');
            $table->index('name');
            $table->index('price');
            $table->index('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
