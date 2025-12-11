<?php

use App\Models\Company;
use App\Models\PatientDocument;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

if (!function_exists('format_date')) {
    function format_date($date, $format = 'd M Y', $default = '-')
    {
        if (!$date) {
            return $default;
        }

        if ($date instanceof \DateTimeInterface) {
            return Carbon::instance($date)->format($format);
        }

        try {
            return Carbon::parse($date)->format($format);
        } catch (Exception $e) {
            return $default;
        }
    }
}

if (! function_exists('format_time')) {
    function format_time($time, $format = 'H:i', $default = '-')
    {
        if (!$time) return $default;

        try {
            $dt = $time instanceof \DateTimeInterface
                ? Carbon::instance($time)
                : Carbon::parse($time);

            return $dt->format($format);
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path) {
        if (app()->environment('local')) {
            return asset('storage/' . $path);
        } else {
            return asset('storage/' . $path);
        }
    }
}



/**
 * Assign a role (and all its permissions) to a model (User, Doctor, etc.)
 * with proper company_id and guard handling.
 *
 * @param Model $model
 * @param string $roleName
 * @param string $guardName
 * @param int|null $companyId
 * @return void
 * @throws \Exception
 */
if (!function_exists('assignRoleToGuardedModel')) {
    /**
     * Assign a role and its permissions to a model (User, Doctor, Patient, Clinic) for a specific guard and optional company.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $roleName
     * @param string $guardName
     * @param int|null $companyId
     * @throws \Exception
     */

function assignRoleToGuardedModel(Model $model, string $roleName, string $guardName, ?int $companyId = null): void
{
    // Fetch the role for this guard and company
    $roleQuery = Role::where('name', $roleName)
        ->where('guard_name', $guardName);

    if ($companyId !== null) {
        $roleQuery->where('company_id', $companyId);
    } else {
        $roleQuery->whereNull('company_id');
    }

    $role = $roleQuery->first();

    if (!$role) {
        throw new \Exception("Role '{$roleName}' with guard '{$guardName}' and company_id='{$companyId}' not found.");
    }

    // Ensure role has permissions for this guard + company
    if ($role->permissions()->count() === 0) {
        $permissions = Permission::where('guard_name', $guardName)
            ->when($companyId, fn($q) => $q->where('company_id', $companyId), fn($q) => $q->whereNull('company_id'))
            ->get();

        $role->syncPermissions($permissions);
    }

    // Assign role to model via pivot table with company_id
    DB::table('model_has_roles')->updateOrInsert(
        [
            'role_id' => $role->id,
            'model_type' => get_class($model),
            'model_id' => $model->id
        ],
        [
            'company_id' => $companyId
        ]
    );

    // Assign permissions to the model (company-aware)
    $permissionIds = $role->permissions()->pluck('id')->toArray();
    $model->syncPermissions($permissionIds);
}
}


if (! function_exists('getAuthenticatedUserAndCompany')) {
    function getAuthenticatedUserAndCompany(): array
    {
        $guards = array_keys(config('auth.guards'));
        
        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                $user = auth()->guard($guard)->user();
                $companyId = $user->company_id ?? null; // Assuming your User model has company_id
                
                return [
                    'user' => $user,
                    'guard' => $guard,
                    'company_id' => $companyId,
                ];
            }
        }
        
        return [
            'user' => null,
            'guard' => null,
            'company_id' => null,
        ];
    }
}

if (!function_exists('getCurrentGuard')) {
    function getCurrentGuard(): ?string
    {
        $guards = ['web', 'clinic', 'doctor', 'patient'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $guard;
            }
        }
        return null;
    }
}
if (!function_exists('current_user')) {
    function current_user()
    {
        $guard = getCurrentGuard();
        $user = $guard ? Auth::guard($guard)->user() : null;

        if ($user) {
            $companyId = $user->company_id ?? null;
            $user->company_roles = $user->roles()->wherePivot('company_id', $companyId)->get();
            $user->company_permissions = $user->permissions()->wherePivot('company_id', $companyId)->get();
        }

        return $user;
    }
}
if (!function_exists('has_role')) {
    function has_role($role): bool
    {
        $user = current_user();
        return $user && $user->hasCompanyRole($role);
    }
}


if (!function_exists('has_permission')) {
    function has_permission($permission): bool
    {
        $user = current_user();
        return $user && $user->hasCompanyPermission($permission);
    }
}

