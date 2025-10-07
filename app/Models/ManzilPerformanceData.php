<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManzilPerformanceData extends Model
{
    protected $table = "manzil_performance_datas";
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [];
}
