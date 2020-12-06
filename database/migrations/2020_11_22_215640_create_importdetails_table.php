<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importdetails', function (Blueprint $table) {
            $table->integer('import_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->double('import_price');
            $table->integer('import_quantity');
            $table->timestamps();

            $table->primary(array('import_id', 'product_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('importdetails');
    }
}
