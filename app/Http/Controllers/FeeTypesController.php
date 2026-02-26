<?php

namespace App\Http\Controllers;

use App\Models\FeeType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FeeTypesController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        if (!$this->isPrivOne($authUser)) {
            abort(403);
        }

        return view('admin.fee-types.index');
    }

    public function initFeeTypes(Request $request)
    {
        $user = $this->resolveApiUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if (!$this->isPrivOne($user)) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        if (!Schema::hasTable('fee_types')) {
            return response()->json(['success' => true, 'fee_types' => []]);
        }

        $idColumn = $this->resolveColumn('fee_types', ['id', 'fee_type_id', 'sid']);
        $nameColumn = $this->resolveColumn('fee_types', ['name', 'fee_type', 'fee_type_name', 'title']);
        if (!$idColumn || !$nameColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported fee_types table structure.'], 422);
        }

        $select = [
            "{$idColumn} as id",
            "{$nameColumn} as name",
        ];
        if (Schema::hasColumn('fee_types', 'active')) {
            $select[] = 'active';
        }
        if (Schema::hasColumn('fee_types', 'created_at')) {
            $select[] = 'created_at';
        }

        $feeTypes = FeeType::query()
            ->select($select)
            ->orderBy($idColumn, 'desc')
            ->get();

        return response()->json(['success' => true, 'fee_types' => $feeTypes]);
    }

    public function storeFeeType(Request $request)
    {
        $user = $this->resolveApiUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if (!$this->isPrivOne($user)) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        if (!Schema::hasTable('fee_types')) {
            return response()->json(['success' => false, 'message' => 'fee_types table not found.'], 422);
        }

        $idColumn = $this->resolveColumn('fee_types', ['id', 'fee_type_id', 'sid']);
        $nameColumn = $this->resolveColumn('fee_types', ['name', 'fee_type', 'fee_type_name', 'title']);
        if (!$idColumn || !$nameColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported fee_types table structure.'], 422);
        }

        $payload = $request->validate([
            'id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'active' => ['nullable', 'in:0,1'],
        ]);

        $data = [
            $nameColumn => $payload['name'],
        ];
        if (Schema::hasColumn('fee_types', 'active')) {
            $data['active'] = isset($payload['active']) ? (int) $payload['active'] : 1;
        }
        if (Schema::hasColumn('fee_types', 'updated_at')) {
            $data['updated_at'] = now();
        }

        if (!empty($payload['id'])) {
            FeeType::query()->where($idColumn, (int) $payload['id'])->update($data);
            return response()->json(['success' => true, 'message' => 'Fee type updated successfully.']);
        }

        if (Schema::hasColumn('fee_types', 'created_at')) {
            $data['created_at'] = now();
        }

        FeeType::query()->insert($data);
        return response()->json(['success' => true, 'message' => 'Fee type created successfully.']);
    }

    public function deleteFeeType(Request $request)
    {
        $user = $this->resolveApiUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if (!$this->isPrivOne($user)) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        if (!Schema::hasTable('fee_types')) {
            return response()->json(['success' => false, 'message' => 'fee_types table not found.'], 422);
        }

        $idColumn = $this->resolveColumn('fee_types', ['id', 'fee_type_id', 'sid']);
        if (!$idColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported fee_types table structure.'], 422);
        }

        $payload = $request->validate([
            'id' => ['required', 'integer'],
        ]);

        FeeType::query()->where($idColumn, (int) $payload['id'])->delete();
        return response()->json(['success' => true, 'message' => 'Fee type deleted successfully.']);
    }

    private function resolveApiUser(Request $request): ?User
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return null;
        }

        return $user;
    }

    private function isPrivOne($user): bool
    {
        if (!$user) {
            return false;
        }

        $priv = $user->priv ?? $user->privillage ?? $user->privilege ?? null;
        return (int) $priv === 1;
    }

    private function resolveColumn(string $table, array $candidates): ?string
    {
        foreach ($candidates as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }
        return null;
    }
}

