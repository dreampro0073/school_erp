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

    public static function authUser($apiToken){
        $user = User::where('api_token',$apiToken)->first();

        if($user){
            return $user;
        }else{
            return "User not found";
        }
    }

   

        
}
