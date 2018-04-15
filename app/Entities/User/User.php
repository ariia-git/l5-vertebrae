<?php namespace App\Entities\User;

use App\Entities\AbstractEntity;
use App\Entities\Permission\Permission;
use App\Entities\Permission\PermissionQueryBuilder;
use App\Entities\Role\Role;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends AbstractEntity implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable,
        Authorizable,
        CanResetPassword,
        Notifiable,
        SoftDeletes;

    protected $fillable = [
        'username',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if the user has a permission.
     *
     * @param int|string $permission
     * @return bool
     */
    private function checkPermission($permission)
    {
        return $this->getPermissions()->contains(function ($value) use ($permission) {
            return $permission === $value->id || Str::is($permission, $value->key);
        });
    }

    /**
     * Check if the user has a role.
     *
     * @param int|string $role
     * @return bool
     */
    private function checkRole($role)
    {
        return $this->getRoles()->contains(function ($value) use ($role) {
            return $role === $value->id || Str::is($role, $value->key);
        });
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getAttribute('email');
    }

    /**
     * Get all permissions as collection.
     *
     * @return Permission[]|Collection
     */
    public function getPermissions()
    {
        $userPermissions = $this->getAttribute('permissions');

        return $this->getRolePermissions()->merge($userPermissions);
    }

    /**
     * Get all roles as collection.
     *
     * @return Role[]|Collection
     */
    public function getRoles()
    {
        return $this->getAttribute('roles');
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->getAttribute('username');
    }

    /**
     * Check if the user has permissions.
     *
     * @param array|int|string $permissions
     * @return bool
     */
    public function hasPermission($permissions)
    {
        if (is_array($permissions)) {
            // make sure user has all roles in array
            foreach ($permissions as $permission) {
                if (!$this->checkPermission($permission)) {
                    return false;
                }
            }

            return true;
        }

        return $this->checkPermission($permissions);
    }

    /**
     * Check if the user has roles.
     *
     * @param array|int|string $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        if (is_array($roles)) {
            // make sure user has all roles in array
            foreach ($roles as $role) {
                if (!$this->checkRole($role)) {
                    return false;
                }
            }

            return true;
        }

        return $this->checkRole($roles);
    }

    /**
     * Get all permissions from roles.
     *
     * @return Permission[]|Collection
     */
    private function getRolePermissions()
    {
        $permissionQueryBuilder = app(PermissionQueryBuilder::class);

        /** @var Builder $permissionQuery */
        $permissionQuery = $permissionQueryBuilder->createNewQuery();
        $permissionQueryBuilder->selectBase($permissionQuery);
        $permissionQueryBuilder->selectPermissionRoleTimestamps($permissionQuery);
        $permissionQueryBuilder->joinPermissionRoleTable($permissionQuery);
        $permissionQueryBuilder->joinRolesTable($permissionQuery);
        $permissionQueryBuilder->whereRoleIds($permissionQuery, $this->getRoles()->pluck('id')->toArray());
        $permissionQueryBuilder->groupByPermissionId($permissionQuery);
        $permissionQueryBuilder->groupByPermissionName($permissionQuery);
        $permissionQueryBuilder->groupByPermissionKey($permissionQuery);
        $permissionQueryBuilder->groupByPermissionDescription($permissionQuery);
        $permissionQueryBuilder->groupByPermissionCreatedAt($permissionQuery);
        $permissionQueryBuilder->groupByPermissionUpdatedAt($permissionQuery);
        $permissionQueryBuilder->groupByPermissionRoleCreatedAt($permissionQuery);
        $permissionQueryBuilder->groupByPermissionRoleUpdatedAt($permissionQuery);
        $permissionQueryBuilder->groupByPermissionDeletedAt($permissionQuery);

        return $permissionQuery->get();
    }

    /**
     * Handle dynamic method calls.
     *
     * @param string $method
     * @param array  $parameters
     * @return bool|mixed
     */
    public function __call($method, $parameters)
    {
        if (starts_with($method, 'is')) {
            return $this->hasRole(snake_case(substr($method, 2), '.'));
        } elseif (starts_with($method, 'can')) {
            return $this->hasPermission(snake_case(substr($method, 3), '.'));
        }

        return parent::__call($method, $parameters);
    }
}
