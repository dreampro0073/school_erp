<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Student, App\Models\Teacher;
// use App\Models\AttendanceStatus;
// use App\Models\ModelHelper;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminController extends Controller {
    public function dashboard()
    {

        return view('admin.dashboard');
    }

    public function initDashboard(Request $request)
    {

        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        if($user && $user->priv == 2){

            $parent_id = $user->parent_id;

            $students["active_students"] = User::clientUsersCount($parent_id,4, 0)->count();
            $students["inactive_students"] = User::clientUsersCount($parent_id,4, 1)->count();
            $students["total_students"] = User::clientUsersCount($parent_id, 4)->count();
            
            $teachers["active_teachers"] = User::clientUsersCount($parent_id, 3, 0)->count();
            $teachers["inactive_teachers"] = User::clientUsersCount($parent_id, 3, 1)->count();
            $teachers["total_teachers"] = User::clientUsersCount($parent_id, 3)->count();

            $data["students"] = $students;
            $data["teachers"] = $teachers;
            $data["success"] = true;
        } else {
            $data = ['success' => false, 'message' => 'Unauthorized user.'];
        }

        return response()->json($data,200,[]);
    }

    public function teachersIndex() {
        return view('admin.teachers.index');
    }

    public function initTeachers(Request $request) {
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);


        $teachers = Teacher::clientTeachersLists($auth_user->parent_id);
        foreach ($teachers as $teacher) {
            $teacher->enc_id = Crypt::encryptString($row->teacher_id);
            $teacher->dob = date("d-m-Y", strtotime($row->dob));
            $teacher->joining_date = date("d-m-Y", strtotime($row->joining_date));
            $teacher->resign_date = date("d-m-Y", strtotime($row->resign_date));
        }

        return response()->json([
            'success' => true,
            'teachers' => $teachers,
        ]);
    }

    public function addTeacherPage(?string $teacher = null) {
        return view('admin.teachers.form', [
            'teacherToken' => $teacher,
        ]);
    }

    public function getTeacher(Request $request) {
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);
        if($auth_user){
            if($request->enc_id){
                $teacherId = $request->enc_id ? Crypt::decryptString($request->enc_id) : "";
                $teacher = Teacher::find($teacherId);
                $user = User::find($teacher->user_id);
                if($user){
                    if($parent_id != $user->parent_id){
                        $data['message'] = "Not authorized !";
                        $data['success'] = false; 
                        return Response::json($data,200,[]);   
                    }
                }

                $bank_details = DB::table("bank_details")->where("user_id", $user->id)->first();
                // *** Hold ***
                // $salary_structure = DB::table("salary_structures")->where("user_id", $user_id)->get();

                $data["bank_details"] = $bank_details;
                $data["user"] = $user;
                $data["teacher"] = $teacher;
            }
            $data["success"] = true;

        } else {
            $data["success"] = false;
            $data["message"] = "Invalid Request";
        }

        return response()->json($data,200,[]);
    }

    public function storeTeacher(Request $request) {
        $apiToken = $request->header('apiToken');
        $auth_user = User::authUser($apiToken);
        if ($auth_user->priv == 2) {

            $parent_id = $admin->parent_id;
            $teacherId = $request->enc_id ? Crypt::decryptString($request->enc_id) : "";

            $teacher = Teacher::find($teacherId);

            $cre = $request->all();
            $rules = [
                'name' => 'required',
                'level' => 'required',
                'company_admin' => 'required',
                'user_admin' => 'required',
            ];

            if(!$teacherId){
                $rules['username'] = 'required|email|unique:users';
            } else {
                $user_id = $teacher->user_id;
                $user = User::find($user_id);
                
                if($parent_id != $user->parent_id){
                    $data['message'] = "Not authorized !";
                    $data['success'] = false; 
                    return Response::json($data,200,[]);   
                }

                $rules['username'] = 'required|email|unique:users,username,'.$user_id;
            } 

            $validator = Validator::make($cre,$rules);

            if ($validator->fails()) {

                $error = "";
                $messages = $validator->messages();
                foreach($messages->all() as $message){
                    $error = $message;
                    break;
                }
                $data["success"] = false;
                $data["message"] = $error;

            } else {
                if ($user && $teacher) {
                    $data['message'] = "Teacher details Successfully Updated";
                } else {
                    $data['message'] = "Teacher Successfully Stored";
                    $teacher = new Teacher;
                    $user = new User;
                    $password = User::getRandPassword();
                    $user->password = Hash::make($password);
                    $user->start_date = $auth_user->start_date;
                    $user->parent_id = $parent_id;
                    $user->check_password = $password;
                    $user->save(); 

                    $teacher->user_id = $user->id;
                    $user->org_id = $teacher->school_id = $auth_user->org_id;
                    $user->email = $teacher->email = $auth_user->email;
                } 

                $user->priv = 3;
                $user->name = $teacher->name = $request->name.' '.$request->last_name;
                $user->username = $request->username;
                $user->mobile = $request->mobile; 
                $user->address = $request->address; 
                $user->end_date = $auth_user->end_date;
                $user->active = 0;
                $user->save(); 

                $teacher->user_id = $user->id;
                $teacher->school_id = $auth_user->org_id;
                $teacher->status = 0;
                $teacher->joining_date = date("Y-m-d", strtotime($request->joining_date));
                $teacher->dob = date("Y-m-d", strtotime($request->dob));



                $user->save(); 
                $teacher->save();
            } 
        } else {
            $data = ['success' => false, 'message' => 'Unauthorized user.'];
        }

        return response()->json($data,200,[]);
    }

    // public function studentsIndex() {
    //     return view('admin.students.index');
    // }

    // public function addStudentPage(?string $student = null) {
    //     return view('admin.students.form', [
    //         'studentToken' => $student,
    //     ]);
    // }

    // public function initStudents(Request $request) {
    //     $apiToken = $request->header('apiToken');
    //     $user = User::authUser($apiToken);
    //     $data["success"] = true;
        
    //     return response()->json($data,200,[]);
    // }

   

    // public function storeStudent(Request $request) {
    //     $apiToken = $request->header('apiToken');
    //     $auth_user = User::authUser($apiToken);
    //     if ($auth_user->priv == 2) {

    //         $parent_id = $admin->parent_id;
    //         $studentId = $request->enc_id ? Crypt::decryptString($request->enc_id) : "";

    //         $student = Student::find($studentId);

    //         $cre = $request->all();
    //         $rules = [
    //             'name' => 'required',
    //             'level' => 'required',
    //             'company_admin' => 'required',
    //             'user_admin' => 'required',
    //         ];

    //         if(!$studentId){
    //             $rules['username'] = 'required|email|unique:users';
    //         } else {
    //             $user_id = $student->user_id;
    //             $user = User::find($user_id);
                
    //             if($parent_id != $user->parent_id){
    //                 $data['message'] = "Not authorized !";
    //                 $data['success'] = false; 
    //                 return Response::json($data,200,[]);   
    //             }

    //             $rules['username'] = 'required|email|unique:users,username,'.$user_id;
    //         } 

    //         $validator = Validator::make($cre,$rules);

    //         if ($validator->fails()) {

    //             $error = "";
    //             $messages = $validator->messages();
    //             foreach($messages->all() as $message){
    //                 $error = $message;
    //                 break;
    //             }
    //             $data["success"] = false;
    //             $data["message"] = $error;

    //         } else {
    //             if ($user && $student) {
    //                 $data['message'] = "Student details Successfully Updated";
    //             } else {
    //                 $data['message'] = "Student Successfully Stored";
    //                 $student = new Student;
    //                 $user = new User;
    //                 $password = User::getRandPassword();
    //                 $user->password = Hash::make($password);
    //                 $user->start_date = $auth_user->start_date;
    //                 $user->parent_id = $parent_id;
    //                 $user->check_password = $password;
    //                 $user->save(); 

    //                 $student->user_id = $user->id;
    //                 $user->org_id = $student->school_id = $auth_user->org_id;
    //                 $user->email = $student->email = $auth_user->email;
    //             } 

    //             $user->priv = 3;
    //             $user->name = $student->name = $request->name.' '.$request->last_name;
    //             $user->username = $request->username;
    //             $user->mobile = $request->mobile; 
    //             $user->address = $request->address; 
    //             $user->end_date = $auth_user->end_date;
    //             $user->active = 0;
    //             $user->save(); 

    //             $student->user_id = $user->id;
    //             $student->school_id = $auth_user->org_id;
    //             $student->status = 0;
    //             $student->joining_date = date("Y-m-d", strtotime($request->joining_date));
    //             $student->dob = date("Y-m-d", strtotime($request->dob));



    //             $user->save(); 
    //             $student->save();
    //         } 
    //     } else {
    //         $data = ['success' => false, 'message' => 'Unauthorized user.'];
    //     }

    //     return response()->json($data,200,[]);
    // }



    public function incomesPage()
    {
        return view('admin.finance.incomes');
    }

    public function initIncomes(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function incomeEntriesPage()
    {
        return view('admin.finance.income_entries');
    }

    public function initIncomeEntries(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function storeIncome(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function expensesPage()
    {
        return view('admin.finance.expenses');
    }

    public function initExpenses(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function initExpenseEntries(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function expenseEntriesPage(){
        return view('admin.finance.expense_entries');
    }

    public function storeExpense(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

}
