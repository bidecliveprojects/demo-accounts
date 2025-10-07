<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalActivity extends Model
{
    protected $table = "additional_activities";
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [];
}
