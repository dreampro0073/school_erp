<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MasterData extends Model
{
    use HasFactory;

    protected $table = 'master_data';

    protected $guarded = [];

    public static function getMasterData($type){
        return MasterData::select('id as value','master_name as label')->where(['status'=>0,'type'=>$type])->where('parent_master_id','!=',0)->get();
    }
}