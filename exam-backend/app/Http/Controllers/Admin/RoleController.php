<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Permission;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('view-roles'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::withCount('users')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('create-roles'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.roles.create');
    }

    public function store(StoreRoleRequest $request)
    {
        abort_if(!auth()->user()->hasPermission('create-roles'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Role::create($request->only('name', 'slug'));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully!');
    }

    public function edit(Role $role)
    {
        abort_if(!auth()->user()->hasPermission('edit-roles'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all();                    
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

   public function update(UpdateRoleRequest $request, Role $role)
    {
        abort_if(!auth()->user()->hasPermission('edit-roles'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Update role details
        $role->update($request->only(['name', 'slug']));

        // Sync permissions (add + remove)
        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->name}' updated + permissions synced!");
    }

    public function destroy(Role $role)
    {
        abort_if(!auth()->user()->hasPermission('delete-roles'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (in_array($role->slug, ['admin', 'user'])) {
            return back()->withErrors(['Cannot delete protected roles (admin/user)']);
        }

        $role->delete();
        return back()->with('success', 'Role deleted!');
    }
}
