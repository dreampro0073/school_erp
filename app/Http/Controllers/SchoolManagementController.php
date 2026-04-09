<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Worklog;
use App\Models\FeeType;
use App\Models\FeePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Standard, App\Models\Section, App\Models\Teacher, App\Models\Student, DB;
use Illuminate\Support\Facades\Validator;

class SchoolManagementController extends Controller {
    public function index(Request $request) {

        return view('admin.school.index');
    }

    public function initSchool(Request $request) {
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $data["standards"] = Standard::where("standards.status", 0)->where("client_id", $auth_user->client_id)->pluck("name", "id")->toArray();
        $data["sections"] = Section::where("sections.status", 0)->pluck("name", "id")->toArray();
        $data["sessions"] = DB::table('years')->pluck("period", "year")->toArray();
        $data["teachers"] = Teacher::where("school_id", $auth_user->client_id)->pluck("teachers.name", "teachers.id")->toArray();
        $data["students"] = Student::where("school_id", $auth_user->client_id)->pluck("name", "id")->toArray();

        $data["days"] = DB::table("days")->where("id", "!=", 7)->pluck("name3l", "id")->toArray();

        $data["edit_flag"] = $auth_user->priv == 2 ? true : false;
        $data["success"] = true;
        return response()->json($data,200,[]);
    }

    // ** Schedule **
    public function initSchedule(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);
        

        $schedule = DB::table('class_schedule')
            ->where('client_id', $auth_user->client_id)
            ->when($request->standard_id, function ($q) use ($request) {
                $q->where('standard_id', $request->standard_id);
            });

        if ((int)$request->day_id !== 8) {
            $schedule->when($request->day_id, function ($q) use ($request) {
                $q->where('day_id', $request->day_id);
            });
        }

        $schedule = $schedule->orderBy('start_time', 'asc')->get();

        $subjects = DB::table("subjects")
            ->where("client_id", $auth_user->client_id)
            ->get();

        $data["success"] = true;
        $data["schedule"] = $schedule;
        $data["subjects"] = $subjects;

