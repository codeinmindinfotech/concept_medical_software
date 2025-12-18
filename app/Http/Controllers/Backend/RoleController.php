<?php
    
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
    
class RoleController extends Controller
{
    public function index(Request $request): View|string
    {
        // Fetch all companies once
        $companies = Company::pluck('name', 'id')->toArray(); // [id => name]

        $query = Role::query();

        if (!has_role('superadmin')) {
            $query->where('company_id', current_company_id());
            if (has_role('consultant')) {
                $query->where('name', '=', 'consultant');
            }
        }
         
        $roles = $query->orderBy('id', 'DESC')->get();

        // Attach company name to each role
        $roles->each(function ($role) use ($companies) {
            $role->company_name = $role->company_id ? ($companies[$role->company_id] ?? 'Unknown') : '---';
        });

        if ($request->ajax()) {
            return view('roles.list', compact('roles'))->render();
        }

        return view(guard_view('roles.index', 'patient_admin.role.index'), compact('roles'));
    }


    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $permission = Permission::get();
        return view(guard_view('roles.create', 'patient_admin.role.create'),compact('permission'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $permissionsID = array_map(
            function($value) { return (int)$value; },
            $request->input('permission')
        );
    
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($permissionsID);
    
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
    
        return view(guard_view('roles.show', 'patient_admin.role.show'),compact('role','rolePermissions'));
    }
    
    public function edit($id): View
    {
        $role = Role::findOrFail($id);

        // Get all permissions for the same guard and company
        $permission = Permission::where('guard_name', $role->guard_name)
                                ->where('company_id', $role->company_id)
                                ->get();

        // Get the permission IDs assigned to this role
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        $companies = Company::all(); // For dropdown

        return view(guard_view('roles.edit', 'patient_admin.role.edit'), compact('role', 'permission', 'rolePermissions', 'companies'));
    }


    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required|array',
        ]);
    
        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->save();
    
        $inputPermissionIDs = array_map('intval', $request->input('permission'));
    
        $permissions = Permission::whereIn('id', $inputPermissionIDs)
            ->where('guard_name', $role->guard_name)
            ->where('company_id', $role->company_id)
            ->get();
    
        // Update role permissions
        $role->syncPermissions($permissions);
    
        // Refresh all models/users with this role
        $models = DB::table('model_has_roles')
            ->where('role_id', $role->id)
            ->get();
    
        foreach ($models as $modelRow) {
            $modelClass = $modelRow->model_type;
            $model = $modelClass::find($modelRow->model_id);
            if ($model) {
                assignRoleToGuardedModel($model, $role->name, $role->guard_name, $role->company_id);
            }
        }
    
        return redirect(guard_route('roles.index'))
                    ->with('success', 'Role updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect(guard_route('roles.index'))
                        ->with('success','Role deleted successfully');
    }
}