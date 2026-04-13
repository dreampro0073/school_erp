<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinYear extends Model
{
    protected $table = 'years';
    public $timestamps = false;

    protected $guarded = [];


    public static function getFinYears(){
       return FinYear::select("period as label", "year as value")->get();
    }

  
}

