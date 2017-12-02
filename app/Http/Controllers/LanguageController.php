<?php namespace App\Http\Controllers;

use App\Http\Requests\AbstractFormRequest;
use App\Services\Entities\Language\LanguageService;

class LanguageController extends AbstractController
{
    public function __construct(LanguageService $service)
    {
        parent::__construct();

        $this->service = $service;

        if (empty($this->sort)) {
            $this->sort = ['name' => 'asc'];
        }
    }

    /**
     * Display a listing of languages.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumbs = [];
        $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.languages'), 'text' => trans_choice('languages.languages', 2)];

        $languages = $this->service->getIndex($this->count, $this->page, $this->filter, $this->sort);
        $languages->setPath(trans('routes.languages'))->appends(\Request::except('page'));

        return view('languages.list', compact(
            'breadcrumbs',
            'languages'
        ));
    }

    /**
     * Show the form for creating a new language.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $breadcrumbs = [];
        $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.languages'), 'text' => trans_choice('languages.languages', 2)];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.languages') . '/' . trans('routes.create'), 'text' => trans('common.create')];

        return view('languages.create', compact(
            'breadcrumbs'
        ));
    }

    /**
     * Store a newly created language in storage.
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

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('langauges', 1), 'action' => strtolower(trans('common.created'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.languages'));
        } catch (\Exception $e) {
            \DB::rollBack();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('languages.languages', 1), 'action' => strtolower(trans('common.created'))]));

            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified language.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($id)
    {
        if ($language = $this->service->find($id)) {
            $breadcrumbs = [];
            $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
            $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.languages'), 'text' => trans_choice('languages.languages', 2)];
            $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.languages') . '/' . trans('routes.edit'), 'text' => $language->name];

            return view('languages.edit', compact(
                'breadcrumbs',
                'language'
            ));
        } else {
            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('languages.languages', 1), 'action' => strtolower(trans('common.found'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.languages'));
        }
    }

    /**
     * Update the specified language in storage.
     *
     * @param \App\Http\Requests\AbstractFormRequest $request
     * @param int                                    $id
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

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('languages.languages', 1), 'action' => strtolower(trans('common.updated'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.languages'));
        } catch (\Exception $e) {
            \DB::rollback();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('languages.languages', 1), 'action' => strtolower(trans('common.updated'))]));

            return redirect()->back();
        }
    }

    /**
     * Remove the specified language from storage.
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

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('languages.languages', 1), 'action' => strtolower(trans('common.deleted'))]));

            return redirect()->back();
        } catch (\Exception $e) {
            \DB::rollback();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('languages.languages', 1), 'action' => strtolower(trans('common.deleted'))]));

            return redirect()->back();
        }
    }
}
