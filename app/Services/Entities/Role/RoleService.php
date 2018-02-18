<?php namespace App\Services\Entities\Role;

use App\Entities\Role\RoleRepository;
use App\Services\Entities\AbstractService;

class RoleService extends AbstractService
{
    public function __construct(RoleRepository $repo)
    {
        $this->repo = $repo;
    }
}
