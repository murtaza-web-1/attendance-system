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
  public function assign(Request $request, $userId)
{
    \Log::info('Assign method hit', ['role' => $request->role]);

    $request->validate([
        'role' => 'required|exists:roles,name',
    ]);

    $user = User::findOrFail($userId);
    $user->syncRoles([$request->role]);

    return response()->json(['message' => 'Role updated successfully.']);
}
public function removePermission(Request $request)
{
    $role = Role::findByName($request->role);
    $permission = Permission::findByName($request->permission);

    $role->revokePermissionTo($permission);

    return response()->json(['message' => 'Permission removed']);
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

    // 🔍 Double-check if permission is assigned
    if ($role->permissions->contains('name', $permission->name)) {
        return response()->json(['message' => 'Permission already exists.']);
    }

    // ✅ Assign permission
    $role->givePermissionTo($permission);

    return response()->json(['message' => 'Permission assigned successfully!']);
}
/**
     * Store a new role via AJAX.
     */
public function storeAjax(Request $request)
{
    $request->validate([
        'name' => 'required|string|unique:roles,name'
    ]);

    $role = Role::create(['name' => $request->name]);

    // Return JSON for frontend updates
    return response()->json([
        'message' => '✅ Role created successfully!',
        'role' => $role->name
    ]);
}
}
