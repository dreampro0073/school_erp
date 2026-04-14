<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AspirantController extends Controller {
    public function dashboard()
    {
        return view('aspirant.dashboard');
    }

    public function subjectsIndex()
    {
        return view('aspirant.subjects.index');
    }

    public function initDashboard(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;
        $data["aspirant"] = $user;

        return response()->json($data,200,[]);
    }

    public function initSubjects(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        $subjects = Subject::get();
        $data["subjects"] = $subjects;
        $data["success"] = true;

        return response()->json($data,200,[]);
    }
}
