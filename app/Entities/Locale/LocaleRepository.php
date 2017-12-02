<?php namespace App\Entities\Locale;

use App\Entities\AbstractRepository;

class LocaleRepository extends AbstractRepository
{
    protected $sorters = [
        'code' => [
            'asc' => ['code', 'asc'],
            'desc' => ['code', 'desc']
        ]
    ];

    public function __construct(Locale $model)
    {
        $this->model = $model;
    }
}
