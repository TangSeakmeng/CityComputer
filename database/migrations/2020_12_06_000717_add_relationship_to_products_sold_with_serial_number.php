<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToProductsSoldWithSerialNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productsSoldWithSerialNumber', function (Blueprint $table) {
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices');

            $table->foreign('product_id')
                ->references('id')
                ->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productsSoldWithSerialNumber', function (Blueprint $table) {
            //
        });
    }
}
