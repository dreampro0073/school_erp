<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Worklog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Standard, App\Models\Section, App\Models\Teacher, App\Models\Student, DB;

class SchoolManagementController extends Controller {
    public function index(Request $request) {

        return view('admin.school.index');
    }

    public function initSchool(Request $request) {
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $data["standards"] = Standard::where("standards.status", 0)->pluck("name", "id")->toArray();
        $data["sections"] = Section::where("sections.status", 0)->pluck("name", "id")->toArray();
        $data["sessions"] = DB::table('years')->pluck("period", "year")->toArray();
        $data["teachers"] = Teacher::where("school_id", $auth_user->parent_user_id)->pluck("teachers.name", "teachers.id")->toArray();
        $data["students"] = Student::where("school_id", $auth_user->parent_user_id)->pluck("name", "id")->toArray();
        $data["days"] = DB::table("days")->get();

        $data["success"] = true;
        return response()->json($data,200,[]);
    }

    public function initSchedule($request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $schedule = [];
        $data["success"] = true;
        $data["schedule"] = $schedule;
        return response()->json($data,200,[]);
    } 

    public function editSchedule(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $formData = [];
        
        if($formData){
            $data["success"] = true;
            $data["formData"] = $formData;
        } else {
            $data["success"] = true;
            $data["message"] = "Data not found";
        }

        return response()->json($data,200,[]);
    }

    public function scheduleStore(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $request->validate([
            'session_id'   => 'required',
        ]);

        foreach ($variable as $item) {
            if(true){ 
                DB::table('schedule')->update([
                    "updated_at" => date("Y-m-d H:i:s"),
                ]);
            } else {
                DB::table('schedule')->insert([
                    "updated_at" => date("Y-m-d H:i:s"),
                    "created_at" => date("Y-m-d H:i:s"),
                ]);
            }   
        }

        $data["success"] = true;
        $data["message"] = "Updated Successfully";

        return response()->json($data,200,[]);
    }   

    public function initClasses(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $classes = DB::table("client_standards")->select("client_standards.*", "standards.name as standard_name", "sections.name as section_name", "years.period")
        ->join("standards", "standards.id", "=", "client_standards.standard_id")
        ->leftJoin("sections", "sections.id", "=", "client_standards.section_id")
        ->leftJoin("years", "years.year", "=", "client_standards.session_id")
        ->where("client_id", $auth_user["client_id"])->where("client_standards.status", 0)->get();
        
        $data["success"] = true;
        $data["classes"] = $classes;
        return response()->json($data,200,[]);
    }

    public function editClass(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $formData = DB::table('client_standards')->where('id', $request->id)->where("client_id", $auth_user->parent_user_id)->first();
        
        if($formData){
            $data["success"] = true;
            $data["formData"] = $formData;
        } else {
            $data["success"] = true;
            $data["message"] = "Data not found";
        }

        return response()->json($data,200,[]);
    }

    public function classStore(Request $request){

        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $request->validate([
            'standard_id'  => 'required',
            'session_id'   => 'required',
        ]);

        $check = DB::table('client_standards')
        ->where("client_id", $auth_user->parent_user_id)
        ->where("standard_id", $request->standard_id)
        ->where("session_id", $request->session_id)
        ->where("section_id", $request->section_id);

        if($request->id){
            
            $check = $check->where("id", "!=", $request->id)->first();
            
            if($check){
                $data["success"] = false;
                $data["message"] = "This class already exists for the selected session.";
                return response()->json($data,200,[]);

            } else {
                DB::table('client_standards')->where('id', $request->id)->where("client_id", $auth_user->parent_user_id)->where("is_verified", "!=", 1)->update([
                    'standard_id' => $request->standard_id,
                    'section_id' => $request->section_id,
                    'session_id' => $request->session_id,
                    "status" => 0
                ]);

                $data["success"] = true;
                $data["message"] = "Updated Successfully";
            }

        } else {

            $check = $check->first();
            if($check){
                DB::table("client_standards")->where("id", $check->id)->update([
                    "status" => 0,
                    "added_by" => $auth_user->id,
                ]);

                $data["success"] = true;
                $data["message"] = 'Created Successfully';
            } else {
                $id = DB::table('client_standards')->insertGetId([
                    'standard_id'  => $request->standard_id,
                    'section_id'   => $request->section_id,
                    'session_id'   => $request->session_id,
                    'status'       => 0,
                    "is_verified"  => 0,
                    "client_id"  => $auth_user->parent_user_id,
                    "added_by"  => $auth_user->id,
                    "created_at" => date("Y-m-d H:i:s")
                ]);
                

                $data["success"] = true;
                $data["message"] = "Created Successfully";
            }
        }
        return response()->json($data,200,[]);
    }

