<?php

// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
       abort_if(!auth()->user()->hasPermission('view-users'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $userRole = auth()->user()->roles->first()->id;

        if($userRole == '2'){
        $users = User::with('roles')->where('id',auth()->user()->id)->withCount('roles')->paginate(10);
        }else{
           $users = User::with('roles')->withCount('roles')->paginate(10);
        }

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('create-users'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        abort_if(!auth()->user()->hasPermission('create-users'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->sync($request->roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        abort_if(!auth()->user()->hasPermission('edit-users'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        abort_if(!auth()->user()->hasPermission('edit-users'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        $user->roles()->sync($request->roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        abort_if(!auth()->user()->hasPermission('delete-users'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($user->email === 'admin@gmail.com') {
            return back()->withErrors(['Cannot delete the super admin account']);
        }

        $user->delete();
        return back()->with('success', 'User deleted!');
    }
}
