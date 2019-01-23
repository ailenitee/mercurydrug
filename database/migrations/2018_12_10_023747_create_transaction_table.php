<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cart_id')->unsigned();
            $table->unsignedInteger('client_id')->unsigned();
            $table->string('status');
            $table->string('reference_num');
            $table->string('error_detail')->nullable();
            $table->timestamps();
            $table->engine = "InnoDB";
        });
        Schema::table('transactions', function(Blueprint $table)
        {
          $table->foreign('cart_id')->references('id')->on('carts')->onDelete('restrict')->onUpdate('restrict');
          $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
