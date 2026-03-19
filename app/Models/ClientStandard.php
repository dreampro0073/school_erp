<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientStandard extends Model
{
    protected $table = 'client_standards';

    protected $guarded = [];

    public static function getClientStandardsDrop($client_id){
        return ClientStandard::select('client_standards.id as value','standards.name as label')->leftJoin('standards','standards.id','=','client_standards.standard_id')->where('client_standards.client_id',$client_id)->get();
    }
}

