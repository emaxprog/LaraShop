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
        Schema::create('countries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 100)->comment('Название страны');
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('country_id')->unsigned()->comment('Страна');
            $table->string('name', 100)->comment('Название региона');

            $table->foreign('country_id')->references('id')->on('countries')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('region_id')->unsigned()->comment('Регион');
            $table->string('name', 100)->comment('Название города');

            $table->foreign('region_id')->references('id')->on('regions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 30)->comment('Имя пользователя');
            $table->string('email', 30)->unique()->comment('Email пользователя');
            $table->string('password', 60)->comment('Пароль пользователя');
            $table->integer('city_id')->unsigned();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('cities');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('countries');
    }
}
