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

        $from_date = $request->from_date ? date("Y-m-d", strtotime($request->from_date)) : date("Y-m-d", strtotime("-7 days"));
        $to_date = $request->to_date ? date("Y-m-d", strtotime($request->to_date)) : date("Y-m-d");
        $user_id = $request->user_id ? $request->user_id : $auth_user->id;

        $worklog = Worklog::selectRaw("worklog.*, DATE_FORMAT(worklog.date, '%d-%m-%Y') as date, users.name")->join("users", "users.id", "=", "worklog.user_id")
        ->where("date", ">=", $from_date)
        ->where("date", "<=", $to_date)
        ->where("worklog.user_id", $user_id)->get();

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

        $day_ids = [];
        if(sizeof($request->day_data) > 0){
            foreach ($request->day_data as $day) {
                if(isset($day["id"]) > 0){
                    $row = Worklog::where("date", date("Y-m-d",  strtotime($request->date)))->where("id", $day['id'])->where("user_id", $user->id)->first();
                } else {
                    $row = new Worklog;
                    $row->date = date("Y-m-d", strtotime($request->date));
                    $row->user_id = $user->id;
                    $row->created_at = date("Y-m-d H:i:s");
                }
                $row->remark = $day["remark"];
                $row->hours = $day["hours"];
                $row->save();
                
                $day_ids[] = $row->id;
            }

        }
        
        $day_data = Worklog::where("date", date("Y-m-d",  strtotime($request->date)))->where("worklog.user_id", $user->id)->whereNotIn("id", $day_ids)->delete();

        $data["success"] = true;

        return response()->json($data,200,[]);
    }
}