if (!function_exists('guard_route')) {
    function guard_route(string $baseRoute, $params = []): string
    {
        $guard = getCurrentGuard();
        $user = current_user();

        // Special logic for web guard
        if ($guard === 'web') {
            if ($user && $user->hasRole('superadmin')) {
                return route($baseRoute, $params); // e.g. 'dashboard'
            }

            if ($user && $user->hasRole('manager')) {
                $prefixedRoute = 'manager' . '.' . $baseRoute; // e.g. clinic.dashboard

                if (Route::has($prefixedRoute)) {
                    return route($prefixedRoute, $params);
                }
            }
        }

        // For other guards, use guard-prefixed route
        if ($guard) {
            $prefixedRoute = $guard . '.' . $baseRoute; // e.g. clinic.dashboard

            if (Route::has($prefixedRoute)) {
                return route($prefixedRoute, $params);
            }
        }

        // Default fallback
        return route($baseRoute, $params);
    }
}

if (!function_exists('user_name')) {
    function user_name(): string
    {
        $user = current_user(); 
        return $user?->name ?? $user?->full_name;
    }
}

if (!function_exists('current_company_id')) {
    function current_company_id(): ?int
    {
        $user = current_user();
        $guard = getCurrentGuard();

        if (!$user) {
            return null;
        }

        if ($guard === 'web' && $user->hasRole('superadmin')) {
            return null;
        }

        return $user->company_id ?? null;
    }
}

// if (!function_exists('is_guard_route')) {
//     function is_guard_route(string $path): bool
//     {
//         $user = current_user();
//         $guard = getCurrentGuard(); // 'web', 'doctor', etc.

//         $prefix = '';

//         if ($guard === 'web') {
//             if ($user?->hasRole('manager')) {
//                 $prefix = 'manager/';
//             }
//             // superadmin gets no prefix
//         } elseif ($guard) {
//             $prefix = $guard . '/';
//         }

//         return \Illuminate\Support\Facades\Request::is($prefix . $path . '*');
//     }
// }

function is_guard_route(string $routeName): bool
{
    $currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
    $guard = getCurrentGuard();
    $prefix = '';

    if ($guard === 'web' && current_user()?->hasRole('manager')) {
        $prefix = 'manager.';
    } elseif ($guard !== 'web') {
        $prefix = $guard . '.';
    }

    return str_starts_with($currentRoute, $prefix . $routeName);
}

if (!function_exists('globalNotificationRecipients')) {
    function globalNotificationRecipients(): array
    {
        $emails = \App\Models\Configuration::getValue('global_notification_emails', '');

        // Assuming emails stored as comma separated
        $emails = explode(',', $emails);

        // Trim each email and filter empty
        $emails = array_filter(array_map('trim', $emails));

        return $emails;
    }
}

function getCurrentUserNotifications()
{
    foreach (['doctor', 'patient', 'clinic', 'web'] as $guard) {
        if (auth($guard)->check()) {
            return auth($guard)->user()->unreadNotifications;
        }
    }

    return collect(); // empty if unauthenticated
}

if (!function_exists('isActive')) {
    function isActive($pattern) {
        return Str::is($pattern, request()->route()->getName());
    }
}

if (!function_exists('btnClass')) {
    function btnClass($pattern, $color) {
        return isActive($pattern)
            ? "btn btn-sm w-100 text-start text-white btn-$color fw-semibold shadow-sm"
            : "btn btn-sm w-100 text-start btn-outline-$color";
    }
}

if (!function_exists('isDarkColor')) {
    function isDarkColor($hexColor) {
        $hexColor = str_replace('#', '', $hexColor);
        if (strlen($hexColor) == 3) {
            $r = hexdec(str_repeat(substr($hexColor,0,1), 2));
            $g = hexdec(str_repeat(substr($hexColor,1,1), 2));
            $b = hexdec(str_repeat(substr($hexColor,2,1), 2));
        } else {
            $r = hexdec(substr($hexColor,0,2));
            $g = hexdec(substr($hexColor,2,2));
            $b = hexdec(substr($hexColor,4,2));
        }
        $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
        return $brightness < 128;
    }
}

if (!function_exists('company_path')) {
    function company_path(string $subPath): string
    {
        $companyId = current_company_id() ?? 'common';
        $basePath = "{$companyId}";
        return rtrim($basePath, '/') . '/' . ltrim($subPath, '/');
    }
}

