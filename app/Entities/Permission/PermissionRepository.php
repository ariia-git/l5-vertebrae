<?php namespace App\Entities\Permission;

use App\Entities\AbstractRepository;

class PermissionRepository extends AbstractRepository
{
    protected $filters = [
        //
    ];

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }
}
