<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_categories', function (Blueprint $table) {
          $table->increments('id');
          $table->unsignedInteger('brand_id')->unsigned();//fk
          $table->unsignedInteger('category_id')->unsigned();//fk
          $table->unsignedInteger('client_id')->unsigned();//fk
          $table->timestamps();
        });
        Schema::table('template_categories', function(Blueprint $table)
        {
          $table->foreign('brand_id')->references('id')->on('brands')->onDelete('restrict')->onUpdate('restrict');
          $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('template_categories');
    }
}
