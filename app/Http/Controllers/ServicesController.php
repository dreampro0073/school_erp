<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\ModelHelper;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller {
    public function index() {
        return view('admin.services.index');
    }

    public function initServices(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $services = Service::get();
        $data["success"] = true;
        $data["services"] = $services;
        return response()->json($data,200,[]);
    }

    public function storeService(Request $request) {        
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
                $service = Service::find($request->id);
                $data["message"] = "Successfully Stored";
            } else {
                $service = new Service;
                $data["message"] = "Successfully Added";
            }
            $service->name = $request->name;
            $service->status = $request->status;
            $service->created_at = date("Y-m-d H:i:s");
            $service->save();

            $data["success"] = true;
        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return response()->json($data,200,[]);
    }
}
