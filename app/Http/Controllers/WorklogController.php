<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Worklog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorklogController extends Controller {
    public function index(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function store(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }
}
