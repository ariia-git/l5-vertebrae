<?php

use App\Entities\User\User;

class UsersTableSeeder extends AbstractSeeder
{
    protected $truncateTables = [
        CreateUsersTable::TABLENAME
    ];

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        \DB::beginTransaction();

        $this->cleanDatabase();

        $this->model->create([
            'username' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
            'remember_token' => str_random(10)
        ]);
        factory(User::class, 10)->create();

        \DB::commit();
    }
}
