<?php namespace App\Services\Entities;

use App\Entities\AbstractRepository;
use Carbon\Carbon;

abstract class AbstractService
{
    /**
     * @var array
     */
    protected $defaultAttributes = [];

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var AbstractRepository
     */
    protected $repo;

    /**
     * @var array
     */
    protected $timestampAttributes = [];

    /**
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->repo->all();
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function appends(array $attributes)
    {
        $this->repo->appends($attributes);

        return $this;
    }

    /**
     * @param array $attributesList
     * @return mixed
     */
    public function bulkCreate(array $attributesList)
    {
        return $this->repo->bulkCreate($attributesList);
    }

    /**
     * @return $this
     */
    public function clearAll()
    {
        $this->repo->clearAll();

        return $this;
    }

    /**
     * @return $this
     */
    public function clearFilters()
    {
        $this->repo->clearFilters();

        return $this;
    }

    /**
     * @return $this
     */
    public function clearQuery()
    {
        $this->repo->clearQuery();

        return $this;
    }

    /**
     * @return $this
     */
    public function clearWith()
    {
        $this->repo->clearWith();

        return $this;
    }

    /**
     * @return mixed
     */
    public function count()
    {
        $result = $this->repo->getCount();
        $this->reset();

        return $result;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        foreach ($this->defaultAttributes as $key => $value) {
            if (!array_key_exists($key, $attributes)) {
                $attributes[$key] = $value;
            }
        }

        $attributes = $this->filterTimestampAttributes($attributes);

        return $this->repo->create($attributes);
    }

    /**
     * @param int $id
     * @return array
     */
    public function destroy($id)
    {
        $this->repo->delete($id);

        return [];
    }

    /**
     * @param array $filter
     * @param bool  $filterTimestamp
     * @return $this
     */
    public function filterBy(array $filter, $filterTimestamp = true)
    {
        if ($filterTimestamp) {
            $filter = $this->filterTimestampAttributes($filter);
        }

        $this->repo->filterBy($filter);

        return $this;
    }

    /**
     * @param array $filter
     * @return mixed
     */
    public function filterTimestampAttributes(array $filter)
    {
        foreach ($this->timestampAttributes as $timestampAttribute) {
            if (array_key_exists($timestampAttribute, $filter) && !is_object($filter[$timestampAttribute])) {
                $filter[$timestampAttribute] = Carbon::createFromTime($filter[$timestampAttribute] / 1000);
            }
        }

        return $filter;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->repo->find($id);
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $operator
     * @return mixed
     */
    public function findAllBy($key, $value, $operator = '=')
    {
        return $this->repo->findAllBy($key, $value, $operator);
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $operator
     * @return mixed
     */
    public function findFirstBy($key, $value, $operator = '=')
    {
        return $this->repo->findFirstBy($key, $value, $operator);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function firstByAttributes(array $attributes)
    {
        return $this->repo->firstByAttributes($attributes);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function firstOrCreate(array $attributes)
    {
        return $this->repo->firstOrCreate($attributes);
    }

    /**
     * @return mixed
     */
    public function get()
    {
        $result = $this->repo->get();
        $this->reset();

        return $result;
    }

    /**
     * @param int      $count
     * @param callable $callback
     */
    public function getChunk($count, callable $callback)
    {
        $this->repo->getChunk($count, $callback);
    }

    /**
     * @param array $filter
     * @return mixed
     */
    public function getCount(array $filter)
    {
        return $this->repo->filterBy($filter)->getCount();
    }

    /**
     * @param int   $count
     * @param int   $page
     * @param array $filter
     * @param array $sorting
     * @return mixed
     */
    public function getIndex($count, $page, array $filter, array $sorting)
    {
        $filter = $this->filterTimestampAttributes($filter);

        return $this->repo->paginate($count, $page)->filterBy($filter)->sortBy($sorting)->get();
    }

    /**
     * @param array $filter
     * @param array $sorting
     * @return mixed
     */
    public function getList(array $filter, array $sorting)
    {
        $filter = $this->filterTimestampAttributes($filter);

        return $this->repo->filterBy($filter)->sortBy($sorting)->get();
    }

    /**
     * @param array $groups
     * @return $this
     */
    public function groupBy(array $groups)
    {
        $this->repo->groupBy($groups);

        return $this;
    }

    /**
     * @param int $perPage
     * @param int $currentPage
     * @return $this
     */
    public function paginate($perPage, $currentPage = 1)
    {
        $this->repo->paginate($perPage, $currentPage);

        return $this;
    }

    /**
     * @param string      $value
     * @param string|null $key
     * @return \Illuminate\Support\Collection
     */
    public function pluck($value, $key = null)
    {
        return $this->repo->pluck($value, $key);
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->repo->reset();

        return $this;
    }

    /**
     * @param string $key
     * @param array  $args
     * @return mixed
     */
    protected function returnError($key, array $args = [])
    {
        $result = $this->errors[$key];
        $result['error'] = call_user_func_array('sprintf', array_merge([$result['error']], $args));

        return $result;
    }

    /**
     * @return array
     */
    public function select()
    {
        $result = $this->repo
            ->filterBy($this->input('filter'))
            ->sortBy($this->input('sorting'))->get();

        $keyName = $this->repo->selectKeyName;
        $valueName = $this->repo->selectValueName;

        $data = [];
        foreach ($result as $item) {
            $data[] = [
                $keyName => $item->$keyName,
                $valueName => $item->$valueName
            ];
        }

        return $data;
    }

    /**
     * @param array $sorters
     * @return static
     */
    public function sortBy(array $sorters)
    {
        $this->repo->sortBy($sorters);

        return $this;
    }

    /**
     * @param int $take
     * @return $this
     */
    public function take($take)
    {
        $this->repo->take($take);

        return $this;
    }

    /**
     * @param int   $id
     * @param array $attributes
     * @return boolean
     */
    public function update($id, array $attributes)
    {
        foreach ($this->timestampAttributes as $timestampAttribute) {
            if (array_key_exists($timestampAttribute, $attributes) && !is_null($attributes[$timestampAttribute])) {
                if (is_string($attributes[$timestampAttribute])) {
                    $attributes[$timestampAttribute] = Carbon::parse($attributes[$timestampAttribute]);
                } else {
                    $timezone = \Config::get('timezone');
                    $attributes[$timestampAttribute] = Carbon::createFromTimestamp($attributes[$timestampAttribute] / 1000, $timezone)->tz('UTC');
                }
            }
        }

        $result = $this->repo->update($id, $attributes);

        return $result;
    }

    /**
     * @param array $with
     * @return $this
     */
    public function with(array $with)
    {
        $this->repo->with($with);

        return $this;
    }
}
