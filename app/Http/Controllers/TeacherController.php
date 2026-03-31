<?php

namespace App\Http\Controllers;
use App\Models\AttendanceStatus;
use App\Models\ModelHelper;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    // *** Teacher Manage ***
    public function index(){
        return view('admin.teachers.index');
    }

    public function addTeacherPage($teacher_token=0) {
        return view('admin.teachers.form', [
            'teacher_token' => $teacher_token,
        ]);
    }

    public function initTeachers(Request $request) {
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $limit = $request->has('limit')?$request->limit:10;
        $query = Teacher::where('school_id', $user->client_id);
        if($request->search){
            $query->where('first_name', 'like', '%'.$request->search.'%');
        }
        $teachers = $query->orderBy('id', 'DESC')->paginate($limit);

        foreach ($teachers as $teacher) {
            $teacher->dob = date("d-m-Y", strtotime($row->dob));
            $teacher->joining_date = date("d-m-Y", strtotime($row->joining_date));
            $teacher->resign_date = date("d-m-Y", strtotime($row->resign_date));
        }

        return response()->json([
            'success' => true,
            'teachers' => $teachers,
        ]);
    }

    public function storeTeacher(Request $request)
    {
        $authUser = User::resolveApiUser($request);
        $e_teacher = Teacher::where('unique_id',$request->unique_id)->first(); 

        $validator = Validator::make($request->all(), [
            'first_name' => ['required','string','max:255'],
            'last_name' => ['nullable','string','max:255'],
            'gender' => ['required'],
            'dob' => ['required'],
            'mobile' => ['required','digits:10'],
            'email' => 'required|email|unique:users,email',
            'aadhar_no'=> ['required','digits:12'], 
            'residential_address' => ['required'],
            'permanent_address' => ['required'],

            'father_name' => ['required','string','max:255'],
            'mother_name' => ['required','string','max:255'],
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors(),
            ],422);
        }

        DB::beginTransaction();

        try {

            $user = User::where('email',$request->email)->first();
            if(!$user){

                $password = User::getRandPassword();

                $user = new User;
                $user->name = $request->first_name.' '.$request->last_name;
                $user->email = $request->email;
                $user->password = Hash::make($password);
                $user->password_check = $password;
                $user->priv = 3; 
                $user->added_by = $authUser->id;
                $user->org_id = $authUser->org_id;
                $user->client_id = $authUser->client_id;
                $user->save();

            }else{
                $user->name = $data['first_name'].' '.$data['last_name'];
                $user->save();
            }

            $teacherData = [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name.' '.$request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'gender' => $request->gender,
                'dob' => date("Y-m-d", strtotime($request->dob)),
                'aadhar_no' => $request->aadhar_no,


                'residential_address' => $request->residential_address,
                'permanent_address' => $request->permanent_address,

                'father_name' => $request->father_name,
                'father_mobile' => $request->father_mobile,
                'father_aadhar_no' => $request->father_aadhar_no,

                'mother_name' => $request->mother_name,
                'mother_mobile' => $request->mother_mobile,
                'mother_aadhar_no' => $request->mother_aadhar_no,

                'active' => $request->active ?? 1,
                'school_id' => $authUser->client_id,
            ];


            if(!$e_teacher){
                $teacher = Teacher::create($teacherData);

            }else{
                $e_teacher->update($teacherData);
                $teacher = $e_teacher;
            }

            $check = DB::table("bank_details")->where("user_id", $user->id)->first();
            if($check){
                DB::table("bank_details")->where("id", $check->id)->update([
                    'account_holder_name' => $request->bank_details['account_holder_name'] ?? null,
                    'bank_name' => $request->bank_details['bank_name'] ?? null,
                    'account_number' => $request->bank_details['account_number'] ?? null,
                    'ifsc_code' => $request->bank_details['ifsc_code'] ?? null,
                    'branch_name' => $request->bank_details['branch_name'] ?? null,
                    'upi_id' => $request->bank_details['upi_id'] ?? null,
                ]);

            } else {
                DB::table("bank_details")->insert([
                    "client_id" => $authUser->client_id,
                    "user_id" => $user->id,
                    'account_holder_name' => $request->bank_details['account_holder_name'] ?? null,
                    'bank_name' => $request->bank_details['bank_name'] ?? null,
                    'account_number' => $request->bank_details['account_number'] ?? null,
                    'ifsc_code' => $request->bank_details['ifsc_code'] ?? null,
                    'branch_name' => $request->bank_details['branch_name'] ?? null,
                    'upi_id' => $request->bank_details['upi_id'] ?? null,
                    "active" => 1,
                    "created_at" => date("Y-m-d H:i:s"),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully',
                'teacher' => $teacher,
                'login_password' => $password
            ]);

        } catch (\Exception $e){

            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function teacherProfile($teacher_token){
        return view('admin.teachers.profile', [
            'teacher_token' => $teacher_token,
        ]);  
    }

    public function getProfileDetails(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $teacher_token = $request->teacher_token;

        $teacher = Teacher::with(['parentUser'])->where('unique_id',$request->teacher_token)->first();
        
        $data = null;
        if($teacher){
            $data = $teacher->toArray();
            if(isset($data['parent_user'])){
                foreach($data['parent_user'] as $key => $value){
                    if(!array_key_exists($key, $data)){
                        $data[$key] = $value;
                    }
                }
                unset($data['parent_user']);
            }
        }

        return response()->json([
            "success" => true,
            "teacher" => $data,
        ]); 
        
    }

    public function getAttendance(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $teacher_token = $request->teacher_token;

        $teacher_id = Teacher::teacherId($teacher_token);
        $data = [];

        return response()->json([
            "success" => true,
            "attendance_data" => $data,
        ]); 

    }

    public function getLeaves(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $teacher_token = $request->teacher_token;

        $teacher_id = Teacher::teacherId($teacher_token);
        $data = [];

        return response()->json([
            "success" => true,
            "leaves" => $data,
        ]); 

    }

    private function resolveApiUser(Request $request): ?User
    {
        $apiToken = (string) $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return null;
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if (!in_array($priv, [1, 2], true)) {
            return null;
        }

        return $user;
    }

}
