<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCountriesTable extends Migration
{
    const TABLENAME = 'countries';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create(self::TABLENAME, function (Blueprint $table) {
            $table->increments('id');
            $table->string('iso_code', 2)->index(); // ISO 3166-1 alpha-2; 2 char
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });

        $countriesTableSeeder = app(CountriesTableSeeder::class);
        $countriesTableSeeder->run();
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
