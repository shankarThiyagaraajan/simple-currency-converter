<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->length(10)->unsigned();
            $table->integer('from')->length(10)->unsigned();
            $table->integer('to')->length(10)->unsigned();
            $table->double('rate', 10, 2);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });

        Schema::table('currency_rates', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('from')->references('id')->on('currencies');
            $table->foreign('to')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_rates');
    }
}
