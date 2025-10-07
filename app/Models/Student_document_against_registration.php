<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Student_document_against_registration extends Model
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
}
