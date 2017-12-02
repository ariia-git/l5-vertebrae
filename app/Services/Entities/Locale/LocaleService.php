<?php namespace App\Services\Entities\Locale;

use App\Entities\Locale\LocaleRepository;
use App\Services\Entities\AbstractService;

class LocaleService extends AbstractService
{
    public function __construct(LocaleRepository $repo)
    {
        $this->repo = $repo;
    }
}
