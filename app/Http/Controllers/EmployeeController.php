<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use Storage;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Department;
use App\Models\CompanyLocations;
use Illuminate\Support\Facades\Auth;

use Yajra\DataTables\DataTables;

use App\Mail\EmployeeCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    private $employeeRepository;
    protected $page;
    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->middleware('auth');
        $this->page = 'HR.employees.';
        $this->employeeRepository = $employeeRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companyId = $this->resolveCompanyIdForEmployee();
        $locationId = $this->resolveCompanyLocationIdForEmployee();
        $departments = Department::status(1)
            ->where('company_id', $companyId)
            ->when($locationId !== null && $locationId > 0, fn ($q) => $q->where('company_location_id', $locationId))
            ->get();
        if ($request->ajax()) {
            $employees = $this->employeeRepository->allEmployees(
                $request->all(),
                $companyId,
                $locationId
            );
            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('emp_type', function ($row) {
                    if ($row->emp_type == 1) {
                        return 'Non Teaching Staff';
                    } else if ($row->emp_type == 2) {
                        return 'Teaching Staff';
                    } else if ($row->emp_type == 3) {
                        return 'Nazim';
                    } else if ($row->emp_type == 4) {
                        return 'Naib Nazim';
                    } else {
                        return 'Moavin';
                    }
                })->addColumn('status', function ($row) {
                    $toggleUrl = $row->status == 1
                        ? route('employees.destroy', $row->id)
                        : route('employees.active', $row->id);
                
                    $toggleId = $row->status == 1 ? 'inactive-record' : 'active-record';
                    $isChecked = $row->status == 1 ? 'checked' : '';
                
                    return '
                        <td class="text-center">
                            <div class="hidden-print">
                                <label class="switch">
                                    <input type="checkbox" id="' . $toggleId . '" data-url="' . $toggleUrl . '" data-id="' . $row->id . '" ' . $isChecked . '>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="d-none d-print-inline-block">'
                                . ($row->status == 1 ? 'Active' : 'Inactive') .
                            '</div>
                        </td>';
                })
                
                
                ->addColumn('action', function ($row) {
                    $data = '<td class="text-center hidden-print"><div class="dropdown">
                <button class="btn btn-xs dropdown-toggle theme-btn" type="button" data-toggle="dropdown">Action<span class="caret"></span></button>
                <ul class="dropdown-menu">';
                    $data .= '<li><a onclick="showDetailModelOneParamerter(\'employees/show\', \'' . $row->id . '\', \'View Employee Detail\')"><span class="glyphicon glyphicon-eye-open"></span> View</a></li>';
                    if ($row->status == 1) {
                        $data .= '<li><a href="' . route('employees.edit', $row->id) . '">Edit</a></li>';
                    }
                    $data .= '</ul></div></td>';
                    return $data;
                })
                ->rawColumns(['emp_type', 'action', 'status'])
                ->make(true);
        }
        return view($this->page . 'index', compact('departments'));
    }

    /** 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companyId = $this->resolveCompanyIdForEmployee();
        $locationId = $this->resolveCompanyLocationIdForEmployee();
        $roles = Role::query()
            ->where('company_id', $companyId)
            ->when(Schema::hasColumn('roles', 'company_location_id') && $locationId !== null && $locationId > 0, fn ($q) => $q->where('company_location_id', $locationId))
            ->when(Schema::hasColumn('roles', 'status'), fn ($q) => $q->where('status', 1))
            ->get();
        $cities = DB::table('cities')->get();
        $departments = Department::status(1)
            ->where('company_id', $companyId)
            ->when($locationId !== null && $locationId > 0, fn ($q) => $q->where('company_location_id', $locationId))
            ->get();

        return view($this->page . 'create', compact('roles', 'cities', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loginAccess = (int) $request->input('login_access', 1);

        $rules = [
            'emp_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'emp_type' => 'required|in:1,2',
            'emp_name' => '',
            'emp_father_name' => '',
            'date_of_birth' => '',
            'cnic_no' => '',
            'address' => '',
            'phone_no' => '',
            'maritarial_status' => '',
            'no_of_childern' => '',
            'relative_name' => '',
            'relative_contact_no' => '',
            'relative_address' => '',
            'login_access' => 'required|in:1,2',
            'grace_time' => '',
            'start_time' => '',
            'end_time' => '',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_mobile_no' => 'nullable|string|max:255',
            'guardian_address' => 'nullable|string|max:500',
            'basic_salary' => '',
            'city_id' => '',
            'department_id' => '',
            'date_of_joining' => '',
            'employment_status' => '',
            'job_type' => 'required|in:1,2',
            'cnic_document.*' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'other_document.*' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ];

        if ($loginAccess === 2) {
            $rules['emp_email'] = ['required', 'email:rfc', Rule::unique('users', 'email')];
            $rules['roles'] = 'nullable|array';
            $rules['roles.*'] = 'string|max:255';
        } else {
            $rules['emp_email'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        $schoolId = $this->resolveCompanyIdForEmployee();
        $schoolCampusId = $this->resolveCompanyLocationIdForEmployee();

        if ($schoolId < 1) {
            return redirect()->back()->withInput()->withErrors([
                'login_access' => 'Company is not selected in session. Choose a company from the header, then try again.',
            ]);
        }

        if ($schoolCampusId === null || $schoolCampusId < 1) {
            return redirect()->back()->withInput()->withErrors([
                'login_access' => 'Company location is not selected in session. Choose a location from the header, then try again.',
            ]);
        }

        $schoolCampusIdsArray = [];
        $empIdsArray = [];
        $plainPasswordForMail = null;

        DB::transaction(function () use ($request, $schoolCampusId, $schoolId, &$schoolCampusIdsArray, &$empIdsArray, $loginAccess, &$plainPasswordForMail) {
            $this->processEmployee($request, $schoolCampusId, $schoolId, $schoolCampusIdsArray, $empIdsArray);

            if ($loginAccess === 2) {
                $plainPasswordForMail = $this->createUserForEmployee($request, $empIdsArray, $schoolCampusIdsArray, $schoolId);
            }
        });

        if ($loginAccess === 2 && $plainPasswordForMail !== null) {
            $email = strtolower(trim((string) $request->input('emp_email')));
            if ($email !== '') {
                try {
                    Mail::to($email)->send(new EmployeeCreated($request->input('emp_name'), $plainPasswordForMail));
                } catch (\Throwable $e) {
                    Log::warning('Employee user welcome mail failed: '.$e->getMessage());
                }
            }
        }

        return redirect()->route('employees.index')->with('message', 'Employee Created Successfully');
    }

    // Function to handle employee creation and file uploads
    private function processEmployee($request, $schoolCampusId, $schoolId, &$schoolCampusIdsArray, &$empIdsArray)
    {
        $basicSalary = $request->input('basic_salary_' . $schoolCampusId) ?? $request->input('basic_salary');
        $employeeDetail = $this->employeeStore($request->all(), $schoolId, $schoolCampusId, $basicSalary);

        $registrationNo = $employeeDetail->emp_no;
        $employeeId = $employeeDetail->id;

        $schoolCampusIdsArray[] = ['company_location_id' => $schoolCampusId];
        $empIdsArray[] = ['emp_id' => $employeeId];

        // Handle image upload (Only once)
        $this->uploadImage($request, $registrationNo, $employeeId);

        // Handle documents upload (Multiple documents)
        $this->uploadDocuments($request, $registrationNo, $employeeId);
    }

    private function uploadImage($request, $registrationNo, $employeeId)
    {
        if ($image = $request->file('emp_image')) {
            //Storage::disk('public')->makeDirectory('public/EmployeeDocument/'.$registrationNo.'');
            $destinationPath = 'public/EmployeeDocument/' . $registrationNo . '';

            // Generate a unique file name using the current timestamp
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            // Check if the file already exists and avoid re-uploading it
            $image->storeAs($destinationPath, $profileImage); // Save image

            // Save image path in database
            $data['emp_image'] = $destinationPath . '/' . $profileImage;
            Employee::where('id', $employeeId)->update($data);
        }
    }

    // Function to upload CNIC and other documents
    private function uploadDocuments($request, $registrationNo, $employeeId)
    {
        $employeeDocuments = [];

        // Handle CNIC documents
        if ($request->hasFile('cnic_document')) {
            foreach ($request->file('cnic_document') as $cnicDocument) {
                $this->uploadDocument($cnicDocument, $registrationNo, $employeeId, 1, $employeeDocuments);
            }
        }

        // Handle other documents
        if ($request->hasFile('other_document')) {
            foreach ($request->file('other_document') as $otherDocument) {
                $this->uploadDocument($otherDocument, $registrationNo, $employeeId, 2, $employeeDocuments);
            }
        }

        if (!empty($employeeDocuments)) {
            DB::table('employee_documents')->insert($employeeDocuments);
        }
    }

    private function uploadDocument($document, $registrationNo, $employeeId, $documentType, &$employeeDocuments)
    {

        //Storage::disk('public')->makeDirectory('public/EmployeeDocument/'.$registrationNo.'');
        $documentPath = 'public/EmployeeDocument/' . $registrationNo . '/' . ($documentType == 1 ? 'cnic_document' : 'other_document');
        $fileName = date('YmdHis') . "." . $document->getClientOriginalExtension();

        $document->storeAs($documentPath, $fileName); // Save document

        $employeeDocuments[] = [
            'employee_id' => $employeeId,
            'document_type' => $documentType,
            'document_path' => $documentPath . '/' . $fileName,
            'status' => 1,
            'created_by' => Auth::user()->name,
            'created_date' => date('Y-m-d'),
        ];
    }

    /**
     * Creates the portal user for an employee with login access.
     *
     * @return string Plain password for welcome email (caller sends mail after commit).
     */
    private function createUserForEmployee(Request $request, array $empIdsArray, array $schoolCampusIdsArray, int $schoolId): string
    {
        $plainPassword = Str::random(12);
        $email = strtolower(trim((string) $request->input('emp_email')));
        $empName = trim((string) $request->input('emp_name'));

        $campusLocationId = $schoolCampusIdsArray[0]['company_location_id'] ?? null;

        $user = User::create([
            'emp_type_multiple_campus' => (int) $request->input('multiple_school_campus', 1),
            'emp_id' => $empIdsArray[0]['emp_id'],
            'emp_ids_array' => json_encode($empIdsArray),
            'acc_type' => 'user',
            'company_id' => (string) $schoolId,
            'company_location_id' => $campusLocationId !== null && $campusLocationId !== '' ? (int) $campusLocationId : null,
            'company_location_ids_array' => json_encode($schoolCampusIdsArray),
            'mobile_no' => $request->input('phone_no'),
            'cnic_no' => $request->input('cnic_no'),
            'name' => $empName,
            'email' => $email,
            'password' => $plainPassword,
            'username' => $email,
            'sgpe' => $empName.'<*>'.$plainPassword.'<*>'.$email,
            'status' => 1,
        ]);

        $this->syncEmployeeRoles($user, $request->input('roles', []));

        return $plainPassword;
    }

    private function employeeStore($data, $schoolId, $schoolCampusId, $basicSalary)
    {

        $registrationNo = Employee::RegistrationNo($schoolId, $schoolCampusId, $basicSalary);
        $employeeDetail = new Employee();
        $employeeDetail->emp_no = $registrationNo;


        //$employeeDetail = $this->employeeRepository->storeEmployee($data);

        $employeeDetail->company_id = $schoolId;
        $employeeDetail->company_location_id = $schoolCampusId;
        $employeeDetail->emp_type = $data['emp_type'] ?? 1;
        $employeeDetail->city_id = $data['city_id'];
        $employeeDetail->department_id = $data['department_id'];
        $employeeDetail->emp_name = $data['emp_name'];
        $employeeDetail->emp_image = '-';
        $employeeDetail->emp_father_name = $data['emp_father_name'];
        $employeeDetail->date_of_birth = $data['date_of_birth'];
        $employeeDetail->date_of_joining = $data['date_of_joining'];
        $employeeDetail->cnic_no = $data['cnic_no'];
        $employeeDetail->address = $data['address'];
        $employeeDetail->emp_email = $data['emp_email'];
        $employeeDetail->phone_no = $data['phone_no'];

        $employeeDetail->maritarial_status = $data['maritarial_status'];
        $employeeDetail->job_type = $data['job_type'];
        $employeeDetail->employment_status = $data['employment_status'];
        $employeeDetail->no_of_childern = $data['no_of_childern'];
        $employeeDetail->relative_name = $data['relative_name'];
        $employeeDetail->relative_contact_no = $data['relative_contact_no'];
        $employeeDetail->relative_address = $data['relative_address'];
        $employeeDetail->guardian_name = $data['guardian_name'] ?? '';
        $employeeDetail->guardian_mobile_no = $data['guardian_mobile_no'] ?? '';
        $employeeDetail->guardian_address = $data['guardian_address'] ?? '';
        $employeeDetail->login_access = $data['login_access'];
        $employeeDetail->grace_time = $data['grace_time'];
        $employeeDetail->start_time = $data['start_time'];
        $employeeDetail->end_time = $data['end_time'];
        $employeeDetail->basic_salary = $basicSalary;

        $employeeDetail->save();



        $employeeId = $employeeDetail->id;
        // $eedData = array([
        //     'employee_id' => $employeeId,
        //     'hafiz_status' => $data['hafiz_status'] ?? 1,
        //     'memorization_location_for_hafiz' => $data['memorization_location_for_hafiz'] ?? 1,
        //     'teacher_name_for_hafiz' => $data['teacher_name_for_hafiz'] ?? 1,
        //     'taraweeh_recitation' => $data['taraweeh_recitation'] ?? 1,
        //     'tajweed_completion' => $data['tajweed_completion'] ?? 1,
        //     'schooling_completed' => $data['schooling_completed'] ?? 1,
        //     'computer_skills' => $data['computer_skills'] ?? 1,
        //     'writing_skills' => $data['writing_skills'] ?? 1,
        //     'skills_writing' => $data['skills_writing'] ?? 1,
        //     'spiritual_connection' => $data['spiritual_connection'] ?? 1,
        //     'status' => 1,
        //     'created_by' => Auth::user()->name,
        //     'created_date' => date('Y-m-d')
        // ]);
        // DB::table('employee_education_details')->insert($eedData);

        $experienceArray = $data['experienceArray'] ?? [];
        foreach ($experienceArray as $eaRow) {
            $eeData = array([
                'employee_id' => $employeeId,
                'organization_name' => $data['organization_name_' . $eaRow . ''] ?? '-',
                'reason_of_resign' => $data['reason_of_resign_' . $eaRow . ''] ?? '-',
                'duration' => $data['duration_' . $eaRow . ''] ?? '-',
                'status' => 1,
                'created_by' => Auth::user()->name,
                'created_date' => date('Y-m-d')
            ]);
            DB::table('employee_experiences')->insert($eeData);
        }
        return $employeeDetail;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = (int) $request->input('id');
        $this->findEmployeeForCurrentTenant($id);

        $employeeDetail = DB::table('employees as e')
            ->leftJoin('cities as c', 'e.city_id', '=', 'c.id')
            ->leftJoin('departments as d', 'e.department_id', '=', 'd.id')
            ->leftJoin('employee_education_details as eed', 'e.id', '=', 'eed.employee_id')
            ->select('e.*', 'eed.*', 'c.city_name', 'd.department_name')
            ->where('e.id', $id)
            ->first();
        $employeeDocuments = DB::table('employee_documents')->where('employee_id', $id)->get();
        $employeeAllowanceDetail = DB::table('employee_allowance_detail')->where('employee_id', $id)->get();
        $employeeExperiences = DB::table('employee_experiences')->where('employee_id', $id)->get();

        return view($this->page . 'viewEmployeeDetail', compact('employeeDetail', 'employeeDocuments', 'employeeAllowanceDetail', 'employeeExperiences'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = $this->findEmployeeForCurrentTenant((int) $id);
        $companyId = $this->resolveCompanyIdForEmployee();
        $locationId = $this->resolveCompanyLocationIdForEmployee();

        $cities = DB::table('cities')->get();
        $departments = Department::status(1)
            ->where('company_id', $companyId)
            ->when($locationId !== null && $locationId > 0, fn ($q) => $q->where('company_location_id', $locationId))
            ->get();
        $roles = Role::query()
            ->where('company_id', $companyId)
            ->when(Schema::hasColumn('roles', 'company_location_id') && $locationId !== null && $locationId > 0, fn ($q) => $q->where('company_location_id', $locationId))
            ->when(Schema::hasColumn('roles', 'status'), fn ($q) => $q->where('status', 1))
            ->get();
        $employeeEducationDetails = DB::table('employee_education_details')->where('employee_id', $id)->first();
        $employeeExperiences = DB::table('employee_experiences')->where('employee_id', $id)->get();
        $normalAllowance = DB::table('allowance_type as atype')
            ->leftJoin('employee_allowance_detail as ead', function ($join) use ($id) {
                $join->on('atype.id', '=', 'ead.allowance_id')
                    ->where('ead.employee_id', $id);
            })
            ->select('atype.*', 'ead.amount')
            ->where('atype.type', 1)
            ->where('atype.company_id', $companyId)
            ->when($locationId !== null && $locationId > 0, fn ($q) => $q->where('atype.company_location_id', $locationId))
            ->get();
        $additionalAllowance = DB::table('allowance_type as atype')
            ->leftJoin('employee_allowance_detail as ead', function ($join) use ($id) {
                $join->on('atype.id', '=', 'ead.allowance_id')
                    ->where('ead.employee_id', $id);
            })
            ->select('atype.*', 'ead.amount')
            ->where('atype.type', 2)
            ->where('atype.company_id', $companyId)
            ->when($locationId !== null && $locationId > 0, fn ($q) => $q->where('atype.company_location_id', $locationId))
            ->get();

        $portalUser = User::with('roles')->where('emp_id', $id)->first();
        $employeeAssignedRoleNames = $portalUser
            ? $portalUser->roles->pluck('name')->values()->all()
            : [];

        return view($this->page . 'edit', compact(
            'employee',
            'roles',
            'employeeEducationDetails',
            'departments',
            'cities',
            'employeeExperiences',
            'normalAllowance',
            'additionalAllowance',
            'employeeAssignedRoleNames'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $loginAccess = (int) $request->input('login_access', 1);

        $rules = [
            'emp_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'emp_type' => 'required|in:1,2',
            'emp_name' => 'required',
            'emp_father_name' => 'required',
            'date_of_birth' => 'required',
            'cnic_no' => 'required',
            'address' => 'required',
            'emp_email' => 'required',
            'phone_no' => 'required',
            'maritarial_status' => 'required',
            'no_of_childern' => 'required',
            'relative_name' => 'required',
            'relative_contact_no' => 'required',
            'relative_address' => 'required',
            'login_access' => 'required|in:1,2',
            'grace_time' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_mobile_no' => 'nullable|string|max:255',
            'guardian_address' => 'nullable|string|max:500',
            'basic_salary' => 'required',
            'city_id' => 'required',
            'department_id' => 'required',
            'date_of_joining' => 'required',
            'job_type' => 'required|in:1,2',
            'employment_status' => 'nullable|in:1,2',
            'cnic_document.*' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'other_document.*' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ];

        if ($loginAccess === 2) {
            $existingUserId = User::where('emp_id', $id)->value('id');
            $emailUnique = Rule::unique('users', 'email');
            if ($existingUserId) {
                $emailUnique = Rule::unique('users', 'email')->ignore($existingUserId);
            }
            $rules['emp_email'] = ['required', 'email:rfc', $emailUnique];
            $rules['roles'] = 'nullable|array';
            $rules['roles.*'] = 'string|max:255';
        }

        $data = $request->validate($rules);

        $data['emp_type'] = (int) $data['emp_type'];
        $data['guardian_name'] = trim((string) ($data['guardian_name'] ?? ''));
        $data['guardian_mobile_no'] = trim((string) ($data['guardian_mobile_no'] ?? ''));
        $data['guardian_address'] = trim((string) ($data['guardian_address'] ?? ''));

        $employee = $this->findEmployeeForCurrentTenant((int) $id);
        $registrationNo = $employee->emp_no;

        // Handle employee image upload
        if ($request->hasFile('emp_image')) {
            $this->handleImageUpload($request, $employee, $data, $registrationNo);
        }
        // Update employee data
        $this->employeeRepository->updateEmployee($data, $id);

        // Handle document uploads
        $this->handleDocumentsUpload($request, $id, $registrationNo);

        // Update education details
        $this->updateEducationDetails($request, $id);

        // Update experiences
        $this->updateExperiences($request, $id);

        // Handle user login access
        $this->handleUserAccess($request, $id);

        // Handle allowances
        $this->handleAllowances($request, $id);

        return redirect()->route('employees.index')->with('success', 'Employee Updated Successfully');
    }

    private function handleImageUpload(Request $request, $employee, &$data, $registrationNo)
    {
        $emp_image = $request->file('emp_image');
        if ($emp_image) {
            // Delete old image
            if (file_exists($employee->emp_image)) {
                unlink($employee->emp_image);
            }

            // Save new image
            $destinationPath = 'storage/app/public/EmployeeDocument/' . $registrationNo;
            $profileImageName = date('YmdHis') . "." . $emp_image->getClientOriginalExtension();
            $emp_image->move($destinationPath, $profileImageName);
            $data['emp_image'] = $destinationPath . '/' . $profileImageName;
        } else {
            $data['emp_image'] = $employee->emp_image; // Retain existing image if none uploaded
        }
    }

    private function handleDocumentsUpload(Request $request, $employeeId, $registrationNo)
    {
        if ($request->hasFile('cnic_document')) {
            $employeeDocuments = DB::table('employee_documents')->where('employee_id', $employeeId)->where('document_type', 1)->get();

            foreach ($employeeDocuments as $document) {
                if ($document->document_path && file_exists($document->document_path)) {
                    unlink($document->document_path);
                }
                DB::table('employee_documents')->where('id', $document->id)->delete();
            }

            $this->persistUploadedEmployeeDocuments($request->file('cnic_document'), (int) $employeeId, (string) $registrationNo, 1);
        }

        if ($request->hasFile('other_document')) {
            $employeeDocuments = DB::table('employee_documents')->where('employee_id', $employeeId)->where('document_type', 2)->get();

            foreach ($employeeDocuments as $document) {
                if ($document->document_path && file_exists($document->document_path)) {
                    unlink($document->document_path);
                }
                DB::table('employee_documents')->where('id', $document->id)->delete();
            }

            $this->persistUploadedEmployeeDocuments($request->file('other_document'), (int) $employeeId, (string) $registrationNo, 2);
        }
    }

    /**
     * Insert rows into employee_documents from uploaded files (edit flow).
     *
     * @param  array<int, \Illuminate\Http\UploadedFile>|\Illuminate\Http\UploadedFile|null  $files
     */
    private function persistUploadedEmployeeDocuments($files, int $employeeId, string $registrationNo, int $documentType): void
    {
        if ($files === null) {
            return;
        }

        if ($files instanceof \Illuminate\Http\UploadedFile) {
            $files = [$files];
        }

        if ($files === []) {
            return;
        }

        $employeeDocuments = [];
        foreach ($files as $file) {
            if ($file === null) {
                continue;
            }
            $this->uploadDocument($file, $registrationNo, $employeeId, $documentType, $employeeDocuments);
        }

        if ($employeeDocuments !== []) {
            DB::table('employee_documents')->insert($employeeDocuments);
        }
    }

    // private function uploadDocuments($documents, $employeeId, $registrationNo, $documentType)
    // {
    //     if ($documents) {
    //         $directory = "EmployeeDocument/$registrationNo/" . ($documentType === 1 ? 'cnic_document' : 'other_document');
    //         Storage::disk('public')->makeDirectory($directory);

    //         $employeeDocuments = [];
    //         foreach ($documents as $document) {
    //             $imageName = date('YmdHis') . "." . $document->getClientOriginalExtension();
    //             $document->move("storage/app/public/$directory", $imageName);
    //             $employeeDocuments[] = [
    //                 'employee_id' => $employeeId,
    //                 'document_type' => $documentType,
    //                 'document_path' => "storage/app/public/$directory/$imageName",
    //                 'status' => 1,
    //                 'created_by' => Auth::user()->name,
    //                 'created_date' => now()->format('Y-m-d'),
    //             ];
    //         }
    //         DB::table('employee_documents')->insert($employeeDocuments);
    //     }
    // }

    private function updateEducationDetails(Request $request, $id)
    {
        $eedData = $request->only([
            'hafiz_status',
            'memorization_location_for_hafiz',
            'teacher_name_for_hafiz',
            'taraweeh_recitation',
            'tajweed_completion',
            'schooling_completed',
            'computer_skills',
            'writing_skills',
            'skills_writing',
            'spiritual_connection',
        ]);
        $eedData['status'] = 1;
        $eedData['created_by'] = Auth::user()->name;
        $eedData['created_date'] = now()->format('Y-m-d');

        DB::table('employee_education_details')->updateOrInsert(
            ['employee_id' => $id],
            array_merge($eedData, ['employee_id' => $id])
        );
    }

    private function updateExperiences(Request $request, $id)
    {
        DB::table('employee_experiences')->where('employee_id', $id)->delete();

        $experienceArray = $request->input('experienceArray', []);
        foreach ($experienceArray as $eaRow) {
            $eeData = [
                'employee_id' => $id,
                'organization_name' => $request->input("organization_name_$eaRow", '-'),
                'reason_of_resign' => $request->input("reason_of_resign_$eaRow", '-'),
                'duration' => $request->input("duration_$eaRow", '-'),
                'status' => 1,
                'created_by' => Auth::user()->name,
                'created_date' => now()->format('Y-m-d'),
            ];
            DB::table('employee_experiences')->insert($eeData);
        }
    }

    private function handleUserAccess(Request $request, $id)
    {
        if ((int) $request->input('login_access') !== 2) {
            return;
        }

        $companyId = $this->resolveCompanyIdForEmployee();
        $locationId = $this->resolveCompanyLocationIdForEmployee();

        if ($companyId < 1 || $locationId === null || $locationId < 1) {
            return;
        }

        $user = User::where('emp_id', $id)->first();
        $email = strtolower(trim((string) $request->input('emp_email')));
        $empName = trim((string) $request->input('emp_name'));

        if ($user) {
            $user->fill([
                'acc_type' => 'user',
                'mobile_no' => $request->input('phone_no'),
                'cnic_no' => $request->input('cnic_no'),
                'name' => $empName,
                'email' => $email,
                'username' => $email,
                'company_id' => (string) $companyId,
                'company_location_id' => $locationId,
            ]);

            $parts = explode('<*>', (string) ($user->sgpe ?? ''));
            $storedPw = $parts[1] ?? '';
            $user->sgpe = $empName.'<*>'.$storedPw.'<*>'.$email;

            $user->save();

            $this->syncEmployeeRoles($user, $request->input('roles', []));

            return;
        }

        $plainPassword = Str::random(12);
        $newUser = User::create([
            'emp_type_multiple_campus' => (int) $request->input('multiple_school_campus', 1),
            'emp_id' => $id,
            'emp_ids_array' => json_encode([['emp_id' => $id]]),
            'acc_type' => 'user',
            'company_id' => (string) $companyId,
            'company_location_id' => $locationId,
            'company_location_ids_array' => json_encode([['company_location_id' => $locationId]]),
            'mobile_no' => $request->input('phone_no'),
            'cnic_no' => $request->input('cnic_no'),
            'name' => $empName,
            'email' => $email,
            'password' => $plainPassword,
            'username' => $email,
            'sgpe' => $empName.'<*>'.$plainPassword.'<*>'.$email,
            'status' => 1,
        ]);

        $this->syncEmployeeRoles($newUser, $request->input('roles', []));

        try {
            Mail::to($email)->send(new EmployeeCreated($empName, $plainPassword));
        } catch (\Throwable $e) {
            Log::warning('Employee user welcome mail failed on edit: '.$e->getMessage());
        }
    }

    /**
     * @param  array<int, string|mixed>  $roleNames
     */
    private function syncEmployeeRoles(User $user, array $roleNames): void
    {
        $companyId = $this->resolveCompanyIdForEmployee();
        if ($companyId < 1) {
            return;
        }

        $names = array_values(array_filter(array_map('strval', $roleNames), fn ($n) => $n !== ''));

        $q = Role::query()->where('company_id', $companyId);
        if (Schema::hasColumn('roles', 'company_location_id')) {
            $locId = $this->resolveCompanyLocationIdForEmployee();
            if ($locId !== null && $locId > 0) {
                $q->where('company_location_id', $locId);
            }
        }
        if (Schema::hasColumn('roles', 'status')) {
            $q->where('status', 1);
        }
        $allowed = $q->pluck('name')->all();

        $clean = array_values(array_intersect($names, $allowed));

        $user->syncRoles($clean);
        if (method_exists($user, 'forgetCachedPermissions')) {
            $user->forgetCachedPermissions();
        }
    }

    protected function resolveCompanyIdForEmployee(): int
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

    protected function resolveCompanyLocationIdForEmployee(): ?int
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

    private function handleAllowances(Request $request, $id)
    {
        $normalAllowance = $request->input('normalAllowance', []);
        $additionalAllowance = $request->input('additionalAllowance', []);

        // Handle normal allowances
        $this->updateAllowances($normalAllowance, $request, $id, 1);

        // Handle additional allowances
        $this->updateAllowances($additionalAllowance, $request, $id, 2);
    }

    private function updateAllowances(array $allowances, Request $request, $id, $type)
    {
        $companyId = $this->resolveCompanyIdForEmployee();
        $locationId = $this->resolveCompanyLocationIdForEmployee();
        if ($companyId < 1 || $locationId === null || $locationId < 1) {
            return;
        }

        DB::table('employee_allowance_detail')->where('type', $type)
            ->where('company_id', $companyId)
            ->where('employee_id', $id)
            ->delete();

        foreach ($allowances as $allowanceId) {
            $naData = [
                'employee_id' => $id,
                'type' => $type,
                'allowance_id' => $allowanceId,
                'amount' => $request->input("normal_allowance_$allowanceId", 0),
                'status' => 1,
                'created_by' => Auth::user()->name,
                'created_date' => now()->format('Y-m-d'),
                'company_id' => $companyId,
                'company_location_id' => $locationId,
            ];
            DB::table('employee_allowance_detail')->insert($naData);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->findEmployeeForCurrentTenant((int) $id);
        $this->employeeRepository->changeEmployeeStatus($id, 2);

        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id)
    {
        $this->findEmployeeForCurrentTenant((int) $id);
        $this->employeeRepository->changeEmployeeStatus($id, 1);

        return response()->json(['success' => 'Active Successfully!']);
    }

    /**
     * Restrict employee CRUD to current session company + location (tenant isolation).
     */
    protected function findEmployeeForCurrentTenant(int $id): Employee
    {
        $companyId = $this->resolveCompanyIdForEmployee();
        $locationId = $this->resolveCompanyLocationIdForEmployee();

        abort_if($companyId < 1, 403);
        abort_if($locationId === null || $locationId < 1, 403);

        return Employee::query()
            ->where('id', $id)
            ->where('company_id', $companyId)
            ->where('company_location_id', $locationId)
            ->firstOrFail();
    }
}
