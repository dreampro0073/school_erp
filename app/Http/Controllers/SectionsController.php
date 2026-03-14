<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\ModelHelper;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectionsController extends Controller {
    public function index() {
        return view('admin.sections.index');
    }

    public function initSections(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        
        $sections = Section::get();
        $data["success"] = true;
        $data["sections"] = $sections;
        $data["success"] = true;
        return response()->json($data,200,[]);
    }

    public function storeSection(Request $request) {

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
                $section = Section::find($request->id);
                $data["message"] = "Successfully Stored";
            } else {
                $section = new Section;
                $data["message"] = "Successfully Added";
            }
            $section->name = $request->name;
            $section->status = $request->status;
            $section->created_at = date("Y-m-d H:i:s");
            $section->save();

            $data["success"] = true;
        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return response()->json($data,200,[]);
    }

}
