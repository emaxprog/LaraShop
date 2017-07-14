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
            $table->string('name', 128)->comment('Название страны');

            $table->index('name');
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('country_id')->unsigned()->comment('Страна');
            $table->string('name', 128)->comment('Название региона');

            $table->foreign('country_id')->references('id')->on('countries')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->index('name');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('region_id')->unsigned()->comment('Регион');
            $table->string('name', 128)->comment('Название города');

            $table->foreign('region_id')->references('id')->on('regions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->index('name');
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('city_id')->unsigned()->comment('Город');
            $table->string('street', 128)->comment('Улица');
            $table->string('building', 32)->comment('Номер дома');
            $table->string('apartment', 32)->nullable()->comment('Номер квартиры');
            $table->string('postcode',16)->comment('Почтовый индекс');

            $table->foreign('city_id')->references('id')->on('cities')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('phones', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('country_code',1)->comment('Код страны');
            $table->string('operator_code',3)->comment('Код оператора');
            $table->string('number',7)->comment('Номер телефона');

            $table->unique(['country_code', 'operator_code', 'number']);
        });

        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 32)->comment('Имя');
            $table->string('surname', 64)->comment('Фамилия');
            $table->string('email', 32)->unique()->comment('Email');
            $table->string('password', 64)->comment('Пароль');
            $table->integer('address_id')->unsigned()->comment('Адрес');
            $table->integer('phone_id')->unsigned()->comment('Телефон');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('address_id')->references('id')->on('addresses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('phone_id')->references('id')->on('phones')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->index(['name', 'surname']);
            $table->index('email');
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
        Schema::dropIfExists('phones');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('countries');
    }
}
