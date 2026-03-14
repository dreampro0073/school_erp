<?php

namespace App\Http\Controllers;
use App\Models\FeeType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeeTypesController extends Controller {
    public function index() {
        return view('admin.fee-types.index');
    }

    public function initFeeTypes(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        $fee_types = FeeType::get();
        $data["fee_types"] = $fee_types;
        $data["success"] = true;
        return response()->json($data,200,[]);
    }

    public function storeFeeType(Request $request) {
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
                $fee_type = FeeType::find($request->id);
                $data["message"] = "Successfully updated";
            } else {
                $fee_type = new FeeType;
                $data["message"] = "Successfully stored";
            }
            $fee_type->name = $request->name;
            $fee_type->status = $request->status;
            $fee_type->created_at = date("Y-m-d H:i:s");
            $fee_type->save();

            $data["success"] = true;
        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return response()->json($data,200,[]);
    }
}
