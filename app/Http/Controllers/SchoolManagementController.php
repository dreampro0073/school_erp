<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Worklog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Standard, App\Models\Section, DB;

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
        $data["teachers"] = Teacher::where("") ->pluck("period", "year")->toArray();

        $data["success"] = false;
        return response()->json($data,200,[]);
    }

    public function initSchedule($request){
        $data["success"] = true;
        return $data;
    }    

    public function initClasses(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $classes = DB::table("client_standards")->select("client_standards.*", "standards.name as standard_name", "sections.name as section_name")
        ->join("standards", "standards.id", "=", "client_standards.standard_id")
        ->leftJoin("sections", "sections.id", "=", "client_standards.section_id")
        ->where("client_id", $auth_user["client_id"])->where("client_standards.status", 0)->get();
        
        $data["success"] = true;
        $data["classes"] = $classes;
        return $data;
    }

    public function initExams($request){
        $data["success"] = true;
        return $data;
    }    

    public function initResults($request){
        $data["success"] = true;
        return $data;
    }


}
