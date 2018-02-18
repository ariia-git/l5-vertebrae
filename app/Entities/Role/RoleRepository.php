<?php namespace App\Entities\Role;

use App\Entities\AbstractRepository;

class RoleRepository extends AbstractRepository
{
    protected $filters = [
        //
    ];

    public function __construct(Role $model)
    {
        $this->model = $model;
    }
}
