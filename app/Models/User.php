<?php

namespace App\Models;

use DB, Session, Cache;

use Illuminate\Http\Request;
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

    public static function authUser($apiToken){
        $user = User::where('api_token',$apiToken)->first();

        if($user){
            return $user;
        }else{
            return "User not found";
        }
    }

    public static function resolveApiUser(Request $request, ?int $requiredPriv = null): ?self
    {
        $apiToken = $request->header('apiToken');
        $user = self::authUser($apiToken);
        if (!$user || is_string($user)) {
            return "User not Fount";
        }

        if ($requiredPriv !== null) {
            $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
            if ($priv !== $requiredPriv) {
                return "Priv not macth";
            }
        }

        return $user;
    }

    public static function isPrivOne($user): bool
    {
        if (!$user) {
            return false;
        }

        $priv = $user->priv ?? $user->privillage ?? $user->privilege ?? null;
        return (int) $priv === 1;
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


    public static function selectUsersColumns(){
        return ['users.id', 'users.org_id', 'users.name', 'users.email', 'users.mobile', 'users.active', 'users.priv', 'users.parent_user_id', 'users.start_date', 'users.end_date', 'users.last_login', 'users.updated_at', 'users.created_at'];
    }


    public static function clientUsersCount($clientId, $priv, $status = "all"){

        $sql = DB::table("users")->where("users.priv", $priv);

        if($clientId != 1){
            $sql = $sql->where("users.parent_user_id", $clientId);
        }        

        if($status != "all"){
            $sql = $sql->where("users.active", $status);
        }
        return $sql;
    }

   

        
}
