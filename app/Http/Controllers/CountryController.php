<?php namespace App\Http\Controllers;

use App\Http\Requests\AbstractFormRequest;
use App\Services\Entities\Country\CountryService;
use App\Services\Entities\Currency\CurrencyService;

class CountryController extends AbstractController
{
    public function __construct(CountryService $service)
    {
        parent::__construct();

        $this->service = $service;

        if (empty($this->sort)) {
            $this->sort = ['name' => 'asc'];
        }
    }

    /**
     * Display a listing of countries.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->middleware('permission:countries');

        $countries = $this->service->getIndex($this->count, $this->page, $this->filter, $this->sort);
        $countries->setPath(trans('routes.countries'))->appends(\Request::except('page'));

        $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.countries'), 'text' => trans_choice('countries.countries', 2)];

        return view('countries.list', compact(
            'breadcrumbs',
            'countries'
        ));
    }

    /**
     * Show the form for creating a new country.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->middleware('permission:countries.create');

        $currencies = app(CurrencyService::class)->sortBy(['name' => 'asc'])->pluck('name', 'id');

        $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.countries'), 'text' => trans_choice('countries.countries', 2)];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.countries') . '/' . trans('routes.create'), 'text' => trans('common.create')];

        return view('countries.create', compact(
            'breadcrumbs',
            'currencies'
        ));
    }

    /**
     * Store a newly created country in storage.
     *
     * @param AbstractFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function store(AbstractFormRequest $request)
    {
        $this->middleware('permission:countries.create');

        $input = $request->all();

        \DB::beginTransaction();

        try {
            $this->service->create($input);

            \DB::commit();

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('countries.countries', 1), 'action' => strtolower(trans('common.created'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.countries'));
        } catch (\Exception $e) {
            \DB::rollback();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('countries.countries', 1), 'action' => strtolower(trans('common.created'))]));

            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified country.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        $this->middleware('permission:countries.update');

        if ($country = $this->service->find($id)) {
            $currencies = app(CurrencyService::class)->sortBy(['name' => 'asc'])->pluck('name', 'id');

            $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
            $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.countries'), 'text' => trans_choice('countries.countries', 2)];
            $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.countries') . '/' . $id . '/' . trans('routes.edit'), 'text' => $country->name];

            return view('countries.edit', compact(
                'breadcrumbs',
                'country',
                'currencies'
            ));
        } else {
            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('countries.countries', 1), 'action' => strtolower(trans('common.found'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.countries'));
        }
    }

    /**
     * Update the specified country in storage.
     *
     * @param AbstractFormRequest $request
     * @param int                 $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function update(AbstractFormRequest $request, $id)
    {
        $this->middleware('permission:countries.update');

        $input = $request->all();

        \DB::beginTransaction();

        try {
            $this->service->update($id, $input);

            \DB::commit();

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('countries.countries', 1), 'action' => strtolower(trans('common.updated'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.countries'));
        } catch (\Exception $e) {
            \DB::rollback();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('countries.countries', 1), 'action' => strtolower(trans('common.updated'))]));

            return redirect()->back();
        }
    }

    /**
     * Remove the specified country from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->middleware('permission:countries.destroy');

        \DB::beginTransaction();

        try {
            $this->service->destroy($id);

            \DB::commit();

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('countries.countries', 1), 'action' => strtolower(trans('common.deleted'))]));

            return redirect()->back();
        } catch (\Exception $e) {
            \DB::rollback();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('countries.countries', 1), 'action' => strtolower(trans('common.deleted'))]));

            return redirect()->back();
        }
    }
}
