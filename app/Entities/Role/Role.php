<?php namespace App\Entities\Role;

use App\Entities\AbstractEntity;
use App\Entities\Permission\Permission;
use App\Entities\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends AbstractEntity
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'key',
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
}
