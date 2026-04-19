<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\StudentParent,App\Models\Standard,App\Models\Section,App\Models\MasterData;
use App\Models\User;

class Student extends Model
{
    use HasFactory;
    // protected $appends = ['blood_group_name'];
    protected $guarded = [];

    public static function studentId($student_token){
        $studentId = Student::where('unique_id', $student_token)->value('id');
        return $studentId ? $studentId : 0;
    }

    public static function clientStudentsLists($clientId){
        return DB::table("students")->join('users', "users.id", "=", "students.user_id")->where("users.parent_user_id", $clientId)->where("users.priv", 4);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parentUser(){
        return $this->belongsTo(StudentParent::class, 'parent_id');
    }
    public function standard(){
        return $this->belongsTo(Standard::class, 'standard_id');
    }
    public function section(){
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function religion(){
        return $this->belongsTo(MasterData::class, 'religion_id');
    }
    public function cast(){
        return $this->belongsTo(MasterData::class, 'cast_id');
    }

    // public function bloodGroup(){
    //     return $this->belongsTo(MasterData::class, 'blood_group_id');
    // }

    // public function getBloodGroupNameAttribute(){
    //     return $this->bloodGroup->master_name ?? null;
    // }

   
}