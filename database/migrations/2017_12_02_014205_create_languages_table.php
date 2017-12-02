<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLanguagesTable extends Migration
{
    const TABLENAME = 'languages';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create(self::TABLENAME, function (Blueprint $table) {
            $table->increments('id');
            $table->string('iso_code', 2)->index(); // ISO 639-1; 2 char
            $table->string('name');
            $table->string('script', 4)->default('Latn');
            $table->softDeletes();
            $table->timestamps();
        });

        $languagesTableSeeder = app(LanguagesTableSeeder::class);
        $languagesTableSeeder->run();
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
