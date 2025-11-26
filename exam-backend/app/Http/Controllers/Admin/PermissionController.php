<?php

// app/Http/Controllers/Admin/PermissionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('view-permissions'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::paginate(10);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('create-permissions'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permissions.create');
    }

    public function store(StorePermissionRequest $request)
    {
        abort_if(!auth()->user()->hasPermission('create-permissions'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Permission::create($request->all());

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully!');
    }

    public function edit(Permission $permission)
    {
        abort_if(!auth()->user()->hasPermission('edit-permissions'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        abort_if(!auth()->user()->hasPermission('edit-permissions'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission->update($request->all());

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated!');
    }

    public function destroy(Permission $permission)
    {
        abort_if(!auth()->user()->hasPermission('delete-permissions'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission->delete();
        return back()->with('success', 'Permission deleted!');
    }
}
