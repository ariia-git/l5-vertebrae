<?php namespace App\Http\Controllers;

use App\Entities\Language\Language;
use App\Http\Requests\AbstractFormRequest;
use App\Services\Entities\Language\LanguageService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

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
     * @return Factory|View
     */
    public function index()
    {
        $this->middleware('permission:languages');

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
     * @return Factory|View
     */
    public function create()
    {
        $this->middleware('permission:languages.create');

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
     * @return RedirectResponse|Redirector
     * @throws \Exception
     */
    public function store(AbstractFormRequest $request)
    {
        $this->middleware('permission:languages.create');

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
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function edit($id)
    {
        $this->middleware('permission:languages.update');

        /** @var Language $language */
        if ($language = $this->service->find($id)) {
            $breadcrumbs = [];
            $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
            $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.languages'), 'text' => trans_choice('languages.languages', 2)];
            $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.languages') . '/' . trans('routes.edit'), 'text' => $language->getName()];

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
     * @param AbstractFormRequest $request
     * @param int                 $id
     * @return RedirectResponse|Redirector
     * @throws \Exception
     */
    public function update(AbstractFormRequest $request, $id)
    {
        $this->middleware('permission:languages.update');

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
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->middleware('permission:languages.destroy');

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
