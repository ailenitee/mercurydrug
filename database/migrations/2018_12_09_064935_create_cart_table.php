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
    Schema::create('carts', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('brand_id')->unsigned();
      $table->unsignedInteger('template_category_id')->unsigned();//fk
      $table->unsignedInteger('template_denomination_id')->unsigned();//fk
      $table->string('transaction_id')->nullable();
      $table->string('user_id');
      $table->string('user_type');
      $table->string('sender');
      $table->string('name');
      $table->string('quantity');
      $table->string('address')->nullable();
      $table->string('message')->nullable();
      $table->string('mobile')->nullable();
      $table->string('fulfillment_type')->nullable();
      $table->string('pickup_date')->nullable();
      $table->string('total');
      $table->timestamps();
      $table->engine = "InnoDB";
    });
    Schema::table('carts', function(Blueprint $table)
    {
      $table->foreign('brand_id')->references('id')->on('brands')->onDelete('restrict')->onUpdate('restrict');
      $table->foreign('template_category_id')->references('id')->on('template_categories')->onDelete('restrict')->onUpdate('restrict');
      $table->foreign('template_denomination_id')->references('id')->on('template_denominations')->onDelete('restrict')->onUpdate('restrict');
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
    Schema::dropIfExists('carts');
    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
  }
}
