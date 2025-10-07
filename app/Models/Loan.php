<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class Loan extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [];

    // get active record
    function scopeStatus($query,$status)
    {
        if($status != ''){
            return $query->where('status',$status);
        }
    }

    // default save username
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->company_id = Session::get('company_id');
            $model->company_location_id = Session::get('company_location_id');
            $model->created_by = Auth::user()->name;
            $model->created_date = date('Y-m-d');
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function school()
    {
        return $this->belongsTo(Blog::class);
    }
}
