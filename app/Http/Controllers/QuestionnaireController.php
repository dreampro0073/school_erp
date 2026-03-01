<?php

namespace App\Http\Controllers;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Http\Request;

use DB, Session, Cache, Validator, Auth, Response, App\Models\Indicator, Redirect, App\Models\User, App\Models\Question,App\Models\Subject;


class QuestionnaireController extends Controller {

    // Subjects

    public function subjects(){
        $sidebar = 'questionnaire';
        $subsidebar = 'subjects';
        if(Auth::user()->questionnaire != 1 || Auth::user()->level != 1 || Auth::user()->company_admin != 1){
            die("Not authorized !");
        }

        return view('admin.questionnaire.sub_list',[
            'sidebar'=>$sidebar,
            'subsidebar'=>$subsidebar,
        ]);
    }
    public function subjectsInit(Request $request){
        $subjects = Subject::where('user_id', Auth::user()->parent_user_id)->get();
        $data['success']=true;
        $data['subjects'] =$subjects;
        if ($request->input('sub_id')) {
            $subject= Subject::find($request->input('sub_id'));
            $data['subject'] = $subject;
        }

        return Response::json($data,200,array());
    }

    public function subjectStore(Request $request){
        $cre = [
            'name' =>$request->input('name'),
        ];
        $rules = [
            'name' =>'required',
        ];
        $validator = Validator::make($cre,$rules);
        if ($validator->passes()) {
            if($request->id > 0){
                $subject = Subject::where("id", $request->id)->where('user_id', Auth::user()->parent_user_id)->first();
                $data['message']="Successfully updated";
            } else {
                $subject = new Subject;
                $data['message']= "Successfully Added";
            }
            $subject->user_id = Auth::user()->parent_user_id;
            $subject->name = $request->name;
            $subject->save();
            $data['subject'] = $subject;
            $data['success']=true;
        }else{
            $data['success']= false;
            $data['message'] = $validator->errors()->first();
        }
        return Response::json($data,200,array());
    }
    public function deleteSubjects(Request $request){
        $sub_id = $request->subject_id;
        $subject = Subject::where('id', $sub_id)->where('user_id', Auth::user()->parent_user_id)->first();
        
        if ($subject) {
            $subject->delete();
            $data['success'] = true;
            $data['message'] = 'Deleted successfully';
        }else{
            $data['success'] = false;
            $data['message'] = 'Subject not found!';
        }    
        
        return Response::json($data,200,array());
    }
    public function uploadFile(Request $request){
        $destination = 'public/uploads/';
        
        if($request->media){
            $file = $request->media;
            $ori_name = $file->getClientOriginalName();
            if(!User::checkFileExtention($ori_name)){
                $data['success'] = false;
                $data['message'] = 'Invalid file format';
                return Response::json($data, 200, array());
            }
            $extension = $request->media->getClientOriginalExtension();
            if(in_array($extension, User::fileExtensions())){
                $name = strtotime("now").'.'.strtolower($extension);
                $file = $file->move($destination, $name);
                $data["media"] = $destination.$name;

                $data["success"] = true;
                $data["media_link"] = url($destination.$name);
            }else{
                $data['success'] = false;
                $data['message'] = 'Invalid file format';
            }
        }else{
            $data['success'] = false;
            $data['message'] ='file not found';
        }

        return Response::json($data, 200, array());
    }

    //  Questions

    public function subjectView($subject_id = 0){
        $sidebar = 'questionnaire';
        $subsidebar = 'subjects';
        $questions = DB::table("questions")->select("questions.*","subjects.name as subject_name")->join("subjects","subjects.id","=","questions.subject_id")->where('questions.subject_id', $subject_id)->where('questions.user_id', Auth::user()->parent_user_id)->get();

        $types = Question::questionTypes();

        return view('admin.questionnaire.index',compact('sidebar','subsidebar','questions','types', 'subject_id'));
    }

