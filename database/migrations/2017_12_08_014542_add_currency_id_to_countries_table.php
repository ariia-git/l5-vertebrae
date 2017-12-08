<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCurrencyIdToCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table(CreateCountriesTable::TABLENAME, function (Blueprint $table) {
            $table->unsignedInteger('currency_id')->nullable()->after('id');
            $table->foreign('currency_id')->references('id')->on(CreateCurrenciesTable::TABLENAME);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table(CreateCountriesTable::TABLENAME, function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn('currency_id');
        });
    }
}
