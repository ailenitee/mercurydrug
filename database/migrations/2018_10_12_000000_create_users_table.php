<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id')->unsigned();
            $table->unsignedInteger('client_id')->unsigned();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile');
            $table->string('password');
            $table->string('status');
            $table->rememberToken();
            $table->timestamps();
            $table->engine = "InnoDB";
        });
        Schema::table('users', function(Blueprint $table)
        {
          $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('users');
    }
}
