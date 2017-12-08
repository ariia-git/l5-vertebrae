<?php

class DatabaseSeeder extends AbstractSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CurrenciesTableSeeder::class);
        $this->call(CountriesTableSeeder::class); // must be seeded after currencies due to currency foreign key
        $this->call(LanguagesTableSeeder::class);
        $this->call(LocalesTableSeeder::class); // must be seeded after countries and languages due to country and language foreign keys
    }
}
