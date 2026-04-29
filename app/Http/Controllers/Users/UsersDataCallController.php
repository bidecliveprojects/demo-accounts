<?php

namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use App\Helpers\CommonHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

class UsersDataCallController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

     public function filterUsersLoginTimePeriodAndRolePermissionList()
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(403);
        }

        $authorityUsers = match ($authUser->acc_type) {
            'client' => ['superadmin', 'user', 'superuser', 'owner'],
            'owner' => ['superadmin', 'user', 'superuser'],
            'superadmin', 'superuser' => ['user', 'superuser'],
            default => [],
        };

        $companyIds = array_values(array_filter(
            explode('<*>', (string) ($authUser->company_id ?? '')),
            fn ($id) => $id !== '' && ctype_digit((string) $id)
        ));

        $query = User::with('roles')->whereIn('acc_type', $authorityUsers);

        // company_id is often a single id or "1<*>2"; plain whereIn misses multi-company rows
        if ($authUser->acc_type !== 'client' && count($companyIds) > 0) {
            $query->where(function ($outer) use ($companyIds) {
                foreach ($companyIds as $cid) {
                    $outer->orWhere(function ($q) use ($cid) {
                        $q->where('company_id', (string) $cid)
                            ->orWhere('company_id', 'like', $cid . '<*>%')
                            ->orWhere('company_id', 'like', '%<*>' . $cid . '<*>%')
                            ->orWhere('company_id', 'like', '%<*>' . $cid);
                    });
                }
            });
        }

        $userDetails = $query->orderBy('name')->get();

        $allCompanyIds = $userDetails->pluck('company_id')
            ->flatMap(fn ($c) => array_filter(explode('<*>', (string) $c)))
            ->unique()
            ->filter(fn ($id) => $id !== '' && ctype_digit((string) $id))
            ->values();

        $companyNameMap = $allCompanyIds->isNotEmpty()
            ? DB::table('companies')->whereIn('id', $allCompanyIds->all())->pluck('name', 'id')
            : collect();

        $filterCompanyName = '';
        if ($authUser->acc_type !== 'client') {
            $sessionCompanyId = Session::get('company_id');
            if ($sessionCompanyId !== null && $sessionCompanyId !== '') {
                $filterCompanyName = (string) (DB::table('companies')->where('id', $sessionCompanyId)->value('name') ?? '');
            }
        }

        return view('Users.AjaxPages.filterUsersLoginTimePeriodAndRolePermissionList', compact(
            'userDetails',
            'companyNameMap',
            'filterCompanyName'
        ));
    }

    public function setUserStatus(Request $request)
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'status' => 'required|integer|in:1,2',
        ]);

        $targetId = (int) $validated['user_id'];
        if ($targetId === (int) $authUser->id) {
            return response()->json(['message' => 'You cannot change your own account status.'], 422);
        }

        $target = User::findOrFail($targetId);

        if (! $this->authMayManageUser($authUser, $target)) {
            return response()->json(['message' => 'You are not allowed to change this user.'], 403);
        }

        $target->status = (int) $validated['status'];
        $target->save();

        return response()->json(['message' => 'Updated', 'status' => $target->status]);
    }

    public function assignUserRoles(Request $request)
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(403);
        }

        $id = (int) $request->query('id');
        abort_unless($id > 0, 404);

        $user = User::findOrFail($id);
        if (! $this->authMayManageUser($authUser, $user)) {
            abort(403);
        }

        $companyId = Session::get('company_id');
        $roles = Role::query()
            ->where('company_id', $companyId)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('Users.AjaxPages.assignUserRoles', compact('user', 'roles'));
    }

    public function saveUserRoles(Request $request)
    {
        $authUser = Auth::user();
        if (! $authUser) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'roles' => 'nullable|array',
            'roles.*' => 'string|max:255',
        ]);

        $target = User::findOrFail((int) $validated['user_id']);
        if (! $this->authMayManageUser($authUser, $target)) {
            return response()->json(['message' => 'You are not allowed to change this user.'], 403);
        }

        $companyId = Session::get('company_id');
        $allowedRoleNames = Role::query()
            ->where('company_id', $companyId)
            ->where('status', 1)
            ->pluck('name')
            ->all();

        $requested = array_values(array_unique($validated['roles'] ?? []));
        foreach ($requested as $name) {
            if (! in_array($name, $allowedRoleNames, true)) {
                return response()->json(['message' => 'Invalid role: '.$name], 422);
            }
        }

        $target->syncRoles($requested);
        if (method_exists($target, 'forgetCachedPermissions')) {
            $target->forgetCachedPermissions();
        }

        return response()->json(['message' => 'Roles saved. User will see menu items allowed by these roles.']);
    }

    protected function authMayManageUser(User $authUser, User $target): bool
    {
        if ((int) $authUser->id === (int) $target->id) {
            return false;
        }

        $authorityUsers = match ($authUser->acc_type) {
            'client' => ['superadmin', 'user', 'superuser', 'owner'],
            'owner' => ['superadmin', 'user', 'superuser'],
            'superadmin', 'superuser' => ['user', 'superuser'],
            default => [],
        };

        if (! in_array($target->acc_type, $authorityUsers, true)) {
            return false;
        }

        if ($authUser->acc_type === 'client') {
            return true;
        }

        return $this->targetUserMatchesAuthCompanyScope($authUser, $target);
    }

    protected function targetUserMatchesAuthCompanyScope(User $authUser, User $target): bool
    {
        $companyIds = array_values(array_filter(
            explode('<*>', (string) ($authUser->company_id ?? '')),
            fn ($id) => $id !== '' && ctype_digit((string) $id)
        ));

        if (count($companyIds) === 0) {
            return false;
        }

        $tid = (string) ($target->company_id ?? '');

        foreach ($companyIds as $cid) {
            $cid = (string) $cid;
            if ($tid === $cid) {
                return true;
            }
            if (str_starts_with($tid, $cid . '<*>')) {
                return true;
            }
            if (str_contains($tid, '<*>' . $cid . '<*>')) {
                return true;
            }
            if (str_ends_with($tid, '<*>' . $cid)) {
                return true;
            }
        }

        return false;
    }

    public function loadSchoolCampusDetailDependSchoolDetailId(Request $request)
    {
        $schoolId = $request->input('schoolId');
        $userId = Auth::id(); // Simplified user ID retrieval

        // Retrieve the campus details with a more readable query
        $assignUserCampusesDetail = DB::table('assign_user_campuses as auc')
            ->join('company_locations as sc', 'auc.company_location_id', '=', 'sc.id')
            ->where('auc.user_id', $userId)
            ->where('auc.company_id', $schoolId)
            ->select('sc.id', 'sc.name')
            ->get();


        // Build the options string using array_map for cleaner code
        $options = $assignUserCampusesDetail->map(function ($campus) {
            return "<option value=\"{$campus->id}\">{$campus->name}</option>";
        })->implode('');

        Log::info($options);

        return $options;
    }

    public function makeFormAssignSchoolAndSchoolCampusSection(Request $request){
        $schoolId = $request->input('m');
        $id = $request->input('id');
        $accType = $request->input('accType');
        ?>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <input type="hidden" name="assign_school_and_school_campus_section_array[]" id="assign_school_and_school_campus_section_array" value="<?php echo $id?>" />
                <label class="sf-label">Select School</label>
                <select id="school_detail_<?php echo $id?>" class="multiselect-ui form-control" name="company_id_detail_<?php echo $id?>" onchange="loadSchoolCampusDetailDependSchoolDetailId('<?php echo $id?>')" required="required">
                    <option value="">Select School</option>
                    <?php
                        if($accType == 'client'){
                            $companiesList = DB::Connection('mysql')->table('companies')->select(['name','id','dbName'])->where('status','=','1')->get();
                        }else if($accType == 'superadmin'){
                            $checkCompanyId = Auth::user()->company_id;
                            $a = explode("<*>",$checkCompanyId);
                            $companiesList = DB::Connection('mysql')->table('companies')->select(['name','id','dbName'])->where('status','=','1')->whereIn('id', $a)->get();
                        }else if($accType == 'superuser'){
                            $checkCompanyId = Auth::user()->company_id;
                            $a = explode("<*>",$checkCompanyId);
                            $companiesList = DB::Connection('mysql')->table('companies')->select(['name','id','dbName'])->where('status','=','1')->whereIn('id', $a)->get();
                        }
                        foreach($companiesList as $cRow1){
                    ?>
                            <option value="<?php echo $cRow1->id;?>" class="testing" ><?php echo $cRow1->name;?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <label class="sf-label">Select School Campus</label>
                <select id="company_location_id_detail_<?php echo $id?>" class="multiselect-ui form-control" name="company_location_id_detail_<?php echo $id?>[]" multiple="multiple" required="required">
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <input type="button" class="btn btn-xs btn-danger" onclick="removeAssignSchoolAndSchoolCampusSectionRow('<?php echo $id?>')" value="Remove" />
            </div>
        <?php
    }

    public function userEdit(Request $request)
    {
        $id = (int) $request->query('id');
        abort_unless($id > 0, 404);

        $userDetails = User::findOrFail($id);
        $authUser = Auth::user();
        abort_unless(
            $authUser && ($this->authMayManageUser($authUser, $userDetails) || (int) $userDetails->id === (int) $authUser->id),
            403
        );
        $accType = Auth::user()?->acc_type ?? '';
        $m = Session::get('company_id');
        $companyIdExplode = array_filter(explode('<*>', (string) ($userDetails->company_id ?? '')));
        $sgpeParts = explode('<*>', (string) ($userDetails->sgpe ?? ''));
        $userPasswordDetaill = [
            0 => $sgpeParts[0] ?? '',
            1 => $sgpeParts[1] ?? '',
            2 => $sgpeParts[2] ?? '',
        ];
        $pageType = $request->query('pageType', '');
        $parentCode = $request->query('parentCode', '');

        return view('Users.AjaxPages.userEdit', compact(
            'userDetails',
            'accType',
            'm',
            'id',
            'companyIdExplode',
            'userPasswordDetaill',
            'pageType',
            'parentCode'
        ));
    }

    public function viewProfile(Request $request)
    {
        $id = (int) $request->query('id');
        abort_unless($id > 0, 404);

        $userDetail = User::findOrFail($id);
        $authUser = Auth::user();
        abort_unless(
            $authUser && ((int) $userDetail->id === (int) $authUser->id || $this->authMayManageUser($authUser, $userDetail)),
            403
        );
        $m = Session::get('company_id');

        return view('Users.AjaxPages.viewProfile', compact('userDetail', 'm'));
    }
}
