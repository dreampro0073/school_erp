<?php

namespace App\Http\Controllers;
use App\Models\AttendanceStatus;
use App\Models\ModelHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TeacherController extends Controller {
    public function dashboard() {
        return view('teachers.dashboard');
    }

    public function examMarksPage() {
        return view('teachers.exam_marks');
    }

    public function initDashboard(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function initExamMarks(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function listExamMarks(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function storeExamMark(Request $request) {
                $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }
}
