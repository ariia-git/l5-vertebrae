<?php namespace App\Http\Middleware\Localization;

use Illuminate\Http\Request;

class Localization
{
    /**
     * The URIs that should not be localized.
     *
     * @var array
     */
    protected $except = [];

    /**
     * Determine if the request has a URI that should not be localized.
     *
     * @param Request $request
     * @return bool
     */
    protected function shouldIgnore(Request $request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
