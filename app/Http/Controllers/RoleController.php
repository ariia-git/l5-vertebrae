<?php namespace App\Http\Controllers;

use App\Entities\Permission\Permission;
use App\Entities\Role\Role;
use App\Http\Requests\AbstractFormRequest;
use App\Services\Entities\Permission\PermissionService;
use App\Services\Entities\Role\RoleService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class RoleController extends AbstractController
{
    public function __construct(RoleService $service)
    {
        parent::__construct();

        $this->service = $service;

        if (empty($this->sort)) {
            $this->sort = ['code' => 'asc'];
        }
    }

    /**
     * Display a listing of roles.
     *
     * @return Factory|View
     */
    public function index()
    {
        $this->middleware('permission:roles');

        $roles = $this->service->getIndex($this->count, $this->page, $this->filter, $this->sort);
        $roles->setPath(trans('routes.roles'))->appends(\Request::except('page'));

        $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.roles'), 'text' => trans_choice('roles.roles', 2)];

        return view('roles.list', compact(
            'breadcrumbs',
            'roles'
        ));
    }

    /**
     * Show the form for creating a new role.
     *
     * @return Factory|View
     */
    public function create()
    {
        $this->middleware('permission:roles.create');

        $permissions = app(PermissionService::class)
            ->sortBy(['key' => 'asc'])
            ->get();

        $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.roles'), 'text' => trans_choice('roles.roles', 2)];
        $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.roles') . '/' . trans('routes.create'), 'text' => trans('common.create')];

        return view('roles.create', compact(
            'breadcrumbs',
            'permissions'
        ));
    }

    /**
     * Store a newly created role in storage.
     *
     * @param AbstractFormRequest $request
     * @return RedirectResponse|Redirector
     * @throws \Exception
     */
    public function store(AbstractFormRequest $request)
    {
        $this->middleware('permission:roles.create');

        $input = $request->all();

        \DB::beginTransaction();

        try {
            $this->service->create($input);

            \DB::commit();

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('roles.roles', 1), 'action' => strtolower(trans('common.created'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.roles'));
        } catch (\Exception $e) {
            \DB::rollback();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('roles.roles', 1), 'action' => strtolower(trans('common.created'))]));

            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param int $id
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function edit($id)
    {
        $this->middleware('permission:roles.update');

        /** @var Role $role */
        if ($role = $this->service->find($id)) {
            $permissions = app(PermissionService::class)
                ->sortBy(['key' => 'asc'])
                ->get()
                ->map(function (Permission $permission) use ($role) {
                    $permission->checked = false;
                    if ($permission->roles()->get()->contains($role)) {
                        $permission->checked = true;
                    }

                    return $permission;
                });

            $breadcrumbs[] = ['link' => trans('routes.admin'), 'text' => trans('common.admin')];
            $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.roles'), 'text' => trans_choice('roles.roles', 2)];
            $breadcrumbs[] = ['link' => trans('routes.admin') . '/' . trans('routes.roles') . '/' . $id . '/' . trans('routes.edit'), 'text' => $role->getName()];

            return view('roles.edit', compact(
                'breadcrumbs',
                'role',
                'permissions'
            ));
        } else {
            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('roles.roles', 1), 'action' => strtolower(trans('common.found'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.roles'));
        }
    }

    /**
     * Update the specified role in storage.
     *
     * @param AbstractFormRequest $request
     * @param int                 $id
     * @return RedirectResponse|Redirector
     * @throws \Exception
     */
    public function update(AbstractFormRequest $request, $id)
    {
        $this->middleware('permission:roles.update');

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

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('roles.roles', 1), 'action' => strtolower(trans('common.updated'))]));

            return redirect(trans('routes.admin') . '/' . trans('routes.roles'));
        } catch (\Exception $e) {
            \DB::rollback();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('roles.roles', 1), 'action' => strtolower(trans('common.updated'))]));

            return redirect()->back();
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * @param int $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->middleware('permission:roles.destroy');

        \DB::beginTransaction();

        try {
            $this->service->destroy($id);

            \DB::commit();

            \Session::push('successes', trans('common.success.action_completed', ['item' => trans_choice('roles.roles', 1), 'action' => strtolower(trans('common.deleted'))]));

            return redirect()->back();
        } catch (\Exception $e) {
            \DB::rollback();

            \Session::push('errors', trans('common.error.action_not_completed', ['item' => trans_choice('roles.roles', 1), 'action' => strtolower(trans('common.deleted'))]));

            return redirect()->back();
        }
    }
}
