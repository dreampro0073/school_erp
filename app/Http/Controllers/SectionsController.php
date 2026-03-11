<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\ModelHelper;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SectionsController extends Controller {
    public function index()
    {
        return view('admin.sections.index');
    }

    public function initSections(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function storeSection(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

}