    public function viewAnswers(Request $request){
        $question = question::find($request->ques_id);
        if($question->question_type != 2){
            $answers = DB::table('answers')->select('answers.answer', 'users.name')->leftJoin('users', 'users.id', '=', 'answers.user_id')->where('question_id', $request->ques_id)->get();
        }


        if($question->question_type == 2){
            $user_ids = DB::table('answers')->where('question_id', $request->ques_id)->pluck('user_id')->toArray();
            $users = User::select('users.id', 'users.name')->whereIn('id', $user_ids)->get();
            $answers = [];
            foreach ($users as $user) { 
                $response = DB::table('answers')->where('answers.user_id', $user->id)->where('question_id', $request->ques_id)->get();           
                $ques_rows = DB::table("question_rows")->where("question_id", $question->id)->where("active", 0)->get();
                $ques_columns = DB::table("question_columns")->where("question_id", $question->id)->where("active", 0)->get();

                $user->rows = $ques_rows; 
                $user->columns = $ques_columns; 
                foreach($response as $answer){
                    if(!$answer->row_id){
                       $user->answer = $answer->answer;
                    } else {
                       $user->{'answer_r'.$answer->row_id.'_c'.$answer->column_id} = $answer->answer;
                    }
                }
                $answers[] = $user;
            }
        } 

        $data['answers'] = $answers;
        $data['question'] = $question;

        return Response::json($data,200,array());
    } 

    public function addQuestion($subject_id, $question_id = 0){
        $sidebar = 'questionnaire';
        $subsidebar = 'subjects';
        if($subject_id == 0){
            die("Invalid Subject");
        }

        return view('admin.questionnaire.add',compact('sidebar','subsidebar','subject_id','question_id'));
    }

    public function questionInit($subject_id, $question_id = 0){

        $question = DB::table("questions")->where('id', $question_id)->where('subject_id', $subject_id)->where('user_id', Auth::user()->parent_user_id)->first();

        if($question_id > 0 ){
            if(!$question){
                $data["success"] = false;
                $data['message'] = "Data Not Found!";
                return Response::json($data,200,array());
            }
        }

        if($question){
            $question->rows = DB::table("question_rows")->where("question_id",$question->id)->where("active",0)->get();
            $question->columns = DB::table("question_columns")->where("question_id",$question->id)->where("active",0)->get();
        }

        $types = Question::questionTypeObject();

        $data["success"] = true;
        $data["question"] = $question;
        $data["types"] = $types;

        return Response::json($data,200,array());
    }

    public function questionSave(Request $request){
        $cre = [
            'question' =>$request->input('question'),
            'subject_id' => $request->input('subject_id'),
        ];
        $rules = [
            'question' =>'required',
            'subject_id' =>'required',
        ];
        $validator = Validator::make($cre,$rules);
        if ($validator->passes()) {
            if($request->id > 0){
                $question = Question::where('id', $request->id)->first();
                $data['message']="Successfully updated";
            } else {
                $question = new Question;
                $data['message']= "Successfully Added";
            }

            $question->user_id = Auth::user()->parent_user_id;
            $question->question = $request->input("question");
            $question->subject_id = $request->input("subject_id");
            $question->type = $request->input("type");
            $question->question_type = $request->input("question_type");
            $question->remarks = $request->input("remarks");
            $question->image_file = ($request->input("image_file")) ? $request->input("image_file") : NULL;
            $question->save();

            DB::table("question_rows")->where("question_id",$question->id)->update(array(
                "active" => 1
            ));
            DB::table("question_columns")->where("question_id",$question->id)->update(array(
                "active" => 1
            ));

            if($request->input("question_type") == 2){
                foreach ($request->input("rows") as $row) {
                    if(isset($row["id"])){
                        DB::table("question_rows")->where("id",$row["id"])->update(array(
                            "row_name" => $row["row_name"],
                            "disabled" => $row["disabled"] ? $row["disabled"] : 0,
                            "active" => 0
                        ));
                    } else {
                        DB::table("question_rows")->insert(array(
                            "row_name" => $row["row_name"],
                            "question_id" => $question->id,
                            "disabled" => $row["disabled"] ? $row["disabled"] : 0,
                            "active" => 0
                        ));
                    }
                }

                foreach ($request->input("columns") as $column) {
                    if(isset($column["id"])){
                        DB::table("question_columns")->where("id",$column["id"])->update(array(
                            "column_name" => $column["column_name"],
                            "disabled" => $column["disabled"] ? $column["disabled"] : 0,
                            "active" => 0
                        ));
                    } else {
                        DB::table("question_columns")->insert(array(
                            "column_name" => $column["column_name"],
                            "question_id" => $question->id,
                            "disabled" => $column["disabled"] ? $column["disabled"] : 0,
                            "active" => 0
                        ));
                    }
                }
            }


            $data["success"] = true;
        } else {
            $data["success"] = false;
            $data['message']= $validator->errors()->first();
        }

        return Response::json($data,200,array());

    }

