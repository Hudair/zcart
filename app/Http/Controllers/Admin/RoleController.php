<?php

namespace App\Http\Controllers\Admin;

use App\Common\Authorizable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Role\RoleRepository;
use App\Http\Requests\Validations\CreateRoleRequest;
use App\Http\Requests\Validations\UpdateRoleRequest;

class RoleController extends Controller
{
    use Authorizable;

    private $model;

    private $role;

    /**
     * construct
     */
    public function __construct(RoleRepository $role)
    {
        parent::__construct();

        $this->model = trans('app.model.role');

        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->role->all();

        $trashes = $this->role->trashOnly();

        return view('admin.role.index', compact('roles', 'trashes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.role._create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoleRequest $request)
    {
        $this->role->store($request);

        return back()->with('success', trans('messages.created', ['model' => $this->model]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = $this->role->find($id);

        $role_permissions = $this->role->getPermissions($role);

        return view('admin.role._show', compact('role', 'role_permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = $this->role->find($id);

        return view('admin.role._edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.roles', 3)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->role->update($request, $id);

        return back()->with('success', trans('messages.updated', ['model' => $this->model]));
    }

    /**
     * Trash the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.roles', 3)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->role->trash($id);

        return back()->with('success', trans('messages.trashed', ['model' => $this->model]));
    }

    /**
     * Restore the specified resource from soft delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        $this->role->restore($id);

        return back()->with('success', trans('messages.restored', ['model' => $this->model]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->role->destroy($id);

        return back()->with('success',  trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massTrash(Request $request)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->role->massTrash($request->ids);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.trashed', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.trashed', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->role->massDestroy($request->ids);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * Empty the Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyTrash(Request $request)
    {
        $this->role->emptyTrash($request);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

}
