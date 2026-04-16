<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Student,App\Models\StudentParent,App\Models\MasterData, App\Models\User,App\Models\Standard,App\Models\FeePayment,App\Models\FeeType;
use DB;

use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function index(){
        return view('admin.students.index');
    }

    public function addStudentPage($student_token=0) {
        return view('admin.students.form', [
            'student_token' => $student_token,
        ]);
    }

    public function initStudents(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        $limit = $request->has('limit')?$request->limit:10;

        $query = Student::where('school_id', $user->client_id);
        if($request->search){
            $query->where('first_name', 'like', '%'.$request->search.'%');
        }

        $students = $query->orderBy('id', 'DESC')->paginate($limit);

        return response()->json([
            "success" => true,
            "data" => $students
        ]);

    }
   
    public function storeStudent(Request $request){
        $authUser = User::resolveApiUser($request);

        $e_student = Student::where('unique_id',$request->unique_id)->first(); 

        $user_id = $e_student ? $e_student->user_id : 'NULL';
        $message = "Student details Successfully saved!";

        $validator = Validator::make($request->all(), [
            'first_name' => ['required','string','max:255'],
            // 'last_name' => ['nullable','string','max:255'],
            'gender' => ['required'],
            'dob' => ['required'],
            // 'mobile' => ['required','digits:10'],
            'email' => 'required|email|unique:users,email,'.$user_id,
            'aadhar_no'=> ['required','digits:12'], 
            'residential_address' => ['required'],
            'permanent_address' => ['required'],
            'father_name' => ['required','string','max:255'],
            // 'father_email' => ['required','email','max:255'],
            'father_mobile' => ['required','digits:10'],
            'father_aadhar_no' => ['required','digits:12'],
            'mother_name' => ['required','string','max:255'],
            'mother_aadhar_no' => ['required','digits:12'],
            'religion_id' => ['required'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->has('father_email') && $request->email === $request->father_email) {
                $validator->errors()->add('father_email', 'Parent email cannot be same as student email');
            }
        });

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors(),
            ],422);
        }

        $data = $request->only([
            'first_name',
            'last_name',
            'gender',
            'dob',
            'mobile',
            'email',
            'aadhar_no', 
            'residential_address',
            'permanent_address',
            'religion_id',
            'cast_id',
            'blood_group_id',
            'standard_id',
            'section_id',
            'height',
            'weight',
            'previous_school',
            'previous_school_address',
            'approved',
            'student_photo',
            'aadhar_card',
        ]);

        $parent_data = $request->only([
            'father_name',
            'father_email',
            'father_mobile',
            'father_aadhar_no',
            'mother_name',
            'mother_aadhar_no',
            'father_occupation',
            'mother_mobile',
            'mother_email',
            'guardian_name'
        ]);

        DB::beginTransaction();
        try {
            $parentUser = User::where('email',$parent_data['father_email'])->first();
           
            if(!$parentUser){
                $parentPassword = User::getRandPassword();
                $parentUser = new User;
                $parentUser->name = $parent_data['father_name'];
                $parentUser->email = $parent_data['father_email'];
                $parentUser->password = Hash::make($parentPassword);
                $parentUser->password_check = $parentPassword;
                $parentUser->parent_user_id =0;
                $parentUser->added_by = $authUser->id;
                $parentUser->org_id = $authUser->org_id;
                $parentUser->client_id = $authUser->client_id;
                $parentUser->priv = 5;
                $parentUser->save();
            }else{ 
                $parentUser->name = $parent_data['father_name'];
                $parentUser->save();
            }

            $parent = StudentParent::where('user_id',$parentUser->id)->first();

            if(!$parent){
                $parent_data['user_id'] = $parentUser->id;
                $parent_data['school_id'] = $authUser->client_id;
                $parent_data['unique_id'] = time().$authUser->client_id.$authUser->id.$parentUser->id;
                $parent = StudentParent::create($parent_data);
            }else{
                $parent->update($parent_data);
            }

            $user = User::where('email',$request->email)->first();
            if(!$user){
                $user = new User;
                $password = User::getRandPassword();
                $user->password = Hash::make($password);
                $user->name = $data['first_name'].' '.$data['last_name'];
                $user->start_date = $authUser->start_date;
                $user->parent_user_id = $parentUser->id;
                $user->added_by = $authUser->id;
                $user->password_check = $password;
                $user->org_id = $authUser->org_id;
                $user->client_id = $authUser->client_id;
                $user->priv = 4;
                $user->email = $data['email'] ?? null;
                $user->save();

                $data['school_id'] = $authUser->client_id;
                $data['user_id'] = $user->id;
                $data['parent_id'] = $parent->id;
                $data['unique_id'] = time().$authUser->client_id.$authUser->id;
            }else{
                $user->name = $data['first_name'].' '.$data['last_name'];
                $user->save();
            }

            $data['name'] = $data['first_name'].' '.$data['last_name'];
            $data['dob'] = date("Y-m-d",strtotime($request->dob));

            if(!$e_student){

                $student = Student::create($data);

            }else{
                $data['admission_no'] = $this->generateAdmissionNumber($authUser->client_id);
                
                $e_student->update($data);
                $student = $e_student;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'student' => $student
            ]);

        } catch (\Exception $e){
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function studentProfile($student_token){
        return view('admin.students.profile', [
            'student_token' => $student_token,
        ]);  
    }

    public function getProfileDetails(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $student_token = $request->student_token;

        $student = Student::with([
            'parentUser',
            'standard:id,name',
            'section:id,name',
        ])->where('unique_id', $request->student_token)->first();
        
        $data = null;
        if($student){
            $data = $student->toArray();
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
            "student" => $data,
        ]); 
        

    }

    public function viewDetails(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $student_token = $request->student_token;

        $student = Student::with(['parentUser'])->where('unique_id',$request->student_token)->first();
        
        $data = null;
        if($student){
            $data = $student->toArray();
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
            "student" => $data,
            "blood_groups" => MasterData::getMasterData(4),
            "religions" => MasterData::getMasterData(1),
            "casts" => MasterData::getMasterData(2),
            "standards" => Standard::getClientStandardsDrop($user->client_id),
        ]);
    }

    public function getAttendance(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $student_token = $request->student_token;

        $student_id = Student::studentId($student_token);
        $data = [];

        return response()->json([
            "success" => true,
            "attendance_data" => $data,
        ]); 

    }

    public function getLeaves(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $student_token = $request->student_token;

        $student_id = Student::studentId($student_token);
        $data = [];

        return response()->json([
            "success" => true,
            "leaves" => $data,
        ]); 

    }

    public function getExams(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $student_token = $request->student_token;
        $standard_id = $request->standard_id;

        $student_id = Student::studentId($student_token);
        
        $data = [];

        return response()->json([
            "success" => true,
            "exams" => $data,
        ]); 

    }
    public function getFeeParams(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $student_token = $request->student_token;
        $student_id = Student::studentId($student_token);

        return response()->json([
            "success" => true,
            "fee_types" => FeeType::getFeeTypes(),
            "fee_frequencies" => FeePayment::getFeeFrequencies($user->client_id),
            "payment_modes" => FeePayment::getPaymentModes(),
            "months" => FeePayment::getMonths(),
            "routes" => FeePayment::getRoutes($user->client_id),
            "years" => DB::table("years")->select("period as label", "year as value")->get(),
        ]); 

    }  
    public function getFees(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $student_token = $request->student_token;
        $student_id = Student::studentId($student_token);

        $limit = $request->has('limit')?$request->limit:2;

        $months = [
            1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',
            7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'
        ];


        $query = FeePayment::with(['feeType:id,name','paymentMode:id,name'])->where([
            'school_id'=> $user->client_id,
            'student_id'=> $student_id,

        ]);
       
        $payments = $query->orderBy('id', 'DESC')->paginate($limit);

        $payments->getCollection()->transform(function ($item) use ($months) {

            return [
                'id' => $item->id,
                'amount' => $item->amount,
                'month' => $item->month,
                'month_name' => $months[$item->month] ?? null,
                'fee_type_id' => $item->fee_type_id,
                'fee_type_name' => $item->feeType->name ?? null,
                'payment_mode' => $item->paymentMode->name ?? null,
                
                'paid_date' => date("d-m-Y",strtotime($item->paid_date)),
            ];
        });

        return response()->json([
            "success" => true,
            "data" => $payments
        ]);

    }
   

    public function getFeeSubs(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $student_token = $request->student_token;
        $student_id = Student::studentId($student_token);
        $standard_id = $request->standard_id;
        $fee_type_id = $request->fee_type_id;
        $frequency_id = $request->has('frequency_id')?$request->frequency_id:null;
        $route_id = $request->has('route_id')?$request->route_id:null;

        
        return response()->json([
            "success" => true,
            "amount" => FeePayment::getFeeAmount($user->client_id,$fee_type_id,$standard_id,$student_id,$frequency_id,$route_id),
        ]); 

    }    

    public function collectFee(Request $request){
        $authUser = User::resolveApiUser($request);

        $student_token = $request->student_token;
        $student_id = Student::studentId($student_token);

        $message = "Fee details Successfully saved!";

        $validator = Validator::make($request->all(), [
            'amount' => ['required'],
            'standard_id' => ['required'],
            'fee_type_id' => ['required'],
            'payment_mode' => ['required'],
            'fin_year' => ['required'],
            
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors(),
            ],422);
        }

        $data = $request->only([
            'standard_id',
            'fee_type_id',
            'payment_mode',
            'amount',
            'fin_year',
            'month',
        ]);

        $data['school_id'] = $authUser->client_id;
        $data['student_id'] = $student_id;
        $data['paid_date'] = now();
        $data['added_by'] = $authUser->id;
        $data['route_id'] = $request->has('route_id')?$request->route_id:0;

        DB::beginTransaction();
        try {
            if(!$request->id){

                $data_ar = [
                    'student_id' => $data['student_id'],
                    'fee_type_id' => $data['fee_type_id'],
                    'school_id' => $data['school_id']
                ];

                if($request->month != ""){
                    $data_ar['month'] = $request->month;

                    $exists = FeePayment::where($data_ar)->exists();

                    if ($exists) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Fee already collected for this month/category'
                        ], 422);
                    }

                }

                $fee_payment = FeePayment::create($data);
                $message = "Fee collected successfully!";

            }else{
                $fee_payment = FeePayment::where('id', $request->id)->where('school_id', $authUser->client_id)->first();

                $fee_payment->update($data);
                $message = "Fee updated successfully!";
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'fee_payment' => $fee_payment,
                'payment_id' => $fee_payment->id,
            ]);

        } catch (\Exception $e){
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function destroy($id){
        Student::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Student deleted'
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

    public function uploadFile(Request $request){


        $destination = "students_photos/";

        if($request->file('media')){
            $extension = $request->file('media')->getClientOriginalExtension();
            if(in_array($extension, User::fileExtensions())){

                $file = $request->file('media');
                $name_file = pathinfo($request->file('media')->getClientOriginalName(), PATHINFO_FILENAME);
                $name_file = preg_replace('/[^a-zA-Z0-9]/', '', $name_file);

                $name = 'student'.$name_file.'_'.strtotime("now").'.'.strtolower($extension);
                $file = $file->move($destination, $name);
                $data['media'] = $destination.$name;
                $data['media_link'] = url($destination.$name);

                return response()->json([
                    "success" => true,
                    "data" => $data,
                ]);
            }else{

                return response()->json([
                    "success" => false,
                    "message" => "Invalid file format for image , Valid extentions are  jpg , png ,jpeg",
                ]);


            }
        }else{
            return response()->json([
                "success" => false,
                "message" => "Please select image",
            ]);
        }
        

    }

    public function generateAdmissionNumber($clientId){
        $year = date('y');
        $prefix = $year . $clientId;

        $remainingLength = 8 - strlen($prefix);

        $lastStudent = Student::where('admission_no', 'LIKE', $prefix . '%')
            ->orderBy('admission_no', 'desc')
            ->first();

        if ($lastStudent) {
            $lastNumber = substr($lastStudent->admission_no, strlen($prefix));
            $nextNumber = (int)$lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $sequence = str_pad($nextNumber, $remainingLength, '0', STR_PAD_LEFT);
        return $prefix . $sequence;
    }


    public function generateReceipt($id){
        $fee = FeePayment::with(['feeType:id,name','student:id,name','paymentMode:id,name','school:id,name,address'])
            ->where('id', $id)
            ->first();


        if (!$fee) {
            abort(404, 'Receipt not found');
        }

        $years = DB::table("years")->pluck("period", "year")->toArray();

        $months = [
            1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',
            7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'
        ];

        $path = public_path('assets/img/gyan_logo1.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $dataImg = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);


        $data = (object) [
            'id' => $fee->id,
            'receipt_no' => 'RCPT-' . str_pad($fee->id, 6, '0', STR_PAD_LEFT),

            'student_name' => $fee->student->name ?? null,
            'fee_type_name' => $fee->feeType->name ?? null,
            'payment_mode' => $fee->paymentMode->name ?? null,
            'school_name' => $fee->school->name ?? null,
            'school_address' => $fee->school->address ?? null,
            'logo' => $base64,
            'amount' => $fee->amount,
            // 'payment_mode' => ucfirst($fee->payment_mode),

            'month' => $fee->month,
            'month_name' => $months[$fee->month] ?? null,
            'period' => $years[$fee->fin_year] ?? null,

            'paid_date' => date("d-m-Y",strtotime($fee->paid_date)),
        ];

        $pdf = Pdf::loadView('admin.students.receipts.fee_receipt', compact('data'));

        return $pdf->stream('receipt.pdf');

     
        return $pdf->download('fee_receipt_'.$fee->id.'.pdf');

    }
}
