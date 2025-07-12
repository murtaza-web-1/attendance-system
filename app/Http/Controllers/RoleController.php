<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // Show all users and their roles
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::pluck('name', 'name');

        return view('admin.manage-roles', compact('users', 'roles'));
    }

    // Assign role to user
    public function assign(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully!');
    }
}
