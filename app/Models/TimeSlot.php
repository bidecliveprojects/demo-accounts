<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;
use Session;
class TimeSlot extends Model
{
    protected $table = "time_slots";
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [

        'company_id',
        'company_location_id',
        'section_id',
        'period_number',
        'start_time',
        'end_time'


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
        });
    }

    


}
