<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    protected $table = 'standards';
    public $timestamps = false;

    protected $guarded = [];

    public static function getClientStandardsDrop($client_id){
        return Standard::select('standards.id as value','standards.name as label')->where('standards.client_id',$client_id)->get();
    }
}

