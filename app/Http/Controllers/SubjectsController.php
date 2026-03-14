<?php

namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectsController extends Controller {
    public function index() {
        return view('admin.subjects.index');
    }

    public function initSubjects(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        $subjects = Subject::get();
        $data["subjects"] = $subjects;
        $data["success"] = true;
        return response()->json($data,200,[]);
    }

    public function storeSubject(Request $request) {

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
                $subject = Subject::find($request->id);
                $data["message"] = "Successfully updated";
            } else {
                $subject = new Subject;
                $data["message"] = "Successfully Stored";
            }
            $subject->name = $request->name;
            $subject->status = $request->status;
            $subject->created_at = date("Y-m-d H:i:s");
            $subject->save();

            $data["success"] = true;
        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return response()->json($data,200,[]);
    }
}
