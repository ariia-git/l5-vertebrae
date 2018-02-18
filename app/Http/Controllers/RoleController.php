<?php namespace App\Http\Controllers;

use App\Services\Entities\Role\RoleService;

class RoleController extends AbstractController
{
    public function __construct(RoleService $service)
    {
        parent::__construct();

        $this->middleware('permission:roles');

        $this->service = $service;
    }
}
