<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCurrencyInfoToLocalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table(CreateLocalesTable::TABLENAME, function (Blueprint $table) {
            $table->string('currency_symbol_first')->default(true)->after('code');
            $table->string('decimal_mark')->default('.')->after('currency_symbol_first');
            $table->string('thousands_separator')->default(',')->after('decimal_mark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table(CreateLocalesTable::TABLENAME, function (Blueprint $table) {
            $table->dropColumn('thousands_separator');
            $table->dropColumn('decimal_mark');
            $table->dropColumn('currency_symbol_first');
        });
    }
}