    public function changeClassStatus(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);
        $check = DB::table('client_standards')->where('id', $request->entry_id)->where("client_id", $auth_user->parent_user_id)->first();

        if($check){
            $status = $request->status;
            if($check->is_verified == 1 && $request->status == -1){
                $status = -2;
            } else if($check->is_verified == -2 && $request->status == 0){
                $status = 1;
            }

            DB::table('client_standards')->where('id', $check->id)->update([
                "is_verified" => $status,
            ]);

            $data["success"] = true;
            $data["message"] = "Successfully Updated";
            $data["status"] = $status;
        } else {
            $data["success"] = true;
            $data["message"] = "Data not found";
        }
        
        return response()->json($data,200,[]);
    }

    public function deleteClass(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);
        $check = DB::table('client_standards')->where('id', $request->entry_id)->where("client_id", $auth_user->parent_user_id)->first();

        if($check){
            DB::table('client_standards')->where('id', $check->id)->update([
                "status" => 1,
            ]);

            $data["success"] = true;
            $data["message"] = "Deleted Successfully";
            $data["status"] = $status;
        } else {
            $data["success"] = true;
            $data["message"] = "Data not found";
        }
        
        return response()->json($data,200,[]);
    }

    public function initExams($request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $exams = [];
        $data["success"] = true;
        $data["exams"] = $exams;
        return $data;
    } 

    public function editExams(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $formData = [];
        
        if($formData){
            $data["success"] = true;
            $data["formData"] = $formData;
        } else {
            $data["success"] = true;
            $data["message"] = "Data not found";
        }

        return response()->json($data,200,[]);
    }

    public function examsStore(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $request->validate([
            'session_id'   => 'required',
        ]);

        foreach ($variable as $item) {
            if(true){ 
                DB::table('exams')->update([
                    "updated_at" => date("Y-m-d H:i:s"),
                ]);
            } else {
                DB::table('exams')->insert([
                    "updated_at" => date("Y-m-d H:i:s"),
                    "created_at" => date("Y-m-d H:i:s"),
                ]);
            }   
        }

        $data["success"] = true;
        $data["message"] = "Updated Successfully"; 
    } 

    public function initResults($request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $results = [];
        $data["success"] = true;
        $data["results"] = $results;
        return $data;
    }

    public function editResult(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $formData = [];
        
        if($formData){
            $data["success"] = true;
            $data["formData"] = $formData;
        } else {
            $data["success"] = true;
            $data["message"] = "Data not found";
        }

        return response()->json($data,200,[]);
    }

    public function resultsStore(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $request->validate([
            'session_id'   => 'required',
        ]);

        foreach ($variable as $item) {
            if(true){ 
                DB::table('exams')->update([
                    "updated_at" => date("Y-m-d H:i:s"),
                ]);
            } else {
                DB::table('exams')->insert([
                    "updated_at" => date("Y-m-d H:i:s"),
                    "created_at" => date("Y-m-d H:i:s"),
                ]);
            }   
        }

        $data["success"] = true;
        $data["message"] = "Updated Successfully"; 
    }


}
