<?php namespace App\Services\Entities\Permission;

use App\Entities\Permission\PermissionRepository;
use App\Services\Entities\AbstractService;

class PermissionService extends AbstractService
{
    public function __construct(PermissionRepository $repo)
    {
        $this->repo = $repo;
    }
}
