<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Roster extends Model
{
    protected $table = "rosters";
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [

        'company_id',
        'company_location_id',
        'section_id',
        'day_of_week',
        'total_periods',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::user()->name;
            $model->created_date = date('Y-m-d');
        });
    }

    // A roster belongs to a section
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function rosterDetails()
    {
        return $this->hasMany(RosterDetail::class);
    }
}