        return response()->json($data, 200, []);
    }    

    public function scheduleStore(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        DB::beginTransaction();

        try {
            if (!$request->has('schedule') || !is_array($request->schedule)) {
                throw new \Exception('Schedule data is required');
            }

            $standard_id = $request->standard_id;
            $day_id = (int) $request->day_id;
            $scheduleRows = $request->schedule;

            if (!$standard_id) {
                throw new \Exception('Standard is required');
            }

            if ($day_id == 8) {
                $days = DB::table("days")
                    ->where("id", "!=", 7)
                    ->pluck("id")
                    ->toArray();
            } else {
                $days = [$day_id];
            }

            foreach ($days as $currentDay) {

                $existingRows = DB::table('class_schedule')
                    ->where('client_id', $auth_user->client_id)
                    ->where('standard_id', $standard_id)
                    ->where('day_id', $currentDay)
                    ->orderBy('id', 'asc')
                    ->get()
                    ->values();

                $existingIds = $existingRows->pluck('id')->toArray();
                $requestIds = [];

                foreach ($scheduleRows as $index => $row) {

                    $rowId = !empty($row['id']) ? (int) $row['id'] : 0;

                    $data = [
                        'client_id'   => $auth_user->client_id,
                        'standard_id' => $standard_id,
                        'section_id'  => $row['section_id'] ?? null,
                        'subject_id'  => $row['subject_id'] ?? null,
                        'teacher_id'  => $row['teacher_id'] ?? null,
                        'day_id'      => $currentDay,
                        'start_time'  => $row['start_time'] ?? null,
                        'end_time'    => $row['end_time'] ?? null,
                        'duration'    => $row['duration'] ?? null,
                        'remarks'     => $row['remarks'] ?? null,
                        'updated_at'  => now(),
                        'added_by'    => $auth_user->id,
                        'approved_by' => $auth_user->client_id,
                    ];

                    if ($rowId > 0) {
                        DB::table('class_schedule')
                            ->where('id', $rowId)
                            ->where('client_id', $auth_user->client_id)
                            ->where('standard_id', $standard_id)
                            ->where('day_id', $currentDay)
                            ->update($data);

                        $requestIds[] = $rowId;
                    } else {
                        $data['created_at'] = now();

                        $newId = DB::table('class_schedule')->insertGetId($data);
                        $requestIds[] = $newId;
                    }
                }

                $deleteIds = array_diff($existingIds, $requestIds);

                if (!empty($deleteIds)) {
                    DB::table('class_schedule')
                        ->where('client_id', $auth_user->client_id)
                        ->where('standard_id', $standard_id)
                        ->where('day_id', $currentDay)
                        ->whereIn('id', $deleteIds)
                        ->delete();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Schedule saved successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    // ** Classes **
    public function initClasses(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $classes = DB::table("standards")->select("standards.*")
        ->where("standards.client_id", $auth_user->client_id)->where("standards.status", 0)->get();
        
        $data["success"] = true;
        $data["classes"] = $classes;
        return response()->json($data,200,[]);
    }

    public function editClass(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $formData = DB::table('standards')->where('id', $request->id)->where("client_id", $auth_user->client_id)->first();
        
        if($formData){
            $data["success"] = true;
            $data["formData"] = $formData;
        } else {
            $data["success"] = true;
            $data["message"] = "Data not found";
        }

        return response()->json($data,200,[]);
    }

    public function classStore(Request $request){

        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $request->validate([
            'name'  => 'required',
        ]);

        if($request->id){
            DB::table('standards')->where('id', $request->id)->where("client_id", $auth_user->client_id)->where("is_verified", "!=", 1)->update([
                "name" => $request->name,
                "status" => 0, 
                "is_verified" => 0
            ]);

            $data["success"] = true;
            $data["message"] = "Updated Successfully";

        } else {
            DB::table('standards')->insertGetId([
                "name" => $request->name,
                'status'       => 0,
                "is_verified"  => 0,
                "client_id" => $auth_user->client_id,
                "created_at" => date("Y-m-d H:i:s")
            ]);                

            $data["success"] = true;
            $data["message"] = "Created Successfully";
        }
        return response()->json($data,200,[]);
    }

    public function changeClassStatus(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $check = DB::table('standards')->where('id', $request->entry_id)->where("client_id", $auth_user->client_id)->first();

        if($check){
            $status = $request->status;
            $currentVerified = (int) data_get($check, 'is_verified', 0);

            if($currentVerified == 1 && $request->status == -1){
                $status = -2;
            } else if($currentVerified == -2 && $request->status == 0){
                $status = 1;
            }

            DB::table('standards')->where('id', $check->id)->update([
                "is_verified" => $status,
            ]);

            $data["success"] = true;
            $data["message"] = "Successfully Updated";
            $data["status"] = $status;
        } else {
            $data["success"] = true;
            $data["message"] = "Data not found";
        }
        
        return response()->json($data,200,[]);
    }

    public function deleteClass(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);
        $check = DB::table('standards')->where('id', $request->id)->where("client_id", $auth_user->client_id)->first();

        if($check){
            DB::table('standards')->where('id', $check->id)->update([
                "status" => 1,
            ]);

            $data["success"] = true;
            $data["message"] = "Deleted Successfully";
        } else {
            $data["success"] = false;
            $data["message"] = "Data not found";
        }
        
        return response()->json($data,200,[]);
    }

    public function classManage($class_id) {

        return view('admin.school.class_manage', ["class_id"=>$class_id]);
    }

    public function classManageInit(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);
        $classId = $request->class_id;

        $standard = DB::table("standards")->select("standards.*")
        ->where("standards.id", $classId)
        ->where("standards.client_id", $auth_user->client_id)->where("standards.status", 0)->first();

        if($request->type == "students"){
            $dataList = DB::table("class_students")->where("school_id", $auth_user->client_id)->where("class_id", $classId)->get();
            $data["dataList"] = $dataList;
        }

        if($request->type == "subjects"){
            $dataList = DB::table("class_subjects")->select("class_subjects.*", "subjects.name as sub_name")->join("subjects", "subjects.id", "class_subjects.subject_id")->where("school_id", $auth_user->client_id)->where("class_id", $classId)->where("class_subjects.status", 0)->get();
            $data["dataList"] = $dataList;
        }

        if($request->type == "fee"){
            $dataList = DB::table("fee_structures")
            ->select(
                "fee_structures.*",
                "fee_frequencies.name as fee_frequency",
                "fee_types.name as fee_type",
                "fee_types.description",
                "years.period"
            )
            ->leftJoin("fee_types", "fee_types.id", "=", "fee_structures.fee_type_id")
            ->leftJoin("fee_frequencies", "fee_frequencies.id", "=", "fee_structures.frequency_id")
            ->leftJoin("years", "years.year", "=", "fee_structures.fin_year")
            ->where("fee_structures.school_id", $auth_user->client_id)
            ->where("fee_structures.class_id", $classId)
            ->orderBy("fee_structures.id", "DESC")
            ->get();
            
            $data["dataList"] = $dataList;
            $data["fee_types"] = FeeType::getFeeTypes();
            $data["fee_frequencies"] = FeePayment::getFeeFrequencies($auth_user->client_id);
        }

        $data["years"] = DB::table("years")->pluck("period", "year")->toArray();
        $data["standard"] = $standard;
        $data["success"] = true;
        return $data;
    } 

    // ** Exams **
    public function initExams($request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $exams = [];
        $data["success"] = true;
        $data["exams"] = $exams;
        return $data;
    } 

    public function editExams(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $formData = [];
        
        if($formData){
            $data["success"] = true;
            $data["formData"] = $formData;
        } else {
            $data["success"] = true;
            $data["message"] = "Data not found";
        }

        return response()->json($data,200,[]);
    }

    public function examsStore(Request $request){
        dd("Pending....");
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $request->validate([
            'session_id'   => 'required',
        ]);

        foreach ($variable as $item) {
            if(true){ 
                DB::table('exams')->update([
                    "updated_at" => date("Y-m-d H:i:s"),
                ]);
            } else {
                DB::table('exams')->insert([
                    "updated_at" => date("Y-m-d H:i:s"),
                    "created_at" => date("Y-m-d H:i:s"),
                ]);
            }   
        }

        $data["success"] = true;
        $data["message"] = "Updated Successfully"; 
    } 

    // ** Result **
    public function initResults($request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $results = [];
        $data["success"] = true;
        $data["results"] = $results;
        return $data;
    }

    public function editResult(Request $request){
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $formData = [];
        
        if($formData){
            $data["success"] = true;
            $data["formData"] = $formData;
        } else {
            $data["success"] = true;
            $data["message"] = "Data not found";
        }

        return response()->json($data,200,[]);
    }

    public function resultsStore(Request $request){
        dd("Pending....");
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);

        $request->validate([
            'session_id'   => 'required',
        ]);

        foreach ($variable as $item) {
            if(true){ 
                DB::table('results')->update([
                    "updated_at" => date("Y-m-d H:i:s"),
                ]);
            } else {
                DB::table('results')->insert([
                    "updated_at" => date("Y-m-d H:i:s"),
                    "created_at" => date("Y-m-d H:i:s"),
                ]);
            }   
        }

        $data["success"] = true;
        $data["message"] = "Updated Successfully"; 
    }

    // *** FEE ***

    public function feeRowEdit(Request $request){
        $apiToken = $request->header("apiToken");
        $auth_user = User::authUser($apiToken);
        $row = DB::table("fee_structures")
            ->where("school_id", $auth_user->client_id)
            ->where("id", $request->id)
            ->first();

        if($row){
            $data["success"] = true;
            $data["row"] = $row;
        } else {
            $data["success"] = false;
            $data["message"] = "Data not found";
        }

        return response()->json($data,200,[]);
    }
    public function feeRowStore(Request $request){
        $apiToken = $request->header("apiToken");
        $auth_user = User::authUser($apiToken);

        $validator = Validator::make($request->all(), [
            'class_id' => ['required'],
            'fee_type_id' => ['required'],
            'amount' => ['required', 'numeric'],
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => $validator->errors()->first(),
            ],422);
        }

        $classId = $request->class_id;
        $frequencyId = ($request->frequency_id === '' || $request->frequency_id === null) ? null : $request->frequency_id;

        DB::beginTransaction();

        try {
            $payload = [
                "school_id" => $auth_user->client_id,
                "class_id" => $classId,
                "fee_type_id" => $request->fee_type_id,
                "frequency_id" => $frequencyId,
                "amount" => $request->amount,
                "fin_year" => $request->fin_year,
                "updated_at" => now(),
            ];

            if($request->id){
                $row = DB::table("fee_structures")
                    ->where("school_id", $auth_user->client_id)
                    ->where("id", $request->id)
                    ->first();

                if(!$row){
                    throw new \Exception("Data not found");
                }

                DB::table("fee_structures")
                    ->where("school_id", $auth_user->client_id)
                    ->where("id", $request->id)
                    ->update($payload);

                $message = "Updated Successfully";
                $rowId = $request->id;
            } else {
                $existing = DB::table("fee_structures")
                    ->where("school_id", $auth_user->client_id)
                    ->where("class_id", $classId)
                    ->where("fee_type_id", $request->fee_type_id)
                    ->when($frequencyId === null, function ($q) {
                        $q->whereNull("frequency_id");
                    }, function ($q) use ($frequencyId) {
                        $q->where("frequency_id", $frequencyId);
                    })
                    ->first();

                if($existing){
                    DB::table("fee_structures")
                        ->where("school_id", $auth_user->client_id)
                        ->where("id", $existing->id)
                        ->update($payload);

                    $message = "Updated Successfully";
                    $rowId = $existing->id;
                } else {
                    $payload["created_at"] = now();
                    $rowId = DB::table("fee_structures")->insertGetId($payload);
                    $message = "Saved Successfully";
                }
            }

            $row = DB::table("fee_structures")
                ->select("fee_structures.*", "fee_frequencies.name as fee_frequency", "fee_types.name as fee_type", "fee_types.description")
                ->leftJoin("fee_types", "fee_types.id", "=", "fee_structures.fee_type_id")
                ->leftJoin("fee_frequencies", "fee_frequencies.id", "=", "fee_structures.frequency_id")
                ->where("fee_structures.school_id", $auth_user->client_id)
                ->where("fee_structures.id", $rowId)
                ->first();

            DB::commit();

            return response()->json([
                "success" => true,
                "message" => $message,
                "row" => $row,
            ],200,[]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ],500,[]);
        }
    }
    public function feeRowDelete(Request $request){
        $apiToken = $request->header("apiToken");
        $auth_user = User::authUser($apiToken);
        $row = DB::table("fee_structures")
            ->where("school_id", $auth_user->client_id)
            ->where("id", $request->id)
            ->first();

        if(!$row){
            return response()->json([
                "success" => false,
                "message" => "Data not found"
            ],200,[]);
        }

        DB::table("fee_structures")
            ->where("school_id", $auth_user->client_id)
            ->where("id", $request->id)
            ->delete();

        return response()->json([
            "success" => true,
            "message" => "Deleted Successfully"
        ],200,[]);
    }    

    // *** FEE ***

    public function feeSubEdit(Request $request){
        $apiToken = $request->header("apiToken");
        $auth_user = User::authUser($apiToken);
        $row = DB::table("class_subjects")->where("school_id", $auth_user->client_id)->where("id", $request->id)->first();

        $data["success"] = true;
        $data["row"] = $row;
        return response()->json($data,200,[]);
    }
    public function feeSubStore(Request $request){
        $apiToken = $request->header("apiToken");
        $auth_user = User::authUser($apiToken);


        $data["success"] = true;
        return response()->json($data,200,[]);
    }
    public function feeSubDelete(Request $request){
        $apiToken = $request->header("apiToken");
        $auth_user = User::authUser($apiToken);

        DB::table("class_subjects")->where("school_id", $auth_user->client_id)->where("id", $request->id)->update([
            "status" => 1
        ]);

        $data["success"] = true;
        return response()->json($data,200,[]);
    }


}
