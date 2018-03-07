<?php namespace App\Services\Entities\Role;

use App\Entities\Role\RoleRepository;
use App\Services\Entities\AbstractService;

class RoleService extends AbstractService
{
    public function __construct(RoleRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $role = parent::create($attributes);

        if (array_key_exists('permissions', $attributes)) {
            foreach ($attributes['permissions'] as $permission) {
                $role->permissions()->attach($permission);
            }
        }

        return $role;
    }

    /**
     * @param int   $id
     * @param array $attributes
     * @return boolean
     */
    public function update($id, array $attributes)
    {
        $role = $this->find($id);

        $result = parent::update($id, $attributes);

        if (array_key_exists('permissions', $attributes)) {
            $role->permissions()->sync($attributes['permissions']);
        }

        return $result;
    }
}
