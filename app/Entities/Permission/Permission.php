<?php namespace App\Entities\Permission;

use App\Entities\AbstractEntity;
use App\Entities\Role\Role;
use App\Entities\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Permission extends AbstractEntity
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'key',
        'description'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->getAttribute('description');
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->getAttribute('key');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }

    /**
     * @return Role[]|Collection
     */
    public function getRoles()
    {
        return $this->getAttribute('roles');
    }

    /**
     * @return User[]|Collection
     */
    public function getUsers()
    {
        return $this->getAttribute('users');
    }
}
