<?php
    
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
    
class RoleController extends Controller
{
    public function index(Request $request): View|string
    {
        $roles = Role::orderBy('id', 'DESC')->paginate(10); // You can change pagination
        if ($request->ajax()) {
            return view('roles.list', compact('roles'))->render();
        }
        return view('roles.index',compact('roles'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $permission = Permission::get();
        return view('roles.create',compact('permission'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:role_permissions,role',
            'permission' => 'required|array',
        ]);

        $role = $request->input('name');
        $guard = getCurrentGuard(); // Based on your system

        RolePermission::where('role', $role)
            ->where('guard_name', $guard)
            ->delete();

        // Save new permissions
        foreach ($request->permission as $permissionId) {
            RolePermission::create([
                'role' => $role,
                'guard_name' => $guard,
                'permission_id' => $permissionId,
            ]);
        }
        return redirect(guard_route('roles.index'))
                            ->with('success','Role created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
    
        return view('roles.show',compact('role','rolePermissions'));
    }
    
    public function edit($id): View
    {
        $role = Role::findOrFail($id);

        $permission = Permission::where('guard_name', $role->guard_name)->get();

        $rolePermissions = DB::table("role_permissions")
            ->where("role", $role->name)
            ->where("guard_name", $role->guard_name)
            ->pluck('permission_id')
            ->toArray();

        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }


    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'permission' => 'required|array',
        ]);

        $role = Role::findOrFail($id);

        // Update role name if needed
        $role->update([
            'name' => $request->name,
        ]);

        // Delete old permissions
        DB::table('role_permissions')
            ->where('role', $role->name)
            ->where('guard_name', $role->guard_name)
            ->delete();

        // Insert new permissions
        $permissionIDs = array_map('intval', array_keys($request->permission));

        foreach ($permissionIDs as $permissionID) {
            DB::table('role_permissions')->insert([
                'role' => $role->name,
                'guard_name' => $role->guard_name,
                'permission_id' => $permissionID,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect(guard_route('roles.index'))->with('success', 'Role updated successfully.');
    }


    public function destroy($id): RedirectResponse
    {
        $role = Role::findOrFail($id);

        if (in_array($role->name, ['superadmin'])) {
            return back()->with('error', 'Cannot delete superadmin role.');
        }

        DB::table('role_permissions')
            ->where('role', $role->name)
            ->where('guard_name', $role->guard_name)
            ->delete();

        $role->delete();

        return redirect(guard_route('roles.index'))->with('success', 'Role deleted successfully.');
    }

}