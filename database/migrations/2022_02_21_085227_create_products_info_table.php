<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_info', function (Blueprint $table) {
            $table->foreignId('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->decimal('rating', 10, 2, true)->default(0); //рэйтинг
            $table->decimal('order_count', 10, 2, true)->default(0); //количество заказ
            $table->json('name_attributes');
            $table->json('attribute_info');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_info');
    }
}
