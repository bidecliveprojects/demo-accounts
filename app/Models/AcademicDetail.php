<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AcademicDetail extends Model
{
    public $timestamps = false;
    protected $table = 'academic_detail';
    use HasFactory;
    protected $fillable = [
        'title','academic_status_id','start_date','end_date'
    ];

    // get active record
    function scopeStatus($query, $status)
    {
        if ($status != '') {
            return $query->where('status', $status);
        }
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::user()->name;
            $model->created_date = date('Y-m-d');
            $model->status = 1;
        });
    }

    public function academicStatus()
    {
        return $this->belongsTo(AcademicStatus::class, 'academic_status_id', 'id');
    }


}
