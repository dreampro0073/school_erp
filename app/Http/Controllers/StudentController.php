<?php

namespace App\Http\Controllers;

use App\Models\Student,App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(){
        return view('admin.students.index');
    }
    public function initStudents(Request $request)
    {
        $limit = $request->limit;
        $query = Student::query();

        if($request->search){
            $query->where('first_name','like','%'.$request->search.'%');
        }

        $students = $query->paginate($limit);

        return response()->json([
            "success" => true,
            "data" => $students
        ]);

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
