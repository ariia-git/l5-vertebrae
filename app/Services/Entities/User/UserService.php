<?php namespace App\Services\Entities\User;

use App\Entities\User\UserRepository;
use App\Services\Entities\AbstractService;

class UserService extends AbstractService
{
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }
}
