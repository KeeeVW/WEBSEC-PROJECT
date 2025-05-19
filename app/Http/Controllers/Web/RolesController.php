<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin_users');
    }
    
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }
    
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);
        
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        
        Artisan::call('cache:clear');
        
        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }
    
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }
    
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,'.$role->id,
            'permissions' => 'required|array',
        ]);
        
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        
        Artisan::call('cache:clear');
        
        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }
    
    public function destroy(Role $role)
    {
        if ($role->users->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete role because it is assigned to users');
        }
        
        $role->delete();
        Artisan::call('cache:clear');
        
        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
