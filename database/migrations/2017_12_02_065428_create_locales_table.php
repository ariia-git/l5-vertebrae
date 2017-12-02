<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocalesTable extends Migration
{
    const TABLENAME = 'locales';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create(self::TABLENAME, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('country_id');
            $table->foreign('country_id')->references('id')->on(CreateCountriesTable::TABLENAME);
            $table->unsignedInteger('language_id');
            $table->foreign('language_id')->references('id')->on(CreateLanguagesTable::TABLENAME);
            $table->string('code', 15)->index();
            $table->boolean('active')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        $localesTableSeeder = app(LocalesTableSeeder::class);
        $localesTableSeeder->run();
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
