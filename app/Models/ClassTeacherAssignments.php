<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClassTeacherAssignments extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'company_id',
        'company_location_id',
        'section_id',
        'teacher_id'
    ];

    // A subject teacher assignment belongs to a teacher
    public function teacher()
    {
        return $this->belongsTo(Employee::class);
    }


    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::user()->name;
            $model->created_date = date('Y-m-d');
        });
    }
}
