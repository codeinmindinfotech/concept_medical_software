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
use Illuminate\Support\Facades\Cache;

class RoleController extends Controller
{
    public function index(Request $request): View|string
    {
        $roles = Role::with('permissions')->orderBy('id', 'DESC')->paginate(10);

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
            'name' => 'required|string|unique:roles,name',
            'permission' => 'required|array',
        ]);

        $guard = getCurrentGuard(); // your helper or default 'web'

        $role = Role::firstOrCreate(
            ['name' => $request->input('name')],
            ['guard_name' => $guard]
        );

        // Step 2: Assign permissions
        foreach ($request->permission as $permissionId) {
            RolePermission::create([
                'role_id' => $role->id,
                'permission_id' => $permissionId,
            ]);
        }

        return redirect(guard_route('roles.index'))
            ->with('success', 'Role created successfully.');
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

        $permission = Permission::all();

        $rolePermissions = DB::table("role_permissions")
            ->where("role_id", $role->id)
            ->pluck('permission_id')
            ->toArray();

        Cache::forget("permissions_role_{$id}");

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

        // Delete old permission
        DB::table('role_permissions')
            ->where('role_id', $role->id)
            ->delete();
        
        
          
        // Insert new permissions
        $permissionIDs = array_map('intval', array_keys($request->permission));

        foreach ($permissionIDs as $permissionID) {
            DB::table('role_permissions')->insert([
                'role_id' => $role->id,
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
            ->where('role_id', $id)
            ->delete();

        $role->delete();

        return redirect(guard_route('roles.index'))->with('success', 'Role deleted successfully.');
    }

}