<?php namespace App\Entities\Language;

use App\Entities\AbstractRepository;

class LanguageRepository extends AbstractRepository
{
    protected $sorters = [
        'code' => [
            'asc' => ['iso_code', 'asc'],
            'desc' => ['iso_code', 'desc']
        ],
        'name' => [],
        'script' => []
    ];

    public function __construct(Language $model)
    {
        $this->model = $model;
    }
}
