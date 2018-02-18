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
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class); // must be seeded after roles so we can attach all permissions to the Administrator role
    }
}
