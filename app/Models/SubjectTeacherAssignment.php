<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTeacherAssignment extends Model
{
    use HasFactory;
    protected $fillable = ['section_id', 'subject_id', 'teacher_id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // A subject teacher assignment belongs to a teacher
    public function teacher()
    {
        return $this->belongsTo(Employee::class);
    }
}
