<?php namespace App\Http\Controllers;

use App\Http\Requests\AbstractFormRequest;
use App\Services\Entities\Country\CountryService;
use App\Services\Entities\Language\LanguageService;
use App\Services\Entities\Locale\LocaleService;

class LocaleController extends AbstractController
{
    public function __construct(LocaleService $service)
    {
        parent::__construct();

        $this->service = $service;

        if (empty($this->sort)) {
            $this->sort = ['code' => 'asc'];
        }
    }

    /**
     * Display a listing of locales.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $locales = $this->service->with(['country', 'language'])->getIndex($this->count, $this->page, $this->filter, $this->sort);
        $locales->setPath(trans('routes.locales'))->appends(\Request::except('page'));

        $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.locales'), 'text' => trans_choice('locales.locales', 2)];

        return view('locales.list', compact(
            'breadcrumbs',
            'locales'
        ));
    }

    /**
     * Show the form for creating a new locale.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $countries = app(CountryService::class)->sortBy(['name' => 'asc'])->pluck('name', 'id');
        $languages = app(LanguageService::class)->sortBy(['name' => 'asc'])->pluck('name', 'id');

        $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.locales'), 'text' => trans_choice('locales.locales', 2)];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.locales') . '/' . trans('routes.create'), 'text' => trans('common.create')];

        return view('locales.create', compact(
            'breadcrumbs',
            'countries',
            'languages'
        ));
    }

    /**
     * Store a newly created locale in storage.
     *
     * @param AbstractFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function store(AbstractFormRequest $request)
    {
        $input = $request->all();

        if ($request->has('active')) {
            $input['active'] = 1;
        } else {
            $input['active'] = 0;
        }

        \DB::beginTransaction();

        try {
            $this->service->create($input);

            \DB::commit();

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('locales.locales', 1), 'action' => strtolower(trans('common.created'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.locales'));
        } catch (\Exception $e) {
            \DB::rollback();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('locales.locales', 1), 'action' => strtolower(trans('common.created'))]));

            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified locale.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        if ($locale = $this->service->find($id)) {
            $countries = app(CountryService::class)->sortBy(['name' => 'asc'])->pluck('name', 'id');
            $languages = app(LanguageService::class)->sortBy(['name' => 'asc'])->pluck('name', 'id');

            $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
            $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.locales'), 'text' => trans_choice('locales.locales', 2)];
            $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.locales') . '/' . $id . '/' . trans('routes.edit'), 'text' => trans('common.edit')];

            return view('locales.edit', compact(
                'breadcrumbs',
                'countries',
                'languages',
                'locale'
            ));
        } else {
            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('locales.locales', 1), 'action' => strtolower(trans('common.found'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.locales'));
        }
    }

    /**
     * Update the specified locale in storage.
     *
     * @param \App\Http\Requests\AbstractFormRequest $request
     * @param int                                    $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function update(AbstractFormRequest $request, $id)
    {
        $input = $request->all();

        if ($request->has('active')) {
            $input['active'] = 1;
        } else {
            $input['active'] = 0;
        }

        \DB::beginTransaction();

        try {
            $this->service->update($id, $input);

            \DB::commit();

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('locales.locales', 1), 'action' => strtolower(trans('common.updated'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.locales'));
        } catch (\Exception $e) {
            \DB::rollback();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('locales.locales', 1), 'action' => strtolower(trans('common.updated'))]));

            return redirect()->back();
        }
    }

    /**
     * Remove the specified locale from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        \DB::beginTransaction();

        try {
            $this->service->destroy($id);

            \DB::commit();

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('locales.locales', 1), 'action' => strtolower(trans('common.deleted'))]));

            return redirect()->back();
        } catch (\Exception $e) {
            \DB::rollback();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('locales.locales', 1), 'action' => strtolower(trans('common.deleted'))]));

            return redirect()->back();
        }
    }
}