    public function deleteQuestion($question_id){
        $question = Question::where('id', $question_id)->where('user_id', Auth::user()->parent_user_id)->first();
        if ($question) {
            $question->delete();
            $data['success'] = true;
            $data['message'] = 'Question deleted successfully';
        }else{
            $data['success'] = false;
            $data['message'] = 'Question not found';
        }
        return json_encode($data);
    }

    // Answers

    public function answers(){
        if(Auth::user()->questionnaire != 1){
            die("Not authorized !");
        }
        
        $sidebar = 'questionnaire';
        $subsidebar = 'answers';
        $patterns = [
            "date" => "/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",
            "integer" => "/^(0|[1-9][0-9]*)$/",
            "float" => "/^[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)$/",
        ];
        
        return view('admin.questionnaire.answers',compact('sidebar','subsidebar', 'patterns'));
    }

    public function questionsInit(){
        $user_id = Auth::id();
        
        $subject_ids = DB::table('subject_access_list')->where('user_id', $user_id)->pluck('subject_id')->toArray();

        $subjects = DB::table('subjects')->whereIn('id', $subject_ids)->where('user_id', Auth::user()->parent_user_id)->get();

        foreach ($subjects as $subject) {

            $questions = DB::table('questions')->select('questions.id', 'questions.question', 'questions.type','questions.question_type')->where('questions.user_id', Auth::user()->parent_user_id)->where('subject_id', $subject->id)->get();

            foreach ($questions as $ques) {

                $ques_rows = DB::table("question_rows")->where("question_id", $ques->id)->where("active", 0)->get();
                $ques_columns = DB::table("question_columns")->where("question_id", $ques->id)->where("active", 0)->get();

                $ques->rows = $ques_rows; 
                $ques->columns = $ques_columns; 

                $answers = DB::table('answers')->where('answers.question_id', $ques->id)->where('answers.user_id', $user_id)->get();
                foreach($answers as $answer){

                    if(!$answer->row_id){
                        $ques->answer = $answer->answer;
                    } else {
                        $ques->{'answer_r'.$answer->row_id.'_c'.$answer->column_id} = $answer->answer;
                    }

                }
            }

            $subject->questions = $questions;

        }


        $data["success"] = true;
        $data['subjects'] = $subjects;
        return Response::json($data, 200, array());
    }

    public function answersStore(Request $request){
        $user_id = Auth::id();

        $subjects = $request->all();
        if(sizeof($subjects) > 0){
            foreach ($subjects as $subject) {
                if(sizeof($subject['questions']) > 0){
                    
                    foreach ($subject['questions'] as $item) {
                        
                        if($item["question_type"] == 1){

                            $answer = isset($item['answer']) ? $item['answer'] : '';

                            $check = DB::table('answers')->where('user_id', $user_id)->where('question_id', $item['id'])->first();
                            if($check){
                                DB::table('answers')->where('id', $check->id)->update(array(
                                    'answer' => $answer
                                ));    
                            } else {
                                $data = [
                                    'user_id' => $user_id,
                                    'question_id' => $item['id'],
                                    'answer' => $answer
                                ]; 
                                $data['created_at'] = date('Y-m-d h:i:s');
                                DB::table('answers')->insert($data);
                            }

                        } else if($item["question_type"] == 2) {

                            foreach($item["rows"] as $row){
                                foreach($item["columns"] as $column){

                                    if(isset($item['answer_r'.$row["id"]."_c".$column["id"]])){

                                        $answer = $item['answer_r'.$row["id"]."_c".$column["id"]];

                                        $check = DB::table('answers')->where('user_id', $user_id)->where("row_id",$row["id"])->where("column_id",$column["id"])->where('question_id', $item['id'])->first();
                                        if($check){
                                            DB::table('answers')->where('id', $check->id)->update(array(
                                                'answer' => $answer
                                            ));
                                        } else {
                                            $data = [
                                                'user_id' => $user_id,
                                                'question_id' => $item['id'],
                                                'answer' => $answer,
                                                "row_id" => $row["id"],
                                                "column_id" => $column["id"],
                                            ]; 
                                            $data['created_at'] = date('Y-m-d h:i:s');
                                            DB::table('answers')->insert($data);
                                        }

                                    } else {
                                        DB::table('answers')->where('user_id', $user_id)->where("row_id",$row["id"])->where("column_id",$column["id"])->where('question_id', $item['id'])->delete();
                                    }

                                }

                            }

                        }
                    }
                }
            }
        }

        $data["success"] = true;
        $data['message'] = "Successfully Updated !";
        return Response::json($data, 200, array());

    }

