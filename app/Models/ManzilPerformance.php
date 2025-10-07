<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManzilPerformance extends Model
{
    protected $table = "manzil_performances";
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [];
}
