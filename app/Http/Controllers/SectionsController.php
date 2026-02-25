<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SectionsController extends Controller
{
    public function index()
    {
        return view('admin.sections.index');
    }

    public function initSections(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!Schema::hasTable('sections')) {
            return response()->json(['success' => true, 'sections' => []]);
        }

        $idColumn = $this->resolveColumn('sections', ['id', 'section_id', 'sid']);
        $nameColumn = $this->resolveColumn('sections', ['name', 'section_name', 'title']);
        if (!$idColumn || !$nameColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported sections table structure.'], 422);
        }

        $select = [
            "{$idColumn} as id",
            "{$nameColumn} as name",
        ];
        if (Schema::hasColumn('sections', 'active')) {
            $select[] = 'active';
        }
        if (Schema::hasColumn('sections', 'created_at')) {
            $select[] = 'created_at';
        }

        $sections = Section::query()
            ->select($select)
            ->orderBy($idColumn, 'desc')
            ->get();

        return response()->json(['success' => true, 'sections' => $sections]);
    }

    public function storeSection(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!Schema::hasTable('sections')) {
            return response()->json(['success' => false, 'message' => 'sections table not found.'], 422);
        }

        $idColumn = $this->resolveColumn('sections', ['id', 'section_id', 'sid']);
        $nameColumn = $this->resolveColumn('sections', ['name', 'section_name', 'title']);
        if (!$idColumn || !$nameColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported sections table structure.'], 422);
        }

        $payload = $request->validate([
            'id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'active' => ['nullable', 'in:0,1'],
        ]);

        $data = [
            $nameColumn => $payload['name'],
        ];
        if (Schema::hasColumn('sections', 'active')) {
            $data['active'] = isset($payload['active']) ? (int) $payload['active'] : 1;
        }
        if (Schema::hasColumn('sections', 'updated_at')) {
            $data['updated_at'] = now();
        }

        if (!empty($payload['id'])) {
            Section::query()->where($idColumn, (int) $payload['id'])->update($data);
            return response()->json(['success' => true, 'message' => 'Section updated successfully.']);
        }

        if (Schema::hasColumn('sections', 'created_at')) {
            $data['created_at'] = now();
        }

        Section::query()->insert($data);
        return response()->json(['success' => true, 'message' => 'Section created successfully.']);
    }

    public function deleteSection(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!Schema::hasTable('sections')) {
            return response()->json(['success' => false, 'message' => 'sections table not found.'], 422);
        }

        $idColumn = $this->resolveColumn('sections', ['id', 'section_id', 'sid']);
        if (!$idColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported sections table structure.'], 422);
        }

        $payload = $request->validate([
            'id' => ['required', 'integer'],
        ]);

        Section::query()->where($idColumn, (int) $payload['id'])->delete();
        return response()->json(['success' => true, 'message' => 'Section deleted successfully.']);
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
