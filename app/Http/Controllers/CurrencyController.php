<?php namespace App\Http\Controllers;

use App\Http\Requests\AbstractFormRequest;
use App\Services\Entities\Currency\CurrencyService;

class CurrencyController extends AbstractController
{
    public function __construct(CurrencyService $service)
    {
        parent::__construct();

        $this->service = $service;

        if (empty($this->sort)) {
            $this->sort = ['name' => 'asc'];
        }
    }

    /**
     * Display a listing of currencies.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $currencies = $this->service->getIndex($this->count, $this->page, $this->filter, $this->sort);
        $currencies->setPath('currencies')->appends(\Request::except('page'));

        $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.currencies'), 'text' => trans_choice('currencies.currencies', 2)];

        return view('currencies.list', compact(
            'breadcrumbs',
            'currencies'
        ));
    }

    /**
     * Show the form for creating a new currency.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.currencies'), 'text' => trans_choice('currencies.currencies', 2)];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.currencies') . '/' . trans('routes.create'), 'text' => trans('common.create')];

        return view('currencies.create', compact(
            'breadcrumbs'
        ));
    }

    /**
     * Store a newly created currency in storage.
     *
     * @param AbstractFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function store(AbstractFormRequest $request)
    {
        $input = $request->all();

        \DB::beginTransaction();

        try {
            $this->service->create($input);

            \DB::commit();

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('currencies.currencies', 1), 'action' => strtolower(trans('common.created'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.currencies'));
        } catch (\Exception $e) {
            \DB::rollBack();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('currencies.currencies', 1), 'action' => strtolower(trans('common.created'))]));

            return redirect('admin/currencies/create');
        }
    }

    /**
     * Show the form for editing the specified currency.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        if ($currency = $this->service->find($id)) {
            $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
            $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.currencies'), 'text' => trans_choice('currencies.currencies', 2)];
            $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.currencies') . '/' . $id . '/' . trans('routes.edit'), 'text' => $currency->name];

            return view('currencies.edit', compact(
                'breadcrumbs',
                'currency'
            ));
        } else {
            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('currencies.currencies', 1), 'action' => strtolower(trans('common.found'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.currencies'));
        }
    }

    /**
     * Update the specified currency in storage.
     *
     * @param AbstractFormRequest $request
     * @param int                 $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function update(AbstractFormRequest $request, $id)
    {
        $input = $request->all();

        \DB::beginTransaction();

        try {
            $this->service->update($id, $input);

            \DB::commit();

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('currencies.currencies', 1), 'action' => strtolower(trans('common.updated'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.currencies'));
        } catch (\Exception $e) {
            \DB::rollBack();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('currencies.currencies', 1), 'action' => strtolower(trans('common.updated'))]));

            return redirect()->back();
        }
    }

    /**
     * Remove the specified currency from storage.
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

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('currencies.currencies', 1), 'action' => strtolower(trans('common.deleted'))]));

            return redirect()->back();
        } catch (\Exception $e) {
            \DB::rollBack();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('currencies.currencies', 1), 'action' => strtolower(trans('common.deleted'))]));

            return redirect()->back();
        }
    }
}
