<?php namespace App\Entities\Role;

use App\Entities\AbstractEntity;
use App\Entities\Permission\Permission;
use App\Entities\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Role extends AbstractEntity
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'key',
        'description'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
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
     * @return Permission[]|Collection
     */
    public function getPermissions()
    {
        return $this->getAttribute('permissions');
    }

    /**
     * @return User[]|Collection
     */
    public function getUsers()
    {
        return $this->getAttribute('Users');
    }
}
