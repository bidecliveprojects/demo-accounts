<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class Head extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable = ['head_name','created_by','created_date'];


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
            $model->created_by = Auth::user()->name;
            $model->created_date = date('Y-m-d');
        });
    }
}
