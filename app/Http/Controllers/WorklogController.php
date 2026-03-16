<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Worklog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorklogController extends Controller {
    public function index(Request $request) {
        return view('worklog.index');
    }

    public function initWorkLog(Request $request) {
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $from_date = $request->from_date ? date("Y-m-d", strtotime($request->from_date)) : date("Y-m-d", strtotime(" -7 days"));
        $to_date = $request->to_date ? date("Y-m-d", strtotime($request->to_date)) : date("Y-m-d");
        $user_id = $request->user_id ? $request->user_id : $auth_user->id;

        $worklog = Worklog::select("worklog.*", "users.name")->join("users", "users.id", "=", "worklog.user_id")->whereBetween("date", [$from_date, $to_date])->where("worklog.user_id", $user_id)->get()->keyBy("Worklog.date");
        
        $users = User::select("id", "name")->whereIn("users.priv", [2,3])->where("parent_id", $auth_user->parent_id)->get();

        $data["worklog"] = $worklog;
        $data["users"] = $users;
        $data["success"] = true;
        return response()->json($data,200,[]);
    }

    public function getDayData(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        $day_data = Worklog::select("worklog.*")->where("date", date("Y-m-d",  strtotime($request->date)))->where("worklog.user_id", $user->id)->get();

        $data["success"] = true;
        $data["day_data"] = $day_data;
        return response()->json($data,200,[]);
    }    

    public function store(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }
}
