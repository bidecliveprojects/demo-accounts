<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RosterDetail extends Model
{
    use HasFactory;

    protected $table = "roster_details";
    public $timestamps = false;

    protected $fillable = [
        'roster_id',
        'time_slot_id',
        'subject_teacher_assignment_id',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::user()->name;
            $model->created_date = date('Y-m-d');
        });
    }

    public function roster()
    {
        return $this->belongsTo(Roster::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function subjectTeacherAssignment()
    {
        return $this->belongsTo(SubjectTeacherAssignment::class, 'subject_teacher_assignment_id');
    }
}
