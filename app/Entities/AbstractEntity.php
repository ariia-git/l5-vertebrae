<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractEntity extends Model
{
    /**
     * @param array $attributes
     * @return $this
     */
    public function appends(array $attributes)
    {
        return $this->append($attributes);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    protected static function firstByAttributes(array $attributes)
    {
        return static::where($attributes)->first();
    }
}
