<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\CustomPermission;
use App\Services\MasterDataDeletionGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $segment;

    public function __construct(Request $request)
    {
        $this->segment = $request->segment(1);
        $this->page = 'roles.';
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = '';
        $companyId = $this->resolveCompanyIdForRole();
        $locationId = $this->resolveCompanyLocationIdForRole();

        if ($request->ajax()) {
            $roles = collect();
            if ($companyId > 0) {
                $q = Role::query()->where('company_id', $companyId);
                if (Schema::hasColumn('roles', 'company_location_id')) {
                    if ($locationId !== null && $locationId > 0) {
                        $q->where('company_location_id', $locationId);
                    } else {
                        $q->whereRaw('1 = 0');
                    }
                }
                $roles = $q->orderBy('name')->get();
            }

            return view($this->page . 'indexAjax', compact('roles'));
        }

        return view($this->page . 'index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $all_permissions = CustomPermission::all();
        $permission_groups = CustomPermission::getpermissionGroups();

        return view($this->page . 'create', compact('permission_groups', 'all_permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $companyId = $this->resolveCompanyIdForRole();
        if ($companyId < 1) {
            return redirect()->back()->withInput()->withErrors([
                'name' => 'Company is not selected in session. Choose a company from the header, then create the role again.',
            ]);
        }

        $companyLocationId = $this->resolveCompanyLocationIdForRole();
        $hasLocationColumn = Schema::hasColumn('roles', 'company_location_id');

        if ($hasLocationColumn && ($companyLocationId === null || $companyLocationId < 1)) {
            return redirect()->back()->withInput()->withErrors([
                'name' => 'Company location is not selected. Choose a location from the header, then create the role again.',
            ]);
        }

        $uniqueName = Rule::unique('roles', 'name')->where(function ($query) use ($companyId, $companyLocationId, $hasLocationColumn) {
            $query->where('company_id', $companyId)->where('guard_name', 'web');
            if ($hasLocationColumn) {
                $query->where('company_location_id', $companyLocationId);
            }
        });

        $request->validate([
            'name' => ['required', 'max:100', $uniqueName],
        ], [
            'name.required' => 'Please give a role name',
        ]);

        $payload = [
            'name' => trim((string) $request->name),
            'guard_name' => 'web',
            'company_id' => $companyId,
        ];

        if ($hasLocationColumn) {
            $payload['company_location_id'] = $companyLocationId;
        }

        // Use query()->create so company_id / company_location_id are persisted (Spatie's Role::create()
        // only dedupes by name+guard and passes attributes through, but query()->create is explicit).
        $role = Role::query()->create($payload);

        $permissions = $request->input('permissions');

        if (! empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        if (function_exists('app') && app()->bound(\Spatie\Permission\PermissionRegistrar::class)) {
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return redirect()->route('roles.index')->with('message', 'Role Created Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageUrl = CommonHelper::checkPermissionInnerOptions('roles.index');
        $role = $this->findRoleForCurrentTenant((int) $id);

        if (is_null($role)) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Role Not Found',
            ]);
        }

        $mainTitle = 'Role';
        $pageTitle = 'Edit Role';

        $all_permissions = CustomPermission::all();
        $permission_groups = CustomPermission::getpermissionGroups();

        return view($this->page . 'edit', compact('mainTitle', 'pageTitle', 'permission_groups', 'all_permissions', 'role', 'pageUrl'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $role = $this->findRoleForCurrentTenant((int) $id);
            if (is_null($role)) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Role Not Found',
                ]);
            }

            $hasLoc = Schema::hasColumn('roles', 'company_location_id');

            // Validation Data
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'max:100',
                    Rule::unique('roles', 'name')
                        ->ignore((int) $id)
                        ->where(function ($query) use ($role, $hasLoc) {
                            $query->where('company_id', $role->company_id)->where('guard_name', 'web');
                            if ($hasLoc) {
                                if ($role->company_location_id !== null) {
                                    $query->where('company_location_id', $role->company_location_id);
                                } else {
                                    $query->whereNull('company_location_id');
                                }
                            }
                        }),
                ],
            ], [
                'name.required' => 'Please give a role name',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'warning',
                    'message' => $validator->errors()->first(),
                ]);
            }

            // Update role name
            $role->name = $request->name;
            $role->save();

            // Sync permissions (Fix: Ensure unchecked ones are removed)
            $role->syncPermissions($request->input('permissions', []));

            if (app()->bound(\Spatie\Permission\PermissionRegistrar::class)) {
                app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            }

            return redirect()->route('roles.index')->with('message', 'Role Updated Successfully');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Permanent delete (only when no user has this role; see MasterDataDeletionGuard::assertRoleDeletable).
     */
    public function destroy($id)
    {
        $companyId = $this->resolveCompanyIdForRole();
        $check = MasterDataDeletionGuard::assertRoleDeletable((int) $id, $companyId, $this->resolveCompanyLocationIdForRole());
        if (! $check['ok']) {
            return response()->json([
                'catchError' => $check['message'],
            ]);
        }

        $roleQuery = Role::where('id', $id)->where('company_id', $companyId);
        if (Schema::hasColumn('roles', 'company_location_id')) {
            $locId = $this->resolveCompanyLocationIdForRole();
            if ($locId !== null && $locId > 0) {
                $roleQuery->where('company_location_id', $locId);
            }
        }
        $role = $roleQuery->first();

        if (is_null($role)) {
            return response()->json([
                'catchError' => 'Role not found.',
            ]);
        }

        $role->syncPermissions([]);
        $role->delete();

        if (app()->bound(\Spatie\Permission\PermissionRegistrar::class)) {
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return response()->json(['success' => 'Role deleted permanently.']);
    }

    /**
     * Role must belong to current session company (+ location when column exists).
     */
    protected function findRoleForCurrentTenant(int $id): ?Role
    {
        $companyId = $this->resolveCompanyIdForRole();
        if ($companyId < 1) {
            return null;
        }

        $q = Role::query()->where('id', $id)->where('company_id', $companyId);

        if (Schema::hasColumn('roles', 'company_location_id')) {
            $locId = $this->resolveCompanyLocationIdForRole();
            if ($locId !== null && $locId > 0) {
                $q->where('company_location_id', $locId);
            } else {
                return null;
            }
        }

        return $q->first();
    }

    /**
     * Prefer session company; fall back to first numeric id from the logged-in user's company_id (e.g. "4<*>5").
     */
    protected function resolveCompanyIdForRole(): int
    {
        $raw = Session::get('company_id');
        if ($raw !== null && $raw !== '' && is_numeric($raw)) {
            $id = (int) $raw;
            if ($id > 0) {
                return $id;
            }
        }

        $user = Auth::user();
        if (! $user) {
            return 0;
        }

        $cid = (string) ($user->company_id ?? '');
        $parts = array_values(array_filter(
            explode('<*>', $cid),
            fn ($p) => $p !== '' && ctype_digit((string) $p)
        ));
        if ($parts !== []) {
            return (int) $parts[0];
        }

        if ($cid !== '' && ctype_digit($cid)) {
            return (int) $cid;
        }

        return 0;
    }

    protected function resolveCompanyLocationIdForRole(): ?int
    {
        $raw = Session::get('company_location_id');
        if ($raw !== null && $raw !== '' && is_numeric($raw)) {
            $id = (int) $raw;

            return $id > 0 ? $id : null;
        }

        $user = Auth::user();
        if ($user && $user->company_location_id !== null && $user->company_location_id !== '' && is_numeric($user->company_location_id)) {
            $id = (int) $user->company_location_id;

            return $id > 0 ? $id : null;
        }

        return null;
    }
}
