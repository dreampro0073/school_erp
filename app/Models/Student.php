<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function clientStudentsLists($clientId){
        return DB::table("students")->join('users', "users.id", "=", "students.user_id")->where("users.parent_id", $clientId)->where("users.priv", 4);
    }
}