<?php namespace App\Entities;

use Carbon\Carbon;
use Illuminate\Database\Query\Expression;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class AbstractRepository
{
    /**
     * @var array
     */
    protected $appends = [];

    /**
     * @var int|null
     */
    protected $currentPage = null;

    /**
     * @var array
     */
    protected $filterBy = [];

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $groupBy = [];

    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var int|null
     */
    protected $perPage = null;

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $query = null;

    /**
     * @var string
     */
    public $selectKeyName = 'id';

    /**
     * @var string
     */
    public $selectValueName = 'name';

    /**
     * @var array
     */
    protected $sortBy = [];

    /**
     * @var array
     */
    protected $sorters = [];

    /**
     * @var null
     */
    protected $take = null;

    /**
     * @var bool
     */
    protected $tokenizeSearch = false;

    /**
     * @var array
     */
    protected $with = [];

    /**
     * @var array
     */
    protected $withModes = [];

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param string $filterKey
     * @param string $filterValue
     * @return $this
     */
    public function addFilter($filterKey, $filterValue)
    {
        $this->filterBy[$filterKey] = $filterValue;

        return $this;
    }

    /**
     * @return array
     */
    protected function advancedSearch()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function advancedSort()
    {
        return [];
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function appends(array $attributes)
    {
        return $this->model->appends(array_merge($this->appends, $attributes));
    }

    /**
     * @param string $key
     * @param string $value
     */
    protected function applyFilter($key, $value)
    {
        if (array_key_exists($key, $this->filters)) {
            if (is_array($this->filters[$key])) {
                $count = count($this->filters[$key]);

                if ($count) {
                    $where = $this->filters[$key][0];
                    $params = [];

                    for ($i = 1; $i < count($this->filters[$key]); $i++) {
                        if (is_array($value)) {
                            $param = $this->filters[$key][$i];

                            foreach ($value as $valueKey => $valueValue) {
                                $param = str_replace(":{$valueKey}", $valueValue, $param);
                            }

                            $params[] = $param;
                        } else {
                            $params[] = str_replace(':value', $value, $this->filters[$key][$i]);
                        }
                    }

                    if (strpos($where, '/') !== false) {
                        $params = explode(',', $params[0]);
                        $where = str_replace('/', '?' . str_repeat(',?', count($params) - 1), $where);
                    }

                    $this->query = $this->query->whereRaw($where, $params);
                }
            }
        } elseif (env('LOG_MISSING_FILTERS', false)) {
            \Log::debug('Missing filter', [$key, $value, debug_backtrace()]);
        }
    }

    /**
     *
     */
    protected function applyFilters()
    {
        if (is_array($this->filterBy)) {
            $advancedSearch = $this->advancedSearch();

            foreach ($this->filterBy as $key => $value) {
                if (is_array($value)) {
                    if (array_key_exists($key, $advancedSearch)) {
                        $advancedSearch[$key]($this->query, $value);

                        $this->applyFilter($key, $value);
                    }
                } else {
                    if (array_key_exists($key, $advancedSearch)) {
                        $advancedSearch[$key]($this->query, $value);
                    }

                    if ($key == 'search' && $this->tokenizeSearch === true) {
                        // todo: Investigate relevance searching
                        $valueList = explode(' ', $value);
                    } else {
                        $valueList = [$value];
                    }

                    foreach ($valueList as $value) {
                        $this->applyFilter($key, $value);
                    }
                }
            }
        }
    }

    /**
     *
     */
    protected function applyGroups()
    {
        if (is_array($this->groupBy)) {
            foreach ($this->groupBy as $key => $value) {
                if (array_key_exists($key, $this->groups)) {
                    $this->query->groupBy($key);

                    $group = $this->groups[$key];

                    if (array_key_exists('count', $group)) {
                        $this->query->addSelect(new Expression("count(*) as `{$group['count']}`"));
                    }
                }
            }
        }
    }

    /**
     * Order of sorters can be applied by prepending a number and pipe to the direction (i.e.: 1|asc, 2|desc).
     */
    protected function applySorters()
    {
        $advancedSort = $this->advancedSort();
        if (is_array($this->sortBy)) {
            $sorters = $unorderedSorters = [];

            foreach ($this->sortBy as $key => $value) {
                if (array_key_exists($key, $advancedSort)) {
                    $advancedSort[$key]($this->query);
                }

                if (strlen($key) > 0 && strlen($value) > 0) {
                    if (array_key_exists($key, $this->sorters)) {
                        if (strpos($value, '|') !== false) {
                            $parts = explode('|', $value);
                            $value = $parts[1];
                            $order = $parts[0];
                        } else {
                            $order = false;
                        }

                        if (array_key_exists($value, $this->sorters[$key])) {
                            $sort = ['key' => false, 'val' => $this->sorters[$key][$value][0] . ' ' . $this->sorters[$key][$value][1]];
                        } else {
                            $sort = ['key' => $key, 'val' => $value];
                        }

                        if ($order === false) {
                            $unorderedSorters[] = $sort;
                        } else {
                            $sorters[$order] = $sort;
                        }
                    }
                }
            }

            ksort($sorters);

            $sorters = array_merge($sorters, $unorderedSorters);

            foreach ($sorters as $sort) {
                if ($sort['key'] === false) {
                    $this->query = $this->query->orderByRaw($sort['val']);
                } else {
                    $this->query = $this->query->orderBy($sort['key'], $sort['val']);
                }
            }
        }
    }

    /**
     * @param array $attributesList
     * @return mixed
     */
    public function bulkCreate(array $attributesList)
    {
        if ($this->model->timestamps) {
            $now = Carbon::now();

            foreach ($attributesList as &$attributes) {
                $attributes['created_at'] = $now;
                $attributes['updated_at'] = $now;
            }
        }

        return $this->model->insert($attributesList);
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->clearFilters();
        $this->clearQuery();

        return $this;
    }

    /**
     * @return $this
     */
    public function clearAll()
    {
        $this->clearFilters();
        $this->clearQuery();
        $this->clearWith();

        return $this;
    }

    /**
     * @return $this
     */
    public function clearFilters()
    {
        $this->filterBy = [];

        return $this;
    }

    /**
     * @return $this
     */
    public function clearQuery()
    {
        $this->query = null;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearWith()
    {
        $this->with = [];

        return $this;
    }

    /**
     * @param array $attributes
     * @return static
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param array $filters
     * @return $this
     */
    public function filterBy(array $filters)
    {
        if (is_null($filters)) {
            $filters = [];
        }

        $this->filterBy = array_merge($filters, $this->filterBy);

        return $this;
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        $query = $this->make();

        return $query->findOrFail($id);
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $operator
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findAllBy($key, $value, $operator = '=')
    {
        $query = $this->make();

        return $query->where($key, $operator, $value)->get();
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $operator
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function findFirstBy($key, $value, $operator = '=')
    {
        $query = $this->make();

        return $query->where($key, $operator, $value)->first();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function firstByAttributes(array $attributes)
    {
        return $this->model->where($attributes)->first();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function firstOrCreate(array $attributes)
    {
        return $this->model->firstOrCreate($attributes);
    }

    /**
     * @return LengthAwarePaginator|\Illuminate\Support\Collection
     */
    public function get()
    {
        if (is_null($this->query)) {
            $this->make();
        }

        $this->preGet();
        $this->applyFilters();
        $this->applyGroups();

        if (is_null($this->perPage)) {
            $this->applySorters();

            if (!is_null($this->take)) {
                $this->query->take($this->take);
            }

            return $this->query->get();
        } else {
            $perPage = (int)$this->perPage ?: 10;
            $currentPage = $this->currentPage ?: 1;

            $count = $this->getCount(false);

            $this->applySorters();

            $items = $this->query->skip(($currentPage - 1) * $perPage)->limit($perPage)->get();

            return new LengthAwarePaginator($items, $count, $this->perPage);
        }
    }

    /**
     * @param int      $count
     * @param callable $callback
     */
    public function getChunk($count, $callback)
    {
        if (is_null($this->query)) {
            $this->make();
        }

        $this->preGet();
        $this->applyFilters();
        $this->applyGroups();
        $this->applySorters();

        $this->query->chunk($count, $callback);
    }

    /**
     * @param bool $apply
     * @return int
     */
    public function getCount($apply = true)
    {
        if (is_null($this->query)) {
            $this->make();
        }

        if ($apply === true) {
            $this->preGet();
            $this->applyFilters();
            $this->applyGroups();
        }

        if (count($this->groupBy) > 0) {
            $count = \DB::table(\DB::raw("({$this->query->toSql()}) as t"))->mergeBindings($this->query->getQuery())->count();
        } else {
            $count = $this->query->count();
        }

        return $count;
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return mixed
     */
    protected function getOffset($page, $perPage)
    {
        return ($page - 1) * $perPage;
    }

    /**
     * @param array|string $groups
     * @return $this
     */
    public function groupBy($groups)
    {
        if (is_null($groups)) {
            return $this;
        } else if (!is_array($groups)) {
            $groups = [$groups];
        }

        $this->groupBy = array_merge($groups, $this->groupBy);

        return $this;
    }

    /**
     * @param string $relation
     * @return mixed
     */
    public function has($relation)
    {
        $query = $this->make();

        return $query->has($relation)->get();
    }

    /**
     * @param array|string $filterNameList
     * @return bool
     */
    public function haveFilter($filterNameList)
    {
        if (!is_array($filterNameList)) {
            $filterNameList = [$filterNameList];
        }

        foreach ($filterNameList as $filterName) {
            if (array_key_exists($filterName, $this->filterBy)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string                                $table
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return bool|mixed
     */
    public function haveJoin($table, $query)
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

                // todo: \Illuminate\Database\Query\Expression doesn't exist?
                if (is_object($item->table) && get_class($item->table) == 'Illuminate\Database\Query\Expression') {
                    if ($item->table->getValue() === $table) {
                        return true;
                    }
                }

                return $carry;
            }, false);
        } else {
            $haveJoin = false;
        }

        return $haveJoin;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|static
     */
    protected function make()
    {
        $this->model->append($this->appends);

        $this->query = $this->model->with($this->with);

        if (empty($this->query->columns)) {
            $this->query->select("{$this->model->getTable()}.*");
        }

        return $this->query;
    }

    /**
     * @param int $perPage
     * @param int $currentPage
     * @return $this
     */
    public function paginate($perPage, $currentPage = 1)
    {
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * @param string      $value
     * @param string|null $key
     * @return mixed
     */
    public function pluck($value, $key = null)
    {
        $query = $this->make();

        return $query->pluck($value, $key);
    }

    /**
     *
     */
    protected function preGet()
    {
        //
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->query = null;
        $this->clearFilters();

        return $this;
    }

    /**
     * @param array $sorters
     * @return $this
     */
    public function sortBy(array $sorters)
    {
        $this->sortBy = $sorters;

        return $this;
    }

    /**
     * @param int $take
     * @return $this
     */
    public function take($take)
    {
        $this->take = $take;

        return $this;
    }

    /**
     * @param int   $id
     * @param array $attributes
     * @return bool
     */
    public function update($id, array $attributes)
    {
        $item = $this->model->findOrFail($id);

        /** @var \Illuminate\Database\Eloquent\Model $item */
        return $item->update($attributes);
    }

    /**
     * @param array $with
     * @return $this
     */
    public function with(array $with)
    {
        $this->with = array_merge($this->with, $with);

        return $this;
    }
}
