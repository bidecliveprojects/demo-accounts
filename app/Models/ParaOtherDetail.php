<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParaOtherDetail extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [];


    // get active record
    function scopeStatus($query)
    {
       return $query->where('status',1);
    }
}
