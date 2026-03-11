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

    public function initStandards(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!Schema::hasTable('standards')) {
            return response()->json(['success' => true, 'standards' => []]);
        }

        $idColumn = ModelHelper::resolveColumn('standards', ['id', 'standard_id', 'sid']);
        $nameColumn = ModelHelper::resolveColumn('standards', ['name', 'standard_name', 'title']);
        if (!$idColumn || !$nameColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported standards table structure.'], 422);
        }

        $select = [
            "{$idColumn} as id",
            "{$nameColumn} as name",
        ];
        if (Schema::hasColumn('standards', 'active')) {
            $select[] = 'active';
        }
        if (Schema::hasColumn('standards', 'created_at')) {
            $select[] = 'created_at';
        }

        $standards = Standard::query()
            ->select($select)
            ->orderBy($idColumn, 'desc')
            ->get();

        return response()->json(['success' => true, 'standards' => $standards]);
    }

    public function storeStandard(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!Schema::hasTable('standards')) {
            return response()->json(['success' => false, 'message' => 'standards table not found.'], 422);
        }

        $idColumn = ModelHelper::resolveColumn('standards', ['id', 'standard_id', 'sid']);
        $nameColumn = ModelHelper::resolveColumn('standards', ['name', 'standard_name', 'title']);
        if (!$idColumn || !$nameColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported standards table structure.'], 422);
        }

        $payload = $request->validate([
            'id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'active' => ['nullable', 'in:0,1'],
        ]);

        $data = [
            $nameColumn => $payload['name'],
        ];
        if (Schema::hasColumn('standards', 'active')) {
            $data['active'] = isset($payload['active']) ? (int) $payload['active'] : 1;
        }
        if (Schema::hasColumn('standards', 'updated_at')) {
            $data['updated_at'] = now();
        }

        if (!empty($payload['id'])) {
            Standard::query()->where($idColumn, (int) $payload['id'])->update($data);
            return response()->json(['success' => true, 'message' => 'Standard updated successfully.']);
        }

        if (Schema::hasColumn('standards', 'created_at')) {
            $data['created_at'] = now();
        }

        Standard::query()->insert($data);
        return response()->json(['success' => true, 'message' => 'Standard created successfully.']);
    }

    public function deleteStandard(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!Schema::hasTable('standards')) {
            return response()->json(['success' => false, 'message' => 'standards table not found.'], 422);
        }

        $idColumn = ModelHelper::resolveColumn('standards', ['id', 'standard_id', 'sid']);
        if (!$idColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported standards table structure.'], 422);
        }

        $payload = $request->validate([
            'id' => ['required', 'integer'],
        ]);

        Standard::query()->where($idColumn, (int) $payload['id'])->delete();
        return response()->json(['success' => true, 'message' => 'Standard deleted successfully.']);
    }
}
