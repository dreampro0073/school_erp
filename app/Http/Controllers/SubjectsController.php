<?php

namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\User;
use App\Models\ModelHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SubjectsController extends Controller {
    public function index()
    {
        $authUser = auth()->user();
        if (!User::isPrivOne($authUser)) {
            abort(403);
        }

        return view('admin.subjects.index');
    }

    public function initSubjects(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if (!User::isPrivOne($user)) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        if (!Schema::hasTable('subjects')) {
            return response()->json(['success' => true, 'subjects' => []]);
        }

        $idColumn = ModelHelper::resolveColumn('subjects', ['id', 'subject_id', 'sid']);
        $nameColumn = ModelHelper::resolveColumn('subjects', ['name', 'subject_name', 'title']);
        if (!$idColumn || !$nameColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported subjects table structure.'], 422);
        }

        $select = [
            "{$idColumn} as id",
            "{$nameColumn} as name",
        ];
        if (Schema::hasColumn('subjects', 'active')) {
            $select[] = 'active';
        }
        if (Schema::hasColumn('subjects', 'created_at')) {
            $select[] = 'created_at';
        }

        $subjects = Subject::query()
            ->select($select)
            ->orderBy($idColumn, 'desc')
            ->get();

        return response()->json(['success' => true, 'subjects' => $subjects]);
    }

    public function storeSubject(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if (!User::isPrivOne($user)) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        if (!Schema::hasTable('subjects')) {
            return response()->json(['success' => false, 'message' => 'subjects table not found.'], 422);
        }

        $idColumn = ModelHelper::resolveColumn('subjects', ['id', 'subject_id', 'sid']);
        $nameColumn = ModelHelper::resolveColumn('subjects', ['name', 'subject_name', 'title']);
        if (!$idColumn || !$nameColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported subjects table structure.'], 422);
        }

        $payload = $request->validate([
            'id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'active' => ['nullable', 'in:0,1'],
        ]);

        $data = [
            $nameColumn => $payload['name'],
        ];
        if (Schema::hasColumn('subjects', 'active')) {
            $data['active'] = isset($payload['active']) ? (int) $payload['active'] : 1;
        }
        if (Schema::hasColumn('subjects', 'updated_at')) {
            $data['updated_at'] = now();
        }

        if (!empty($payload['id'])) {
            Subject::query()->where($idColumn, (int) $payload['id'])->update($data);
            return response()->json(['success' => true, 'message' => 'Subject updated successfully.']);
        }

        if (Schema::hasColumn('subjects', 'created_at')) {
            $data['created_at'] = now();
        }

        Subject::query()->insert($data);
        return response()->json(['success' => true, 'message' => 'Subject created successfully.']);
    }

    public function deleteSubject(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if (!User::isPrivOne($user)) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        if (!Schema::hasTable('subjects')) {
            return response()->json(['success' => false, 'message' => 'subjects table not found.'], 422);
        }

        $idColumn = ModelHelper::resolveColumn('subjects', ['id', 'subject_id', 'sid']);
        if (!$idColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported subjects table structure.'], 422);
        }

        $payload = $request->validate([
            'id' => ['required', 'integer'],
        ]);

        Subject::query()->where($idColumn, (int) $payload['id'])->delete();
        return response()->json(['success' => true, 'message' => 'Subject deleted successfully.']);
    }
}
