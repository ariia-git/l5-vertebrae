<?php namespace App\Services\Entities\Country;

use App\Entities\Country\CountryRepository;
use App\Services\Entities\AbstractService;

class CountryService extends AbstractService
{
    public function __construct(CountryRepository $repo)
    {
        $this->repo = $repo;
    }
}
