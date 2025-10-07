<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student_parent_and_guardian_information;
use App\Models\Student_document_against_registration;
use App\Models\Employee;
use App\Models\Department;
use Session;
use DB;

class Student extends Model
{
    protected $table = "students";
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [];

    // get active record
    function scopeStatus($query,$status)
    {
        if($status != ''){
            return $query->where('status',$status);
        }
    }

    public function student_guardian_information()
    {
        return $this->hasOne(Student_parent_and_guardian_information::class);
    }

    public function student_document()
    {
        return $this->hasOne(Student_document_against_registration::class);
    }

    public function department(){
        return $this->hasOne(Department::class);
    }

    public function employee(){
        return $this->hasOne(Employee::class);
    }

    function scopeRegistrationNo($query)
    {
        $id = DB::table($this->table)->where('company_id',Session::get('company_id'))->where('company_location_id',Session::get('company_location_id'))->max('registration_code')+1;
        return  $number = Session::get('company_code').'/'.date('Y').'/'.sprintf('%03d',$id);
    }
}
