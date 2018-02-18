<?php namespace App\Entities\Permission;

use App\Entities\AbstractQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class PermissionQueryBuilder extends AbstractQueryBuilder
{
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    /**
     * @param Builder $query
     */
    public function groupByPermissionCreatedAt(Builder $query)
    {
        $query->groupBy('permissions.created_at');
    }

    /**
     * @param Builder $query
     */
    public function groupByPermissionDeletedAt(Builder $query)
    {
        $query->groupBy('permissions.deleted_at');
    }

    /**
     * @param Builder $query
     */
    public function groupByPermissionDescription(Builder $query)
    {
        $query->groupBy('permissions.description');
    }

    /**
     * @param Builder $query
     */
    public function groupByPermissionId(Builder $query)
    {
        $query->groupBy('permissions.id');
    }

    /**
     * @param Builder $query
     */
    public function groupByPermissionKey(Builder $query)
    {
        $query->groupBy('permissions.key');
    }

    /**
     * @param Builder $query
     */
    public function groupByPermissionName(Builder $query)
    {
        $query->groupBy('permissions.name');
    }

    /**
     * @param Builder $query
     */
    public function groupByPermissionRoleCreatedAt(Builder $query)
    {
        $this->joinPermissionRoleTable($query);

        $query->groupBy('permission_role.created_at');
    }

    /**
     * @param Builder $query
     */
    public function groupByPermissionRoleUpdatedAt(Builder $query)
    {
        $this->joinPermissionRoleTable($query);

        $query->groupBy('permission_role.updated_at');
    }

    /**
     * @param Builder $query
     */
    public function groupByPermissionUpdatedAt(Builder $query)
    {
        $query->groupBy('permissions.updated_at');
    }

    /**
     * @param Builder $query
     */
    public function joinPermissionRoleTable(Builder $query)
    {
        if (!$this->haveJoin('permission_role', $query)) {
            $query->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id');
        }
    }

    /**
     * @param Builder $query
     */
    public function joinRolesTable(Builder $query)
    {
        $this->joinPermissionRoleTable($query);

        if (!$this->haveJoin('roles', $query)) {
            $query->join('roles', 'roles.id', '=', 'permission_role.role_id')
                  ->whereNull('roles.deleted_at');
        }
    }

    /**
     * @param Builder $query
     */
    public function selectBase(Builder $query)
    {
        $query->addSelect('permissions.*');
    }

    /**
     * @param Builder $query
     */
    public function selectPermissionRoleTimestamps(Builder $query)
    {
        $query->addSelect('permission_role.created_at as pivot_created_at')
              ->addSelect('permission_role.updated_at as pivot_updated_at');
    }

    /**
     * @param Builder $query
     * @param array   $ids
     */
    public function whereRoleIds(Builder $query, array $ids)
    {
        $this->joinRolesTable($query);

        $query->whereIn('roles.id', $ids);
    }
}
