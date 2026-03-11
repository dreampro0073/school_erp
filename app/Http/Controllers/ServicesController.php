<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\ModelHelper;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ServicesController extends Controller {
    public function index()
    {
        return view('admin.services.index');
    }

    public function initServices(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function storeService(Request $request) {        
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }
}
