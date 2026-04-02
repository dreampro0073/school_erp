<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    protected $table = 'fee_types';
    public $timestamps = false;

    protected $guarded = [];

    public static function getFeeTypes(){
        return FeeType::select('id as value','name as label')->get();
    }
}

