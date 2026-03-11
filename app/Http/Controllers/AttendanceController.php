<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\ModelHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AttendanceController extends Controller {
    public function index()
    {
        return view('admin.attendance.index');
    }

    public function init(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function store(Request $request) {
                $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function list(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }
}
