<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;
use DB;

class Employee extends Model
{
    protected $table = "employees";
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [
        // 'company_id',
        // 'company_location_id',
        // 'department_id',
        // 'emp_image',
        // 'emp_no',
        // 'emp_type',
        // 'emp_name',
        // 'emp_father_name',
        // 'date_of_birth',
        // 'cnic_no',
        // 'address',
        // 'emp_email',
        // 'phone_no',
        // 'maritarial_status',
        // 'no_of_childern',
        // 'relative_name',
        // 'relative_contact_no',
        // 'relative_address',
        // 'status',
        // 'created_by',
        // 'created_date',
        // 'login_access',
        // 'grace_time',
        // 'start_time',
        // 'end_time',
        // 'guardian_name',
        // 'guardian_mobile_no',
        // 'guardian_address',
        // 'job_type',
        // 'employment_status',
        // 'basic_salary',
        // 'city_id',
        // 'date_of_joining'
    ];

    // get active record
    function scopeStatus($query, $status)
    {
        if ($status != '') {
            return $query->where('status', $status);
        }
    }

    function scopeRegistrationNo($query, $schoolId, $schoolCampusId)
    {
        //$id = $query->max('id')+1;
        $id = DB::table($this->table)->where('company_id', $schoolId)->where('company_location_id', $schoolCampusId)->max('id') + 1;
        return $number = 'EMP-' . $schoolId . '-' . $schoolCampusId . '-' . sprintf('%03d', $id);
    }

    // default save username
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::user()->name;
            $model->created_date = date('Y-m-d');
        });
    }


    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher_assignments')
            ->withPivot('section_id')
            ->withTimestamps();
    }

    // Define the relationship to sections
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'subject_teacher_assignments')
            ->withPivot('subject_id')
            ->withTimestamps();
    }

    public function subjectTeacherAssignments()
    {
        return $this->hasMany(SubjectTeacherAssignment::class);
    }
}
