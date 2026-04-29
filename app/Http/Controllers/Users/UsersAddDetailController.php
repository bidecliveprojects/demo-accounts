<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\CustomPermission;
use App\Models\SubMenu;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UsersAddDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addMainMenuTitleDetail(Request $request)
    {
        $menu_type = $request->input('menu_type');
        $menu_icon = $request->input('menu_icon');
        $title = $request->input('title_name');
        $title_id = preg_replace('/\s+/', '', $title);

        $data1['menu_type'] = $menu_type;
        $data1['menu_icon'] = $menu_icon;
        $data1['menu_name'] = $title;

        Menu::create($data1);
    }

    public function addSubMenuDetail(Request $request)
    {
        $menu_id = $request->input('menu_id');
        $sub_menu_icon = $request->input('sub_menu_icon');
        $sub_menu_name = $request->input('sub_menu_name');
        $url = $request->input('url');
        $sub_menu_type = $request->input('sub_menu_type');

        $data1['menu_id'] = $menu_id;
        $data1['sub_menu_icon'] = $sub_menu_icon;
        $data1['sub_menu_name'] = $sub_menu_name;
        $data1['url'] = $url;
        $data1['sub_menu_type'] = $sub_menu_type;

        $subMenu = SubMenu::create($data1);

        $data['group_id'] = $menu_id;
        $data['sub_menu_id'] = $subMenu->id;
        $data['name'] = $url;
        $data['guard_name'] = 'web';
        CustomPermission::create($data);
    }

    public function addUsersLoginTimePeriodAndPermissionDetail(Request $request)
    {
        $companyIdsRaw = $request->input('company_id_detail');
        if (! is_array($companyIdsRaw)) {
            $companyIdsRaw = ($companyIdsRaw !== null && $companyIdsRaw !== '') ? [$companyIdsRaw] : [];
        }
        $companyIdsRaw = array_values(array_filter($companyIdsRaw, fn ($id) => $id !== null && $id !== ''));
        $request->merge(['company_id_detail' => $companyIdsRaw]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => 'required|string|min:6',
            'account_type' => 'required|string|in:owner,user,superadmin,superuser,client,company,master,parent',
            'mobile_no' => 'required|string|max:20',
            'cnic_no' => 'required|string|max:30',
            'company_id_detail' => 'required|array|min:1',
            'company_id_detail.*' => 'required|numeric|exists:companies,id',
            'roles' => 'nullable|array',
            'roles.*' => 'string|max:255',
        ], [], [
            'username' => 'email',
            'company_id_detail' => 'company',
        ]);

        $companyIds = array_map('strval', $validated['company_id_detail']);
        $companyId = implode('<*>', $companyIds);

        $emp_name = trim($validated['name']);
        $emp_email = strtolower(trim($validated['username']));
        $employee_password = $validated['password'];
        $mobile_no = preg_replace('/\s+/', '', (string) $validated['mobile_no']);
        $cnic_no = preg_replace('/\s+/', '', (string) $validated['cnic_no']);

        $user = User::create([
            'name' => $emp_name,
            'username' => $emp_email,
            'email' => $emp_email,
            'password' => $employee_password,
            'mobile_no' => $mobile_no,
            'cnic_no' => $cnic_no,
            'acc_type' => $validated['account_type'],
            'sgpe' => $emp_name . '<*>' . $employee_password . '<*>' . $emp_email,
            'company_id' => $companyId,
            'status' => 1,
        ]);

        $this->syncAllowedRolesForUser($user, Session::get('company_id'), $validated['roles'] ?? []);

        return Redirect::to('users/viewUsersLoginTimePeriodList')
            ->with('success', 'User created successfully.');
    }

    public function editUsersLoginTimePeriodAndPermissionDetail(Request $request)
    {
        $userId = (int) $request->input('userIds');
        $user = User::findOrFail($userId);

        $companyIdsRaw = $request->input('company_id_detail');
        if (! is_array($companyIdsRaw)) {
            $companyIdsRaw = ($companyIdsRaw !== null && $companyIdsRaw !== '') ? [$companyIdsRaw] : [];
        }
        $companyIdsRaw = array_values(array_filter($companyIdsRaw, fn ($cid) => $cid !== null && $cid !== ''));
        $request->merge(['company_id_detail' => $companyIdsRaw]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => 'nullable|string|min:6',
            'account_type' => 'required|string|in:owner,user,superadmin,superuser,client,company,master,parent',
            'mobile_no' => 'required|string|max:20',
            'cnic_no' => 'required|string|max:30',
            'company_id_detail' => 'required|array|min:1',
            'company_id_detail.*' => 'required|numeric|exists:companies,id',
        ], [], [
            'username' => 'email',
            'company_id_detail' => 'company',
        ]);

        $emp_name = trim($validated['name']);
        $emp_email = strtolower(trim($validated['username']));
        $companyId = implode('<*>', array_map('strval', $validated['company_id_detail']));
        $mobile_no = preg_replace('/\s+/', '', (string) $validated['mobile_no']);
        $cnic_no = preg_replace('/\s+/', '', (string) $validated['cnic_no']);

        $user->name = $emp_name;
        $user->username = $emp_email;
        $user->email = $emp_email;
        $user->mobile_no = $mobile_no;
        $user->cnic_no = $cnic_no;
        $user->acc_type = $validated['account_type'];
        $user->company_id = $companyId;

        $plainPassword = $request->input('password');
        if ($plainPassword !== null && $plainPassword !== '') {
            $user->password = $plainPassword;
            $user->sgpe = $emp_name . '<*>' . $plainPassword . '<*>' . $emp_email;
        } else {
            $parts = explode('<*>', (string) ($user->sgpe ?? ''));
            $user->sgpe = $emp_name . '<*>' . ($parts[1] ?? '') . '<*>' . $emp_email;
        }

        $user->save();

        return Redirect::to('users/viewUsersLoginTimePeriodList')
            ->with('success', 'User updated successfully.');
    }

    /**
     * @param  array<int, string>  $roleNames
     */
    private function syncAllowedRolesForUser(User $user, mixed $companyId, array $roleNames): void
    {
        if ($companyId === null || $companyId === '') {
            return;
        }

        $q = Role::query()->where('company_id', $companyId);
        if (Schema::hasColumn('roles', 'company_location_id')) {
            $loc = Session::get('company_location_id');
            if ($loc !== null && $loc !== '' && is_numeric($loc) && (int) $loc > 0) {
                $q->where('company_location_id', (int) $loc);
            }
        }
        if (Schema::hasColumn('roles', 'status')) {
            $q->where('status', 1);
        }
        $allowed = $q->pluck('name')->all();

        $clean = array_values(array_intersect($roleNames, $allowed));

        $user->syncRoles($clean);
        if (method_exists($user, 'forgetCachedPermissions')) {
            $user->forgetCachedPermissions();
        }
    }
}
