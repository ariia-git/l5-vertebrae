<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCurrenciesTable extends Migration
{
    const TABLENAME = 'currencies';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create(self::TABLENAME, function (Blueprint $table) {
            $table->increments('id');
            $table->string('iso_code', 3)->index(); // ISO 4217; 3 char
            $table->string('name');
            $table->string('symbol')->default('$');
            $table->integer('decimal_precision')->default(2);
            $table->decimal('exchange_rate', 8, 6)->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists(self::TABLENAME);
    }
}
