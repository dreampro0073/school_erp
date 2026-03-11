<?php

namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\User;
use App\Models\ModelHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SubjectsController extends Controller {
    public function index() {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        return view('admin.subjects.index');
    }

    public function initSubjects(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function storeSubject(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }
}
