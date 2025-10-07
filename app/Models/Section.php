<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
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

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    // public function subjects()
    // {
    //     return $this->hasMany(Subject::class, 'section_id');
    // }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher_assignments')
            ->withPivot('teacher_id')
            ->withTimestamps();
    }


    public function rosters()
    {
        return $this->hasMany(Roster::class);
    }
}
