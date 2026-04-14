<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Question;
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

    public function questionsIndex($subjectId, $topicId)
    {
        $subject = Subject::find($subjectId);
        $topic = Topic::find($topicId);
        return view('aspirant.questions.index', [
            'subject' => $subject,
            'topic' => $topic,
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

    public function initQuestions(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        $subjectId = (int) $request->subject_id;
        $topicId = (int) $request->topic_id;
        $questions = Question::where('subject_id', $subjectId)
            ->where('topic_id', $topicId)
            ->get();

        $data["questions"] = $questions;
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function storeQuestion(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        if(!$user){
            die("Not authorized!");
        }

        $cre["question"] = $request->question;
        $cre["subject_id"] = $request->subject_id;
        $cre["topic_id"] = $request->topic_id;
        $rules["question"] = "required";
        $rules["subject_id"] = "required";
        $rules["topic_id"] = "required";

        $validator = Validator::make($cre, $rules);
        if($validator->passes()){
            if($request->id){
                $question = Question::find($request->id);
                $data["message"] = "Successfully updated";
            } else {
                $question = new Question;
                $data["message"] = "Successfully Stored";
            }
            $question->question = $request->question;
            $question->question_hi = $request->question_hi;
            $question->remarks = $request->remarks;
            $question->opt_a = $request->opt_a;
            $question->opt_b = $request->opt_b;
            $question->opt_c = $request->opt_c;
            $question->opt_d = $request->opt_d;
            $question->answer = $request->answer;
            $question->negative_marks = $request->negative_marks;
            $question->paragraph_id = $request->paragraph_id;
            $question->image_file = $request->image_file;
            $question->total_marks = $request->total_marks ?? 0;
            $question->subject_id = $request->subject_id;
            $question->topic_id = $request->topic_id;
            $question->created_at = date("Y-m-d H:i:s");
            $question->save();

            $data["success"] = true;
        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return response()->json($data,200,[]);
    }
}
