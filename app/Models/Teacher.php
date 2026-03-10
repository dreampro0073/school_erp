<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';

    protected $guarded = [];

    // public static function clientTeachersLists($clientId){
    //     return DB::table("teachers")->join('users', "users.id", "=", "students.user_id")->where("users.parent_id", $clientId)->where("users.priv", 3);
    // }
}