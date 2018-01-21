<?php namespace App\Entities\User;

use App\Entities\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}
