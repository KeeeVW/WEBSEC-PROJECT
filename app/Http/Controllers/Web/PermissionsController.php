<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class PermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin_users');
    }
    
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }
    
    public function create()
    {
        return view('admin.permissions.create');
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name',
            'display_name' => 'required',
        ]);
        
        Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);
        
        Artisan::call('cache:clear');
        
        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully');
    }
    
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }
    
    public function update(Request $request, Permission $permission)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name,'.$permission->id,
            'display_name' => 'required',
        ]);
        
        $permission->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);
        
        Artisan::call('cache:clear');
        
        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully');
    }
    
    public function destroy(Permission $permission)
    {
        if ($permission->roles->count() > 0) {
            return redirect()->route('permissions.index')
                ->with('error', 'Cannot delete permission because it is assigned to roles');
        }
        
        $permission->delete();
        Artisan::call('cache:clear');
        
        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
}
