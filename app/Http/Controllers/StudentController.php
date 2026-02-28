<?php

namespace App\Http\Controllers;

use App\Models\Student,App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class StudentController extends Controller
{
    public function index(){
        return view('admin.students.index');
    }
    public function initStudents(Request $request){
        $user = User::resolveApiUser($request, 2);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $clientId = (int) ($user->client_id ?? 0);
        if ($clientId <= 0 || !Schema::hasTable('students') || !Schema::hasColumn('students', 'client_id')) {
            return response()->json(['success' => true, 'students' => []], 200, []);
        }

        $students = Student::query()
            ->where('client_id', $clientId)
            ->latest()
            ->get();

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
        $request->validate([
            'first_name' => 'required'
        ]);

        $student = Student::create($request->all());

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
        $student = Student::findOrFail($id);

        $student->update($request->all());

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

}
