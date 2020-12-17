<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnSoldProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_sold_products', function (Blueprint $table) {
            $table->increments('return_id')->unsigned();
            $table->integer('invoice_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->dateTime('return_date');
            $table->integer('return_qty');
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
        Schema::dropIfExists('return_sold_products');
    }
}
