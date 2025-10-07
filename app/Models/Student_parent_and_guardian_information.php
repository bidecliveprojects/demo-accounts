<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\City;

class Student_parent_and_guardian_information extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [];

    // get active record
    function scopeStatus($query)
    {
       return $query->where('status',1);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function city()
    {
        return $this->hasOne(City::class);
    }
}
