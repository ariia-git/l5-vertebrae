<?php namespace App\Http\Middleware;

use App\Exceptions\RoleDeniedException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class VerifyRole
{
    /**
     * @var Guard
     */
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Request  $request
     * @param \Closure $next
     * @param string   $role
     * @return mixed
     * @throws RoleDeniedException
     */
    public function handle(Request $request, \Closure $next, $role)
    {
        if (!$this->auth->check() || !$this->auth->user()->hasRole($role)) {
            throw new RoleDeniedException;
        }

        return $next($request);
    }
}
