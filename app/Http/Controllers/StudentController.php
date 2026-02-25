<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(){
        return view('admin.students.index');
    }
    public function initStudents(Request $request){
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
