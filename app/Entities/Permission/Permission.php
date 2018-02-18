<?php namespace App\Entities\Permission;

use App\Entities\AbstractEntity;
use App\Entities\Role\Role;
use App\Entities\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