    // ***    User Access Start   ***

    public function eligibleUsers(Request $request){
        $eligibleUsers = DB::table("users")->select("id","name", "username")->where('active', 0)->where('parent_user_id', Auth::user()->parent_user_id)->where('questionnaire', 1)->get();

        $selected_ids = DB::table('subject_access_list')->where('subject_id', $request->subject_id)->pluck('user_id')->toArray();

        $data["success"] = true;
        $data['eligibleUsers'] = $eligibleUsers;
        $data['selected_ids'] = $selected_ids;

        return Response::json($data, 200, array());
    }


    public function storeAccess(Request $request){
        $selected_ids = $request->selected_ids;

        DB::table('subject_access_list')->whereNotIn('user_id', $selected_ids)->where('subject_id', $request->subject_id)->delete();

        foreach ($selected_ids as $user_id) {

            $check = DB::table('subject_access_list')->where('user_id', $user_id, )->where('subject_id', $request->subject_id)->first();
            if(!$check){
                DB::table('subject_access_list')->insert([
                    'user_id' => $user_id,
                    'subject_id' => $request->subject_id,
                    'added_by' => Auth::id(),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                Subject::sendSubjectEmail($user_id, $request->subject_id);

            }
        }
          
        $data['message'] = 'Successfully Updated';
        $data['success'] = true; 

        return Response::json($data,200,[]); 

    }

    public function exportSubject(){
        
        $question_ids = [];
        $subjects = DB::table('subjects')->where('user_id', Auth::user()->parent_user_id)->get();
        foreach ($subjects as $subject) {
            $user_ids = DB::table("subject_access_list")->where("subject_id", $subject->id)->pluck("user_id")->toArray();
            $subject->users = User::whereIn("id", $user_ids)->pluck('name', "id")->toArray();
            $questions = DB::table('questions')->select('questions.id', 'questions.question', 'questions.type','questions.question_type')->where('questions.user_id', Auth::user()->parent_user_id)->where('subject_id', $subject->id)->get();
            foreach ($questions as $question) {       
                $question->ques_rows = DB::table("question_rows")->where("question_id", $question->id)->where("active", 0)->get();
                $question->ques_columns = DB::table("question_columns")->where("question_id", $question->id)->where("active", 0)->get();

                $question_ids[] = $question->id;
            }
            $subject->questions = $questions;
        }

        $all_responses = DB::table('answers')->whereIn('question_id', $question_ids)->get();

        $responses = [];
        foreach($all_responses as $response){
            if($response->row_id){
                $responses["r".$response->row_id."_c".$response->column_id."_u".$response->user_id] = $response->answer;
            } else {
                $responses["q".$response->question_id."_u".$response->user_id] = $response->answer;
            }
        }

        include(app_path().'/ExcelExport/questions_export.php');

    }

    function getNameFromNumber($num) {
        $numeric = ($num ) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num ) / 26) - 1;
        if ($num2 >= 0) {
            return $this->getNameFromNumber($num2) . $letter;
        } else {
            return $letter;
        }
    }


    // ***    User Access End   ***

}
