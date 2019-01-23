<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateDenominationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_denominations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brand_id')->unsigned();//fk
            $table->unsignedInteger('denomination_id')->unsigned();//fk
            $table->unsignedInteger('client_id')->unsigned();//fk
            $table->timestamps();
        });
        Schema::table('template_denominations', function(Blueprint $table)
        {
          $table->foreign('brand_id')->references('id')->on('brands')->onDelete('restrict')->onUpdate('restrict');
          $table->foreign('denomination_id')->references('id')->on('denominations')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('template_denominations');
    }
}
