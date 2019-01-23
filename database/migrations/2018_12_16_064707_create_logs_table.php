<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('logs', function (Blueprint $table) {
      $table->unsignedInteger('cart_id')->unsigned();
      $table->unsignedInteger('client_id')->unsigned();
      $table->increments('id');
      $table->string('image');
      $table->string('status');
      $table->string('code')->nullable();
      $table->string('verified_date')->nullable();
      $table->timestamps();
    });
    Schema::table('logs', function(Blueprint $table)
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
    Schema::dropIfExists('logs');
  }
}
