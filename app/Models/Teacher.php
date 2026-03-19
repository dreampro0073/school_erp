<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';

    protected $guarded = [];

    public static function clientTeachersLists($parent_user_id){
        $select_column = User::selectUsersColumns();
        return DB::table("teachers")
        ->select('teachers.id as teacher_id', "teachers.joining_date", "teachers.dob", "teachers.resign_date",'users.org_id', 'users.name', 'users.email', 'users.mobile', 'users.active', 'users.priv', 'users.parent_user_id', 'users.start_date', 'users.end_date', 'users.last_login', 'users.updated_at', 'users.created_at')
        ->join('users', "users.id", "=", "students.user_id")->where("users.parent_user_id", $parent_user_id)->where("users.priv", 3);
    }
}