<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\CompanyLocations;


use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    private $companyRepository;

    public function __construct(CompanyRepositoryInterface $countryRepository)
    {
        $this->companyRepository = $countryRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $companies =  $this->companyRepository->allCompanies($request->all());
            return view('companies.indexAjax', compact('companies'));
        }
        return view('companies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('companies.create');
    }

    public function addSchoolAdditionalForm(Request $request){
        $id = $request->get('id');
        $company = Company::where('id',$id)
        ->with(['nazim' => function ($query) {
            $query->get(['emp_name']);
        },])
        ->with(['naibnazim' => function ($query) {
            $query->get(['emp_name']);
        },])
        ->with(['moavin' => function ($query) {
            $query->get(['emp_name']);
        },])
        ->first();
        return view('companies.addSchoolAdditionalForm',compact('company'));
    }

    public function addSchoolAdditionalDetail(Request $request){
        $company_id = $request->input('company_id');
        $nazim_id = $request->input('nazim_id');
        $naib_nazim_id = $request->input('naib_nazim_id');
        $moavin_id = $request->input('moavin_id');

        $data['nazim_id'] = $nazim_id;
        $data['naib_nazim_id'] = $naib_nazim_id;
        $data['moavin_id'] = $moavin_id;

        DB::table('companies')->where('id',$company_id)->update($data);
        return redirect()->route('companies.index')->with('message', 'Company Add Additional Detail Successfully');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'registration_no' => 'required|unique:companies|max:255',
            'company_code' => 'required',
            'name' => 'required|string|max:255',
            'address' => 'required',
            'contact_no' => 'required',
            'school_logo' => ''
        ]);

        Storage::disk('public')->makeDirectory('SchoolLogo');
        $destinationPath = 'storage/app/public/SchoolLogo';
        $school_logo = $request->file('school_logo');

        if(empty($school_logo)){
            $data['school_logo'] = '-';
        }else{
            $schoolLogo = date('YmdHis') . "_1." . $school_logo->getClientOriginalExtension();
            $school_logo->move($destinationPath, $schoolLogo);
            $data['school_logo'] = $destinationPath.'/'.$schoolLogo;
        }

        $this->companyRepository->storeCompany($data);

        return redirect()->route('companies.index')->with('message', 'Company Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $country
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = $this->companyRepository->findCompany($id);

        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'registration_no' => 'required|unique:companies,registration_no,' . $id . '|max:255',
            'company_code' => 'required',
            'name' => 'required|string|max:255',
            'address' => 'required',
            'contact_no' => 'required',
            'school_logo' => ''
        ]);
        $company = $this->companyRepository->findCompany($id);

        Storage::disk('public')->makeDirectory('SchoolLogo');
        $destinationPath = 'storage/app/public/SchoolLogo';
        $school_logo = $request->file('school_logo');

        if(empty($school_logo)){
            $data['school_logo'] = $company->school_logo;
        }else{
            if($company->school_logo != ''){
                if (file_exists($company->school_logo)) {
                    unlink($company->school_logo);
                }
            }
            
            
            $schoolLogo = date('YmdHis') . "_1." . $school_logo->getClientOriginalExtension();
            $school_logo->move($destinationPath, $schoolLogo);
            $data['school_logo'] = $destinationPath.'/'.$schoolLogo;
        }

        $this->companyRepository->updateCompany($data, $id);

        return redirect()->route('companies.index')->with('message', 'Company Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function status($id){
        $this->companyRepository->changeCompanyStatus($id,2);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id){
        $this->companyRepository->changeCompanyStatus($id,1);
        return response()->json(['success' => 'Active Successfully!']);
    }

    public function loadCompanies(Request $request){
        $filterSchoolMainScreenId = $request->input('filterSchoolMainScreenId');
        $accType = $request->input('accType');
        if(empty($filterSchoolMainScreenId)){
            if($accType == 'client'){
                $companiesList = DB::Connection('mysql')->table('companies')->select(['name','id','dbName','company_code','registration_no'])->where('status','=','1')->get();
            }else if($accType == 'superadmin' || $accType == 'superuser' || $accType == 'owner'){
                $checkCompanyId = Auth::user()->company_id;
                $a = explode("<*>",$checkCompanyId);
                $companiesList = DB::Connection('mysql')->table('companies')->select(['name','id','dbName','company_code','registration_no'])->where('status','=','1')->whereIn('id', $a)->get();
            }
        }else{
            $companiesList = DB::Connection('mysql')->table('companies')->select(['name','id','dbName','company_code','registration_no'])->where('status','=','1')->where('id', $filterSchoolMainScreenId)->get();
        }
        $h = static function ($v) {
            return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        };

        $cards = '';
        foreach ($companiesList as $cRow1) {
            $companyId = (int) $cRow1->id;
            $letter = function_exists('mb_substr')
                ? mb_substr((string) $cRow1->name, 0, 1)
                : substr((string) $cRow1->name, 0, 1);
            $cards .= '<a href="#" class="company-picker-card company-picker-card--company" role="button" onclick="loadLocations('.$companyId.'); return false;">';
            $cards .= '<span class="company-picker-avatar theme-bg theme-f-m">'.$h($letter).'</span>';
            $cards .= '<div class="company-picker-card-body">';
            $cards .= '<span class="company-picker-name">'.$h($cRow1->name).'</span>';
            $cards .= '<span class="company-picker-meta"><span class="company-picker-meta-label">Reg.</span> '.$h($cRow1->registration_no).'</span>';
            $cards .= '</div>';
            $cards .= '<span class="company-picker-chevron" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>';
            $cards .= '</a>';
        }

        $empty = $companiesList->isEmpty()
            ? '<div class="company-picker-empty"><i class="fa fa-info-circle" aria-hidden="true"></i> No active companies available.</div>'
            : '';

        $html = '<div class="company-picker" data-step="company">';
        $html .= '<div class="company-picker-stepbar" role="status"><span class="company-picker-step company-picker-step--active"><span class="company-picker-step-num">1</span> Company</span><span class="company-picker-stepsep" aria-hidden="true"></span><span class="company-picker-step"><span class="company-picker-step-num">2</span> Location</span></div>';
        $html .= '<h5 class="company-picker-heading">Choose company</h5>';
        $html .= '<p class="company-picker-lead text-muted">Select an organization. You will choose a campus or location on the next step.</p>';
        $html .= '<div class="company-picker-grid">'.$cards.'</div>'.$empty;
        $html .= '</div>';

        return response($html);
    }

    public function loadLocations(Request $request)
    {
        $schoolId = $request->input('company_id');
        $accType = $request->input('accType');
        $userId = Auth::id(); // Get authenticated user ID
        if($accType == 'client' || $accType == 'owner'){
            $locationsList = DB::table('company_locations as sc')
                ->join('companies as c','sc.company_id','=','c.id')
                ->select('sc.*', 'c.name as company_name', 'c.school_logo', 'c.registration_no', 'c.company_code')
                ->where('company_id',$schoolId)->get();
        }else if($accType == 'superadmin'){
            if(Auth::user()->emp_type_multiple_campus == 1){
                $locationsList = DB::table('company_locations as sc')
                    ->join('companies as c','sc.company_id','=','c.id')
                    ->select('sc.*', 'c.name as company_name', 'c.school_logo', 'c.registration_no', 'c.company_code')
                    ->where('company_id',$schoolId)
                    ->where('sc.id',Auth::user()->company_location_id)
                    ->get();
            }else{
                $schoolCampusIdsArray = Auth::user()->company_location_ids_array;
                
                // Extract company_location_id values
                $ids = array_column(json_decode($schoolCampusIdsArray), 'company_location_id');
                $locationsList = DB::table('company_locations as sc')
                    ->join('companies as c','sc.company_id','=','c.id')
                    ->select('sc.*', 'c.name as company_name', 'c.school_logo', 'c.registration_no', 'c.company_code')
                    ->where('company_id',$schoolId)
                    ->whereIn('sc.id', $ids)
                    ->get();
            }
        }else{
            $locationsList = DB::table('assign_user_campuses as auc')
                ->join('company_locations as sc', 'auc.company_location_id', '=', 'sc.id')
                ->join('companies as c', 'auc.company_id', '=', 'c.id') // Join with companies
                ->where('auc.user_id', $userId)
                ->where('auc.company_id', $schoolId)
                ->select('sc.*', 'c.name as company_name', 'c.school_logo', 'c.registration_no', 'c.company_code') // Include company fields
                ->get();
        }

        $h = static function ($v) {
            return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        };

        $cards = '';
        foreach ($locationsList as $lRow1) {
            $schoolId = urlencode($lRow1->company_id);
            $companyName = urlencode($lRow1->company_name);
            $companyCode = urlencode($lRow1->company_code);
            $campusName = urlencode($lRow1->name);
            $schoolCampusId = urlencode($lRow1->id);
            $url = url("set_user_db_id?company_id={$schoolId}&company_name={$companyName}&company_code={$companyCode}&company_location_id={$schoolCampusId}&company_location_name={$campusName}");

            $letter = function_exists('mb_substr')
                ? mb_substr((string) $lRow1->name, 0, 1)
                : substr((string) $lRow1->name, 0, 1);

            $cards .= '<a href="'.$url.'" class="company-picker-card company-picker-card--location">';
            $cards .= '<span class="company-picker-avatar theme-bg theme-f-m">'.$h($letter).'</span>';
            $cards .= '<div class="company-picker-card-body">';
            $cards .= '<span class="company-picker-name">'.$h($lRow1->name).'</span>';
            $cards .= '<span class="company-picker-meta company-picker-meta--enter"><i class="fa fa-sign-in" aria-hidden="true"></i> Open workspace</span>';
            $cards .= '</div>';
            $cards .= '<span class="company-picker-chevron" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>';
            $cards .= '</a>';
        }

        $empty = $locationsList->isEmpty()
            ? '<div class="company-picker-empty"><i class="fa fa-info-circle" aria-hidden="true"></i> No locations found for this company.</div>'
            : '';

        $html = '<div class="company-picker" data-step="location">';
        $html .= '<div class="company-picker-toolbar">';
        $html .= '<button type="button" class="btn btn-default btn-sm company-picker-back" onclick="loadCompanies(); return false;"><i class="fa fa-arrow-left" aria-hidden="true"></i> Change company</button>';
        $html .= '</div>';
        $html .= '<div class="company-picker-stepbar" role="status"><span class="company-picker-step company-picker-step--done"><span class="company-picker-step-num"><i class="fa fa-check"></i></span> Company</span><span class="company-picker-stepsep" aria-hidden="true"></span><span class="company-picker-step company-picker-step--active"><span class="company-picker-step-num">2</span> Location</span></div>';
        $html .= '<h5 class="company-picker-heading">Choose location</h5>';
        $html .= '<p class="company-picker-lead text-muted">Select the campus or branch to load your data and continue.</p>';
        $html .= '<div class="company-picker-grid">'.$cards.'</div>'.$empty;
        $html .= '</div>';

        return response($html);
    }

    public function loadSchoolCampusDetailDependCampusIds(Request $request){
        $schoolCampusIds = $request->input('company_location_ids');
        $schoolCampusDetails = CompanyLocations::whereIn('id',$schoolCampusIds)->get();
        $data = '<div class="row">';
        foreach($schoolCampusDetails as $scdRow){
            $data .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">'.$scdRow->name.'</div>';
            $data .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Salary</label><input type="number" value="" name="basic_salary_'.$scdRow->id.'" id="basic_salary_'.$scdRow->id.'" class="form-control" /></div>';
        }
        $data .= '</div>';
        echo $data;
    }
}
