<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('cart', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('brand_id')->unsigned();
      $table->unsignedInteger('theme_id')->unsigned();
      $table->string('sender');
      $table->string('name');
      $table->string('quantity');
      $table->string('address');
      $table->string('mobile');
      $table->string('total');
      $table->string('email')->nullable();
      $table->timestamps();
      $table->engine = "InnoDB";
    });
    Schema::table('cart', function(Blueprint $table)
    { 
      $table->foreign('brand_id')->references('id')->on('brand')->onDelete('restrict')->onUpdate('restrict');
      $table->foreign('theme_id')->references('id')->on('themes')->onDelete('restrict')->onUpdate('restrict');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    Schema::dropIfExists('cart');
    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
  }
}
