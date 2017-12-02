<?php namespace App\Entities\Country;

use App\Entities\AbstractRepository;

class CountryRepository extends AbstractRepository
{
    protected $sorters = [
        'code' => [
            'asc' => ['iso_code', 'asc'],
            'desc' => ['iso_code', 'desc']
        ],
        'name' => []
    ];

    public function __construct(Country $model)
    {
        $this->model = $model;
    }
}
