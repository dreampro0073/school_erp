<?php

namespace App\Http\Controllers;
use App\Models\FeeType;
use App\Models\User;
use App\Models\ModelHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FeeTypesController extends Controller {
    public function index()
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);


        return view('admin.fee-types.index');
    }

    public function initFeeTypes(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function storeFeeType(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function deleteFeeType(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }
}
