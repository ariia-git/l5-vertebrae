<?php namespace App\Entities\Currency;

use App\Entities\AbstractRepository;

class CurrencyRepository extends AbstractRepository
{
    protected $sorters = [
        'code' => [
            'asc' => ['iso_code', 'asc'],
            'desc' => ['iso_code', 'desc']
        ],
        'name' => []
    ];

    public function __construct(Currency $model)
    {
        $this->model = $model;
    }
}
