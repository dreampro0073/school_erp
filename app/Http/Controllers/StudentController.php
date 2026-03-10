<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index()
    {
        return view('admin.students.index');
    }
    
    public function initStudents(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        $students = Student::latest()->get();

        $data['success'] = true;
        $data['students'] = $students;
        return response()->json($data,200,[]);
    }

    // public function index() {
    //     $students = Student::latest()->get();
    //     return response()->json($students);
    // }

    public function store(Request $request)
    {
      
  
        $user = $this->resolveApiUser($request);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized user.'
            ], 401);
        }

        $data = $request->validate([
            'first_name' => ['required','string','max:255'],
            'last_name' => ['nullable','string','max:255'],
            
            'gender' => ['nullable','string','max:50'],
            'mobile' => ['nullable','string','max:50'],
            'email' => ['nullable','email','max:255'],
            'address' => ['nullable','string','max:1000'],
            'admission_no' => ['nullable','string','max:100'],
            'aadhar_no' => ['nullable','string','max:50'],
            'active' => ['nullable','in:0,1'],
        ]);

     

        $data['name'] = $data['first_name'].' '.$data['last_name'];

        $data['client_id'] = $user->client_id;
        $data['user_id'] = $user->id;

        $student = Student::create($data);

        return response()->json([
            'status' => true,
            'data' => $student
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
