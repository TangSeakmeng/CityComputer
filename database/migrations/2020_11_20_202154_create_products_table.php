<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('barcode')->unique();
            $table->string('name');
            $table->integer('category_id')->unsigned();
            $table->integer('brand_id')->unsigned();
            $table->double('cost_of_sale');
            $table->integer('unit_in_stock');
            $table->double('price');
            $table->double('discount_price');
            $table->text('description');
            $table->integer('sale_status_id')->unsigned();
            $table->boolean('published');
            $table->string('image_path');
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
        Schema::dropIfExists('products');
    }
}
