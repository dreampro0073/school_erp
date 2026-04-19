<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceStatus;
use App\Models\MasterData;
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

    public function initTeachers(Request $request) {

        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $limit = $request->has('limit')?$request->limit:10;
        $query = Teacher::where('school_id', $auth_user->client_id)->where("status", "!=", 2);
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('mobile', 'like', '%'.$request->search.'%');
            });
        }

        // ✅ GENDER FILTER
        if ($request->gender) {
            $query->where('gender', ucfirst($request->gender)); 
            // because DB has Male/Female
        }
         if ($request->status !== null && $request->status !== '') {
            $query->where('active', $request->status);
        }

        $teachers = $query->orderBy('id', 'DESC')->paginate($limit);

        foreach ($teachers as $teacher) {
            $teacher->dob = $teacher->dob ? date("d-m-Y", strtotime($teacher->dob)) : null;
            $teacher->joining_date = $teacher->joining_date ? date("d-m-Y", strtotime($teacher->joining_date)) : null;
            $teacher->resign_date = $teacher->resign_date ? date("d-m-Y", strtotime($teacher->resign_date)) : null;
        }


        return response()->json([
            'success' => true,
            'teachers' => $teachers,
        ]);
    }

    public function addTeacherPage($teacher_token=0) {
        $auth_user = Auth::user();
        if ($teacher_token > 0) {
            $teacher = Teacher::where('unique_id', $teacher_token)->first();

            if (!$teacher) {
                die("Teacher not found!");
            }

            $check = User::where("id", $teacher->user_id)
                ->where("priv", 3)
                ->where("parent_user_id", $auth_user->parent_user_id)
                ->first();

            if (!$check) {
                die("You are not authorised to edit this profile!");
            }
        }

        return view('admin.teachers.form', [
            'teacher_token' => $teacher_token,
        ]);
    }

    public function viewDetails(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $teacher_token = $request->teacher_token;

        $teacher = Teacher::where('unique_id',$request->teacher_token)->first();

        return response()->json([
            "success" => true,
            "teacher" => $teacher,
            "blood_groups" => MasterData::getMasterData(4),
            "religions" => MasterData::getMasterData(1),
            "casts" => MasterData::getMasterData(2),
        ]);
    }

    public function initDetails(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        if (!$request->teacher_token || $request->teacher_token == 0) {
            return response()->json([
                "success" => true,
                "teacher" => [
                    'enc_id' => null,
                    'first_name' => '',
                    'last_name' => '',
                    'email' => '',
                    'mobile' => '',
                    'gender' => '',
                    'dob' => null,
                    'salary_components' => [
                        [
                            'component_name' => 'Basic Salary',
                            'component_type' => 'earning',
                            'amount' => ''
                        ]
                    ],
                    'bank_details' => [
                        'account_holder_name' => '',
                        'bank_name' => '',
                        'account_number' => '',
                        'ifsc_code' => '',
                        'branch_name' => '',
                        'upi_id' => ''
                    ]
                ],

                // dropdowns
                "blood_groups" => MasterData::getMasterData(4),
                "religions" => MasterData::getMasterData(1),
                "casts" => MasterData::getMasterData(2),
            ]);
        }

        // ✅ EDIT MODE

        $teacher = Teacher::where('unique_id', $request->teacher_token)->first();

        $check = User::where("id", $teacher->user_id)->where("parent_user_id", $auth_user->parent_user_id)->first();

        if(!$teacher || !$check){
            return response()->json([
                "success" => false,
                "message" => "Teacher not found"
            ],404);
        }

        $bank = DB::table('bank_details')->where('user_id', $teacher->user_id)->first();

        $salary = DB::table('salary_structures')
        ->where('user_id', $teacher->user_id)
        ->get();

        $response = [
            'enc_id' => $teacher->unique_id,

            'first_name' => $teacher->first_name,
            'last_name' => $teacher->last_name,
            'email' => $teacher->email,
            'mobile' => $teacher->mobile,
            'gender' => $teacher->gender,
            'marital_status' => $teacher->marital_status,
            'qualification' => $teacher->qualification,
            'eligibility' => $teacher->eligibility,
            'experience' => $teacher->experience,
            'skills' => $teacher->skills,
            'dob' => $teacher->dob ? date('Y-m-d', strtotime($teacher->dob)) : null,
            'aadhar_no' => $teacher->aadhar_no,

            'joining_date' => $teacher->joining_date ? date('d-m-Y', strtotime($teacher->joining_date)) : null,
            'resign_date' => $teacher->resign_date ? date('d-m-Y', strtotime($teacher->resign_date)) : null,

            'erp_id' => $teacher->erp_id,

            'residential_address' => $teacher->residential_address,
            'permanent_address' => $teacher->permanent_address,

            'father_name' => $teacher->father_name,
            'father_mobile' => $teacher->father_mobile,
            'father_email' => $teacher->father_email,
            'father_aadhar_no' => $teacher->father_aadhar_no,

            'mother_name' => $teacher->mother_name,
            'mother_mobile' => $teacher->mother_mobile,
            'mother_email' => $teacher->mother_email,
            'mother_aadhar_no' => $teacher->mother_aadhar_no,

            'blood_group_id' => $teacher->blood_group_id,
            'religion_id' => $teacher->religion_id,
            'cast_id' => $teacher->cast_id,
            'height' => $teacher->height,
            'weight' => $teacher->weight,
            'previous_school' => $teacher->previous_school,
            'previous_school_address' => $teacher->previous_school_address,

            'bank_details' => [
                'account_holder_name' => optional($bank)->account_holder_name,
                'bank_name' => optional($bank)->bank_name,
                'account_number' => optional($bank)->account_number,
                'ifsc_code' => optional($bank)->ifsc_code,
                'branch_name' => optional($bank)->branch_name,
                'upi_id' => optional($bank)->upi_id,
            ],

            'salary_components' => $salary->count() ? $salary->map(function($item){
                return [
                    'component_name' => $item->component_name,
                    'component_type' => $item->component_type,
                    'amount' => $item->amount,
                ];
            }) : [
                [
                    'component_name' => 'Basic Salary',
                    'component_type' => 'earning',
                    'amount' => ''
                ]
            ],

            'active' => (string) $teacher->active,
        ];

        return response()->json([
            "success" => true,
            "teacher" => $response,

            // Dropdowns
            "blood_groups" => MasterData::getMasterData(4),
            "religions" => MasterData::getMasterData(1),
            "casts" => MasterData::getMasterData(2),
        ]);
    }

    public function deleteTeacher(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $teacher = Teacher::where('unique_id', $request->unique_id)->where('school_id', $auth_user->client_id)->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ]);
        }

        $teacher->status = 2;
        $teacher->save();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }

    public function storeTeacher(Request $request)
    {
        $authUser = User::resolveApiUser($request);
        $unique_id = $request->enc_id ?: ($request->unique_id ?: null);
        $e_teacher = $unique_id ? Teacher::where('unique_id', $unique_id)->first() : null;

        $validator = Validator::make($request->all(), [
            'first_name' => ['required','string','max:255'],
            'last_name' => ['nullable','string','max:255'],
            'gender' => ['required'],
            'qualification' => ['required'],
            'marital_status' => ['required'],
            'dob' => ['required'],
            'mobile' => ['required','digits:10'],
            // 'email' => 'required|email|unique:teachers,email,' . ($e_teacher->id ?? 'NULL') . ',id',
            'aadhar_no'=> ['required','digits:12'], 
            'blood_group_id' => ['required','integer'],
            'weight' => ['required','string','max:20'],
            'height' => ['required','string','max:20'],
            'residential_address' => ['required'],
            'permanent_address' => ['required'],

            'father_name' => ['nullable','string','max:255'],
            'mother_name' => ['nullable','string','max:255'],

            'bank_details.account_holder_name' => ['required','string','max:255'],
            'bank_details.bank_name' => ['required','string','max:255'],
            'bank_details.account_number' => ['required','string','max:50'],
            'bank_details.ifsc_code' => ['required','string','max:20'],
            'bank_details.branch_name' => ['required','string','max:255'],
            'bank_details.upi_id' => ['nullable','string','max:255'],

            // 'salary_components' => ['required','array','min:1'],
            // 'salary_components.*.component_name' => ['required','string','max:255'],
            // 'salary_components.*.component_type' => ['required','in:earning,deduction'],
            // 'salary_components.*.amount' => ['required','regex:/^\d+(\.\d{1,2})?$/'],
        ]);

        $validator->setCustomMessages([
            'blood_group_id.required' => 'Blood group is required.',
            'weight.required' => 'Weight is required.',
            'height.required' => 'Height is required.',
            'bank_details.account_holder_name.required' => 'Account holder name is required.',
            'bank_details.bank_name.required' => 'Bank name is required.',
            'bank_details.account_number.required' => 'Account number is required.',
            'bank_details.ifsc_code.required' => 'IFSC code is required.',
            'bank_details.branch_name.required' => 'Branch name is required.',
            'salary_components.required' => 'At least one salary component is required.',
            'salary_components.*.component_name.required' => 'Component name is required.',
            'salary_components.*.component_type.required' => 'Component type is required.',
            'salary_components.*.amount.required' => 'Amount is required.',
            'salary_components.*.amount.regex' => 'Enter a valid amount.',
        ]);


        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors(),
            ],422);
        }
        if(!empty($request->email)){
            $check = User::where('email',$request->email)
                ->when($e_teacher, function($q) use ($e_teacher){
                    return $q->where('id','!=',$e_teacher->user_id);
                })
                ->exists();
            if($check){
                return response()->json([
                    'success'=>false,
                    'message' => "Email already exists in the system kindly use different email",
                ],422);
            }
        }

        DB::beginTransaction();
        try {
            $user = null;

            $email = !empty($request->email) 
                ? $request->email 
                : strtolower($request->first_name).'_'.$authUser->client_id.'_'.time().'@school.com';

            if($e_teacher && $e_teacher->user_id){
                $user = User::find($e_teacher->user_id);
            }

    
            if(!$user){
                $user = User::where('email',$email)->first();
            }

            if(!$user){
                $password = User::getRandPassword();

                $user = new User;
                $user->email = $email;
                $user->password = Hash::make($password);
                $user->password_check = $password;
                $user->priv = 3;
                $user->added_by = $authUser->id;
                $user->org_id = $authUser->org_id;
                $user->client_id = $authUser->client_id;
                $user->parent_user_id = $authUser->parent_user_id;
            }

            $user->name = $request->first_name.' '.$request->last_name;

           
            if(!empty($request->email)){
                $user->email = $request->email;
            }

            $user->save();

            $teacherData = [
                'user_id' => (isset($user)) ? $user->id : null,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name.' '.$request->last_name,
                'email' => $email,
                'mobile' => $request->mobile,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
                'dob' => date("Y-m-d", strtotime($request->dob)),
                'aadhar_no' => $request->aadhar_no,

                'qualification' => $request->qualification,
                'eligibility' => $request->eligibility,
                'experience' => $request->experience,
                'skills' => $request->skills,
                

                'residential_address' => $request->residential_address,
                'permanent_address' => $request->permanent_address,

                'father_name' => $request->father_name,
                'father_mobile' => $request->father_mobile,
                'father_email' => $request->father_email,
                'father_aadhar_no' => $request->father_aadhar_no,

                'mother_name' => $request->mother_name,
                'mother_mobile' => $request->mother_mobile,
                'mother_email' => $request->mother_email,
                'mother_aadhar_no' => $request->mother_aadhar_no,

                'blood_group_id' => $request->blood_group_id,
                'height' => $request->height,
                'weight' => $request->weight,
                'previous_school' => $request->previous_school,
                'previous_school_address' => $request->previous_school_address,

                'active' => $request->active ?? 1,
                'school_id' => $authUser->client_id,
            ];


            if(!$e_teacher){
                $teacherData['unique_id'] = time().$authUser->client_id.$authUser->id;
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


            // ✅ Delete old salary (for update case)
                DB::table('salary_structures')
                    ->where('user_id', $teacher->user_id)
                    ->delete();
                // ✅ Insert new salary components

                if (!empty($request->salary_components)) {
                    $salaryInsert = [];

                   foreach ($request->salary_components as $index => $item) {
                        $salaryInsert[] = [
                            'client_id' => $authUser->client_id, // required
                            'user_id' => $teacher->user_id,
                            'component_name' => $item['component_name'],
                            'component_type' => $item['component_type'],
                            'amount' => $item['amount'],
                            'sort_order' => $index + 1,
                            'active' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    DB::table('salary_structures')->insert($salaryInsert);
                }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully',
                // 'teacher' => $teacher,
                // 'login_password' => $password
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
