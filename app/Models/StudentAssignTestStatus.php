<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssignTestStatus extends Model
{
    public $table = 'student_assign_test_status';
    use HasFactory;


    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