if (!function_exists('guard_view')) {
    /**
     * Return the correct view path based on guard
     *
     * @param string $oldLayout
     * @param string $newLayout
     * @return string
     */
    function guard_view(string $oldLayout, string $newLayout): string
    {
        $guard = getCurrentGuard();

        // Use old layout for superadmin/manager (web guard)
        if ($guard === 'web') {
            return $oldLayout;
        }

        // Use new layout for doctor/clinic/patient
        return $newLayout;
    }
}
if (!function_exists('setProfileImage')) {

    function setProfileImage()
    {
        // Detect guard (doctor or patient)
        if (Auth::guard('doctor')->check()) {
            $user = Auth::guard('doctor')->user();
            $path = "storage/doctor_pictures/{$user->id}/small.jpg";
        }
        elseif (Auth::guard('patient')->check()) {
            $user = Auth::guard('patient')->user();
            $path = "storage/patient_pictures/{$user->id}/small.jpg";
        }
        else {
            return asset('assets/img/doctors-dashboard/profile-06.jpg');
        }

        // Check if file exists
        if (file_exists(public_path($path))) {
            return asset($path);
        }

        // Return your custom default image
        return asset('assets/img/doctors-dashboard/profile-06.jpg');
    }
}
if (!function_exists('hasCompanyPermission')) {
    function hasCompanyPermission(User $user, string $permission)
    {
        $guard = getCurrentGuard();

        // Superadmin: global permissions
        if ($user->hasRole('superadmin', $guard)) {
            return $user->hasPermissionTo($permission); // company_id = null permissions attached to role
        }

        // Normal company user: check permissions scoped by company
        return $user->permissions()
                    ->where('permissions.name', $permission)
                    ->where('permissions.guard_name', $guard)
                    ->where('permissions.company_id', $user->company_id)
                    ->exists();
    }
}

if (!function_exists('setupCompanyRolesAndPermissions')) {
    /**
     * Create company-specific roles and permissions for all guards.
     * If admin user is provided, assign manager role to them.
     *
     * @param Company $company
     * @param User|null $adminUser
     * @return void
     */
    function setupCompanyRolesAndPermissions(Company $company, ?User $adminUser = null): void
    {
        $entities = [
            'role','dropdown','document','dropdownvalue','doctor','company',
            'patient','consultant','insurance','clinic','chargecode',
            'appointment','configuration','notification'
        ];
        $actions = ['list', 'create', 'edit', 'delete'];
        $guards  = ['web','doctor','patient','clinic'];

        // Ensure all permissions exist globally and for this company
        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                foreach ($guards as $guard) {
                    // Global permission
                    Permission::firstOrCreate([
                        'name' => "{$entity}-{$action}",
                        'guard_name' => $guard,
                        'company_id' => null,
                    ]);

                    // Company-specific permission
                    Permission::firstOrCreate([
                        'name' => "{$entity}-{$action}",
                        'guard_name' => $guard,
                        'company_id' => $company->id,
                    ]);
                }
            }
        }

        // Define roles and their permissions
        $rolesPermissions = [
            'manager' => [
                'guard_name' => 'web',
                'permissions' => null, // all permissions
            ],
            'doctor' => [
                'guard_name' => 'doctor',
                'permissions' => [
                    'appointment-list','appointment-create','appointment-edit',
                    'notification-list','notification-create','notification-edit','notification-delete',
                    'patient-list'
                ],
            ],
            'patient' => [
                'guard_name' => 'patient',
                'permissions' => [
                    'appointment-list','appointment-create','appointment-edit',
                    'patient-list','patient-edit',
                    'notification-list','notification-create','notification-edit','notification-delete'
                ],
            ],
            'clinic' => [
                'guard_name' => 'clinic',
                'permissions' => [
                    'doctor-list','doctor-create','patient-list',
                    'appointment-list','appointment-create','appointment-edit',
                    'notification-list','notification-create','notification-edit','notification-delete'
                ],
            ],
        ];

        foreach ($rolesPermissions as $roleName => $config) {
            $guard = $config['guard_name'];

            // Create or get the role
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => $guard,
                'company_id' => $company->id,
            ]);

            // Assign permissions to role
            if ($config['permissions'] === null) {
                // All permissions for this guard & company
                $permissions = Permission::where('guard_name', $guard)
                    ->where(function($q) use ($company) {
                        $q->where('company_id', $company->id)
                          ->orWhereNull('company_id');
                    })->get();
            } else {
                $permissions = [];
                foreach ($config['permissions'] as $permName) {
                    $permissions[] = Permission::firstOrCreate([
                        'name' => $permName,
                        'guard_name' => $guard,
                        'company_id' => $company->id,
                    ]);
                }
            }

            $role->syncPermissions($permissions);

            // Assign manager role to admin user if provided
            if ($roleName === 'manager' && $adminUser) {
                assignRoleToGuardedModel($adminUser, $roleName, $guard, $company->id);
            }
        }
    }
}




