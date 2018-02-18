<?php

use Illuminate\Database\Seeder;

class AbstractSeeder extends Seeder
{
    /**
     * Model used for import.
     *
     * @var null
     */
    protected $model = null;

    /**
     * Items to be seeded.
     *
     * @var array
     */
    protected $itemList = [];

    /**
     * Tables to be cleared before seeding.
     *
     * @var array
     */
    protected $truncateTables = [];

    /**
     * Clear existing data in $truncateTables array.
     */
    protected function cleanDatabase()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($this->truncateTables as $table) {
            \DB::table($table)->truncate();
        }

        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Run the seeder.
     *
     * @throws \Exception
     */
    public function run()
    {
        \DB::beginTransaction();

        $this->cleanDatabase();

        foreach ($this->itemList as $item) {
            $this->model->create($item);
        }

        $this->complete();

        \DB::commit();
    }

    /**
     * Additional tasks to be completed after seeding has completed.
     */
    protected function complete()
    {
        //
    }
}
