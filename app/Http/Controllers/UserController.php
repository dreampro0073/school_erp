<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Redirect, Validator, Hash, Response, DB,DateTime;

use App\Models\User;
use Crypt;

use Dompdf\Dompdf;
use Dompdf\Options;

class UserController extends Controller {
    public function makePass(){
        return Hash::make("Vik@s@2018");
    }
    public function webCam(){
        return view('web_cam');
    }

    public function index(){
        return Redirect::to('admin/dashboard');
        
        return view('index');
    }

    public function users(){
        $sidebar = 'users'; 
        return view('admin.users.index',compact('sidebar'));
    }

   
    public function print(){
        return view('admin.print_page');
    }
  
    public function testx(){
        $start_date = "02-01-2024";
    }

    public function login(){   
        return view('login');
    }
    public function notAvailable(){   
        return view('not_available');
    }

    public function postLogin(Request $request){

        $cre = ["email"=>$request->input("email"),"password"=>$request->input("password")];
        $rules = ["email"=>"required","password"=>"required"];
        $validator = Validator::make($cre,$rules);
        
        if($validator->passes()){
            $cre["active"] = 1;
            if(Auth::attempt($cre)){

                $client_id = Auth::user()->client_id;   
                $client = DB::table("clients")->where("id",$client_id)->first();
                $user = User::find(Auth::id());
                $user->last_login = date("Y-m-d H:i:s");
                $user->save();

                if(!$user->api_token){
                    $user->api_token = Hash::make($user->id.strtotime("now"));
                    $user->save();
                }

                DB::table('login_logs')->insert([
                    'client_id'=>$client_id,
                    'user_id'=>$user->id,
                    'login_time'=> date("Y-m-d H:i:s"),
                    'ip'=> $request->ip(),
                ]);

                $currentSessionId = Session::getId();

                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->where('id', '!=', $currentSessionId)
                    ->delete(); 

                // if(Auth::user()->priv == 3 && $request->login_mode == 1){
                // if(Auth::user()->priv == 3){
                //     // dd('hello');
                   
                //     DB::table("login_token")->insert([
                //         "client_id" => $client_id,
                //         "user_id" => Auth::id(),
                //         "created_at" => date("Y-m-d H:i:s"),
                //     ]);

                //     $current_tokens = DB::table("login_token")->where("client_id", $client_id)->count();

                //     if($current_tokens > $client->max_logins){
                        

                //         $lt_user_ids =  DB::table('login_token')->where('client_id',Auth::user()->client_id)->where('id','DESC')->take(2)->pluck('user_id')->toArray();

                //         $user_logs_ids = DB::table('login_token')->where('client_id',Auth::user()->client_id)->whereNotIn('user_id',$lt_user_ids)->pluck('user_id')->toArray();

                //         DB::table('sessions')->whereIn('user_id', $user_logs_ids)->delete();
                        
                //         DB::table("login_token")->whereIn("user_id", $user_logs_ids)->where("client_id", $client_id)->delete();
                //     }

                // }


                if($client){
                    $service_ids = DB::table('client_services')->where("client_id", $client_id)->where('status',1)->pluck('services_id')->toArray();
                    Session::put('client_name',$client->name);
                    Session::put('gst_no',$client->gst);
                    Session::put('service_ids',$service_ids);
                    Session::put('address',$client->address);
                    Session::put('auto_alert_status',0);    
                    Session::put('login_mode',$request->input("login_mode"));    
                    Session::put('print_name',$client->print_name);    
                    
                    $client_ids = [1,2,3,9,10,11,12];
                    Session::put('client_ids',$client_ids);     
                }
                if(Auth::user()->priv == 1){
                    return Redirect::to('/superAdmin/dashboard');
                }else if(Auth::user()->priv == 5){
                    return Redirect::to('/admin/clients/shift-status');
                }else{
                    return Redirect::to('/admin/dashboard');

                }

            } else {
                return Redirect::back()->withInput()->with('failure','Invalid username or password');
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }
    public function setCheckoutAlert(Request $request){
        $session_value = Session::get('auto_alert_status') == 1 ? 0 : 1 ;
        Session::put('auto_alert_status',$session_value);

        $data['success'] = true;
        $data['auto_alert_status'] = Session::get('auto_alert_status');

        return Response::json($data,200,array());
    }

    public function resetPassword(){
        $sidebar = 'change_pass';
        return view('admin.users.reset_password',compact('sidebar'));
    }

    public function updatePassword(Request $request){
        $cre = ["old_password"=>$request->old_password,"new_password"=>$request->new_password,"confirm_password"=>$request->confirm_password];
        $rules = ["old_password"=>'required',"new_password"=>'required|min:5',"confirm_password"=>'required|same:new_password'];
        $old_password = Hash::make($request->old_password);
        $validator = Validator::make($cre,$rules);
        if ($validator->passes()) { 
            if (Hash::check($request->old_password, Auth::user()->password )) {
                $password = Hash::make($request->new_password);
                $user = User::find(Auth::id());
                $user->password = $password;
                $user->password_check = $request->new_password;
                $user->save();
                
                return Redirect::back()->with('success', 'Password changed successfully ');
                
            } else {
                return Redirect::back()->withInput()->with('failure', 'Old password does not match.');
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        return Redirect::back()->withErrors($validator)->withInput()->with('failure','Unauthorised Access or Invalid Password');
    }


    public function initUsers(Request $request){
        $no_of_users = DB::table('users')->where("priv", [2,3])->where("active", '!=', 0)->where("client_id", Auth::user()->client_id)->count();

        $users = DB::table('users')->select('id','name','email','mobile', 'priv', 'active')->whereIn("priv",  [2,3])->where("client_id", Auth::user()->client_id);

        if($request->name){
            $users = $users->where('name','LIKE','%'.$request->name.'%');
        }
        if($request->email){
            $users = $users->where('email','LIKE','%'.$request->email.'%');
        }
        if($request->mobile){
            $users = $users->where('mobile','LIKE','%'.$request->mobile.'%');
        }
        $users = $users->orderBy("priv", "ASC")->orderBy("name", "ASC")->get();
        $add_new_flag = false;

        $client = DB::table("clients")->where("id", Auth::user()->client_id)->first();

        $data["add_new_flag"] = $no_of_users < $client->max_users ? true : false;
        $data['success'] = true;
        $data['users'] = $users;
        
        return Response::json($data, 200, []);
    }

    public function editUser(Request $request){
        $user = User::where('id', $request->user_id)->where("client_id", Auth::user()->client_id)->where("priv", '!=', '4')->first();
        if($user){
            $user->mobile = $user->mobile*1;
        }

        $data['success'] = true;
        $data['user'] = $user;

        return Response::json($data, 200, []);

    }

    public function storeUser(Request $request){


        $cre = [
            'name'=>$request->name,
            'mobile'=>$request->mobile,
            'email'=>$request->email,
        ];

        $rules = [
            'name'=>'required',
            'mobile'=>'required',
            'email'=>'required',
        ];

        if(!$request->has('id')){
            $rules['email'] = 'required|unique:users';
        }

        if(!$request->has('id')){
            $cre['password'] = $request->password;
            $cre['confirm_password'] = $request->confirm_password;

            $rules['password'] = 'required';
            $rules['confirm_password'] = 'required|same:password';

            
        }  

        $validator = Validator::make($cre,$rules);

        if($validator->passes()){
            
            if($request->id){
                $user = User::find($request->id);
                $data['message'] = 'Successfully Updated';

            } else {
                $user = new User;
                $user->password_check = $request->password;
                $user->password = Hash::make($request->password);
                $user->priv = 3;
                $user->client_id = Auth::user()->client_id;
                
                $data['message'] = 'successfully Added';   
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;    
            $user->client_id = Auth::user()->client_id;    
               
            $user->save();
            $data['success'] = true;

        } else {
            $data['success'] = false;
            $message = $validator->errors()->first();
            $data['message'] = $message;
        }

        return Response::json($data, 200, []);

    }
    public function activeUser(Request $request){
        $user = User::where('id', $request->user_id)->where("client_id", Auth::user()->client_id)->first();

        if ($user) {
            $user->active = $user->active == 0 ? 1 : 0;
            $user->save();
            $data['success'] = true;
            $data['message'] ="Successfully Updated !";

        } else {
            $data['success'] = false;
            $data['message'] ="User Not Found !";

        }
        return Response::json($data, 200 ,[]);
    }
    
}