<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\ModelHelper;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StandardsController extends Controller {
    public function index()
    {
        return view('admin.standards.index');
    }

    public function initStandards(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $standards = Standard::get();
        $data["success"] = true;
        $data["standards"] = $standards;
        return response()->json($data,200,[]);
    }

    public function storeStandard(Request $request) {        
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        if(!$user){
            die("Not authorized!");
        }

        $cre["name"] = $request->name;
        $rules["name"] = "required";
        
        $validator = Validator::make($cre, $rules);
        if($validator->passes()){
            if($request->id){
                $standard = Standard::find($request->id);
                $data["message"] = "Successfully Stored";
            } else {
                $standard = new Standard;
                $data["message"] = "Successfully Added";
            }
            $standard->name = $request->name;
            $standard->status = $request->status;
            $standard->created_at = date("Y-m-d H:i:s");
            $standard->save();

            $data["success"] = true;
        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return response()->json($data,200,[]);
    }
}

