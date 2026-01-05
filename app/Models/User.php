<?php

namespace App\Models;

use DB, Session, Cache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\MailQueue;

class User extends Authenticatable {

    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    //protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function fileExtensions(){
        return array (
            "pdf" , "jpg" , "jpeg", "xls","xlsx" ,"png" , "JPG" ,"JPEG" , "PDF" ,"PNG","XLSX","XLS","csv","CSV","docx","DOCX","pdf","PDF","svg","SVG"
        );
    }

    public static function onlyImages(){
        return array("jpg","JPG","JPEG","jpeg","png","PNG");
    }

    public static function checkHrType(){
        $check = DB::table("clients")->where("id", Auth::user()->client_id)->first();
        if($check->rate_type == 2){
            return true;
        } else {
            return false;
        }
    }

    public static function getServiceIds(){
        $service_ids = DB::table('client_services')->where("client_id", Auth::user()->client_id)->where('status',1)->pluck('services_id')->toArray();

        return $service_ids;
    }

    public static function getRandPassword(){
        $string1 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $string2 = "abcdefghijklmnopqrstuvwxyz";
        $string3 = "0123456789";
        $string4 = "$#@*^%";
        $string5 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789$#@*^%";

        $n = rand(0, strlen($string1) - 1);
        $rand_pwd =  $string1[$n];

        for ($i=0; $i < 2; $i++) { 
            $n = rand(0, strlen($string2) - 1);
            $rand_pwd .=  $string2[$n];
        }

        $n = rand(0, strlen($string3) - 1);
        $rand_pwd .=  $string3[$n];

        $n = rand(0, strlen($string4) - 1);
        $rand_pwd .=  $string4[$n];

        for ($i=0; $i < 3; $i++) { 
            $n = rand(0, strlen($string5) - 1);
            $rand_pwd .=  $string5[$n];
        }

        return $rand_pwd;
    }

    public static function AuthenticateUser($api_token){
        if(!$api_token || $api_token == NULL){
            die("user not found");
        } else {
            $user = User::where('api_token',$api_token)->first();
            if($user){
                return $user;
            }else{
                die("user not found");
            }
        }
    }
    

        
}
