<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSoldWithSerialNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productsSoldWithSerialNumber', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('invoice_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('serial_number')->unique();
            $table->float('warranty_period');
            $table->dateTime('expired_date');
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
        Schema::dropIfExists('productSoldWithSerialNumber');
    }
}
