<?php namespace App\Http\Middleware;

use App\Exceptions\PermissionDeniedException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class VerifyPermission
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
     * @param string   $permission
     * @return mixed
     * @throws PermissionDeniedException
     */
    public function handle(Request $request, \Closure $next, $permission)
    {
        if (!$this->auth->check() || !$this->auth->user()->hasPermission($permission)) {
            throw new PermissionDeniedException;
        }

        return $next($request);
    }
}
