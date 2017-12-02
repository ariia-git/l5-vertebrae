<?php namespace App\Services\Entities\Language;

use App\Entities\Language\LanguageRepository;
use App\Services\Entities\AbstractService;

class LanguageService extends AbstractService
{
    public function __construct(LanguageRepository $repo)
    {
        $this->repo = $repo;
    }
}
