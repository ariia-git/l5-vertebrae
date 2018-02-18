<?php namespace App\Http\Controllers;

use App\Services\Entities\User\UserService;

class UserController extends AbstractController
{
    public function __construct(UserService $service)
    {
        parent::__construct();

        $this->middleware('permission:users');

        $this->service = $service;
    }
}
