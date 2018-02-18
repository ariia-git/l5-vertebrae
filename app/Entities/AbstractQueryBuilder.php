<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Builder;

class AbstractQueryBuilder
{
    /** @var \App\Entities\AbstractEntity $model */
    protected $model;

    public function createNewQuery()
    {
        return $this->model->newQuery();
    }

    /**
     * @param string  $table
     * @param Builder $query
     * @return bool
     */
    protected function hasRootTable($table, Builder $query)
    {
        /** @var \Illuminate\Database\Query\Builder $builder */
        $builder = $query->getQuery();
        if (property_exists($builder, 'from')) {
            $hasRootTable = ($builder->from == $table);
        } else {
            $hasRootTable = false;
        }

        return $hasRootTable;
    }

    /**
     * @param string  $table
     * @param Builder $query
     * @return bool|mixed
     */
    protected function haveJoin($table, Builder $query)
    {
        /** @var \Illuminate\Database\Query\Builder $builder */
        $builder = $query->getQuery();
        if (is_array($builder->joins)) {
            $haveJoin = array_reduce($builder->joins, function ($carry, $item) use ($table) {
                if ($carry) {
                    return $carry;
                }

                /** @var \Illuminate\Database\Query\JoinClause $item */
                if ($item->table === $table) {
                    return true;
                }

                return $carry;
            }, false);
        } else {
            $haveJoin = false;
        }

        return $haveJoin;
    }

    /**
     * @param string  $select
     * @param Builder $query
     * @return bool|mixed
     */
    protected function haveSelect($select, Builder $query)
    {
        /** @var \Illuminate\Database\Query\Builder $builder */
        $builder = $query->getQuery();
        if (is_array($builder->columns)) {
            $haveSelect = array_reduce($builder->columns, function ($carry, $item) use ($select) {
                if ($carry) {
                    return $carry;
                }

                $selectParts = explode(' ', $item);
                $selectAlias = end($selectParts);

                if ($selectAlias === false) {
                    return false;
                } else if ($selectAlias === $select) {
                    return true;
                }

                return $carry;
            }, false);
        } else {
            $haveSelect = false;
        }

        return $haveSelect;
    }
}
