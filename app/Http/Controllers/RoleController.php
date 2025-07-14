<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Show all users and their assigned roles.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::pluck('name', 'name');

        return view('admin.manage-roles', compact('users', 'roles'));
    }

    /**
     * Assign a role to a user.
     */
    // public function assign(Request $request, User $user)
    // {
    //     $request->validate([
    //         'role' => 'required|exists:roles,name',
    //     ]);

    //     $user->syncRoles([$request->role]);

    //     return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully!');
    // }
    public function assign(Request $request, User $user)
{
     // 👇 Add this line first
    \Log::info('Assign method hit', $request->all());
    $request->validate([
        'role' => 'required|exists:roles,name',
    ]);

    $user->syncRoles([$request->role]);

    return response()->json([
        'message' => '✅ Role updated successfully!',
        'role' => $request->role
    ]);
}

    /**
     * Show all roles and their assigned permissions.
     */
    public function permissions()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return view('admin.roles.permissions', compact('roles', 'permissions'));
    }

    /**
     * Assign a permission to a role.
     */
   public function assignPermission(Request $request)
{
    $request->validate([
        'role' => 'required|exists:roles,name',
        'permission' => 'required|exists:permissions,name',
    ]);

    $role = Role::findByName($request->role);
    $permission = Permission::findByName($request->permission);

    if (!$role->hasPermissionTo($permission)) {
        $role->givePermissionTo($permission);
    }

    return redirect()->route('admin.permissions.index')
     ->with('success', '✅ Permission assigned successfully!');
}
}
