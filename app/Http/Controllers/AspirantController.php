<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AspirantController extends Controller {
    public function dashboard()
    {
        return view('aspirant.dashboard');
    }

    public function subjectsIndex()
    {
        return view('aspirant.subjects.index');
    }

    public function topicsIndex($subjectId)
    {
        $subject = Subject::find($subjectId);
        return view('aspirant.topics.index', [
            'subject' => $subject,
        ]);
    }

    public function initDashboard(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;
        $data["aspirant"] = $user;

        return response()->json($data,200,[]);
    }

    public function initSubjects(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        $subjects = Subject::get();
        $data["subjects"] = $subjects;
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function initTopics(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        $subjectId = (int) $request->subject_id;
        $topics = Topic::where('subject_id', $subjectId)->get();
        $data["topics"] = $topics;
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function storeTopic(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        if(!$user){
            die("Not authorized!");
        }

        $cre["name"] = $request->name;
        $cre["subject_id"] = $request->subject_id;
        $rules["name"] = "required";
        $rules["subject_id"] = "required";

        $validator = Validator::make($cre, $rules);
        if($validator->passes()){
            if($request->id){
                $topic = Topic::find($request->id);
                $data["message"] = "Successfully updated";
            } else {
                $topic = new Topic;
                $data["message"] = "Successfully Stored";
            }
            $topic->name = $request->name;
            $topic->subject_id = $request->subject_id;
            $topic->status = $request->status ?? 0;
            $topic->created_at = date("Y-m-d H:i:s");
            $topic->save();

            $data["success"] = true;
        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return response()->json($data,200,[]);
    }
}
