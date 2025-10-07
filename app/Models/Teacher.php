<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
class Teacher extends Model
{
    protected $table = "teachers";
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

    function scopeRegistrationNo($query)
    {
        //$id = $query->max('id')+1;
        $id = DB::table($this->table)->max('id')+1;
        return  $number = 'T-'.Session::get('company_id').'-'.sprintf('%03d',$id);
    }
}
