<?php namespace App\Http\Controllers;

use App\Services\Entities\User\UserService;

class UserController extends AbstractController
{
    protected $with = [
        //
    ];

    public function __construct(UserService $service)
    {
        parent::__construct();

        $this->service = $service;
    }
}
