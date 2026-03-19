<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\StudentParent;
use App\Models\User;

class Student extends Model
{
    use HasFactory;
    // protected $appends = ['blood_group_name'];
    protected $guarded = [];

    public static function clientStudentsLists($clientId){
        return DB::table("students")->join('users', "users.id", "=", "students.user_id")->where("users.parent_user_id", $clientId)->where("users.priv", 4);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parentUser(){
        return $this->belongsTo(StudentParent::class, 'parent_user_id');
    }

    // public function bloodGroup(){
    //     return $this->belongsTo(MasterData::class, 'blood_group_id');
    // }

    // public function getBloodGroupNameAttribute(){
    //     return $this->bloodGroup->master_name ?? null;
    // }
}