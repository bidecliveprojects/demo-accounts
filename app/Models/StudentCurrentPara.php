<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class StudentCurrentPara extends Model
{
    protected $table = "student_current_paras";
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [];

    // get active record
    function scopeStatus($query)
    {
       return $query->where('status',1);
    }
}
