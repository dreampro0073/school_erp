<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\ModelHelper;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class StandardsController extends Controller {
    public function index()
    {
        return view('admin.standards.index');
    }

    public function initStandards(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function storeStandard(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }
}
