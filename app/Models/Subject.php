<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [];

    // get active record
    function scopeStatus($query, $status)
    {
        if ($status != '') {
            return $query->where('status', $status);
        }
    }


    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher_assignments')
            ->withPivot('section_id')
            ->withTimestamps();
    }

    // Define the relationship to sections
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'subject_teacher_assignments')
            ->withPivot('teacher_id')
            ->withTimestamps();
    }

    public function subjectTeacherAssignments()
    {
        return $this->hasMany(SubjectTeacherAssignment::class);
    }


}
