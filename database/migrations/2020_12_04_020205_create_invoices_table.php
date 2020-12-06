<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('invoice_number')->unique();
            $table->dateTime('invoice_date');
            $table->string('customer_name');
            $table->string('customer_contact');
            $table->text('note');
            $table->double('discount');
            $table->double('subtotal');
            $table->integer('exchange_rate_in');
            $table->integer('exchange_rate_out');
            $table->string('payment_method');
            $table->double('money_received_in_dollar');
            $table->double('money_received_in_riel');
//            $table->integer('user_id');
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('invoices');
    }
}
