<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required|array'
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        if ($role->name === 'admin' || $role->name === 'customer') {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete core roles.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
} 