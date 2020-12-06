<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductserialnumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productserialnumbers', function (Blueprint $table) {
            $table->integer('import_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('serial_number');
            $table->text('note');
            $table->timestamps();

            $table->primary(array('import_id', 'product_id', 'serial_number'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productserialnumbers');
    }
}
