<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

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
        $limit = $request->limit;
        $query = Student::query();

        if($request->search){
            $query->where('first_name','like','%'.$request->search.'%');
        }

        $students = $query->orderBy('id','DESC')->paginate($limit);

        return response()->json([
            "success" => true,
            "data" => $students
        ]);

    }

    public function initDetails(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $student_token = $request->student_token;

        $student = Student::where('unique_id',$student_token)->first();
        return response()->json([
            "success" => true,
            "student" => $student
        ]); 
        

    }
    public function storeStudent(Request $request){
        $authUser = User::resolveApiUser($request);

        $data = $request->validate([
            'first_name' => ['required','string','max:255'],
            'last_name' => ['nullable','string','max:255'],
            'gender' => ['required'],
            'dob' => ['required'],
            'mobile' => ['required','digits:10'],
            'email' => ['required','email','max:255'],
            'admission_no' => ['nullable','string','max:100'],
            'aadhar_no'=> ['required','digits:12'], 
            'residential_address' => ['required'],
            'permanent_address' => ['required'],
        ]);

        $p_valid = $request->validate([
            'father_name' => ['required','string','max:255'],
            'father_email' => ['required','email','max:255'],
            'father_mobile' => ['required','digits:10'],
            'father_aadhar_no' => ['required','digits:12'],
            'mother_name' => ['required','string','max:255'],
            'mother_aadhar_no' => ['required','digits:12'],
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

        $user = new User;
        $password = User::getRandPassword();
        $user->password = Hash::make($password);
        $user->name = $data['first_name'].' '.$data['last_name'];
        $user->start_date = $authUser->start_date;
        $user->perent_user_id = $authUser->id;
        $user->password_check = $password;
        $user->org_id = $authUser->org_id;
        $user->client_id = $authUser->client_id;
        $user->priv = 4;
        $user->email = $data['email'] ?? null;
        $user->save();

        $parentUser = new User;
        $parentPassword = User::getRandPassword();
        $parentUser->name = $parent_data['father_name'];
        $parentUser->email = $parent_data['father_email'];
        $parentUser->password = Hash::make($parentPassword);
        $parentUser->password_check = $parentPassword;
        $parentUser->perent_user_id = $authUser->id;
        $parentUser->org_id = $authUser->org_id;
        $parentUser->client_id = $authUser->client_id;

        $parentUser->priv = 5;
        $parentUser->save();

        $data['name'] = $data['first_name'].' '.$data['last_name'];
        $data['dob'] = date("Y-m-d",strtotime($request->dob));
        $data['client_id'] = $parent_data['client_id'] = $authUser->client_id;
        $data['user_id'] = $user->id;

        $data['unique_id'] = strtotime('now').$authUser->client_id.$authUser->id;

        $student = Student::create($data);

        $parent_data['student_id'] = $student->id;
        $parent_data['user_id'] = $parentUser->id;

        $parent_data['unique_id'] = strtotime('now').$authUser->client_id.$authUser->id.$student->id;

        $parent = StudentParent::create($parent_data);

        return response()->json([
            'success' => true,
            'message' => "Successfully added",
            'student' => $student
        ]);
    }

    public function show($id)
    {
        return Student::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = $this->resolveApiUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $student = Student::findOrFail($id);

        $data = $request->validate([
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            // 'dob' => ['sometimes', 'nullable', 'date'],
            'gender' => ['sometimes', 'nullable', 'string', 'max:50'],
            'mobile' => ['sometimes', 'nullable', 'string', 'max:50'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'address' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'admission_no' => ['sometimes', 'nullable', 'string', 'max:100'],
            'aadhar_no' => ['sometimes', 'nullable', 'string', 'max:50'],
            'active' => ['sometimes', 'nullable', 'in:0,1'],
        ]);
        if (array_key_exists('active', $data)) {
            $data['active'] = (int) $data['active'];
        }

        $student->update($data);

        return response()->json([
            'status' => true,
            'data' => $student
        ]);
    }

    public function destroy($id)
    {
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
}
