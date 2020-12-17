<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnImportedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_imported_products', function (Blueprint $table) {
            $table->increments('return_id')->unsigned();
            $table->integer('import_id')->unsigned();
            $table->integer('product_id')->unsigned();
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
        Schema::dropIfExists('return_imported_products');
    }
}
