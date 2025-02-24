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
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->uuid('seller_id');
            $table->uuid('product_id');
            $table->string('product_name', 100);
            $table->double('price', 15, 2);
            $table->text('description');
            $table->integer('quantity');
            $table->double('subTotal', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->index('order_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
