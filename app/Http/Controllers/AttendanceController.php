<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ModelHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('admin.attendance.index');
    }

    public function init(Request $request)
    {
        $admin = User::resolveApiUser($request, 2);
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        if (!Schema::hasTable('attendances')) {
            return response()->json(['success' => false, 'message' => 'attendances table not found.'], 422);
        }

        $clientId = (int) ($admin->client_id ?? 0);
        $payload = $request->validate([
            'type' => ['nullable', 'in:student,teacher'],
            'date' => ['nullable', 'date'],
        ]);

        $type = $payload['type'] ?? 'student';
        $date = $payload['date'] ?? now()->toDateString();

        $people = $this->getPeople($type, $clientId);
        $attendanceRows = Attendance::query()
            ->where('client_id', $clientId)
            ->where('attendance_date', $date)
            ->where('user_type', $type)
            ->get()
            ->keyBy('entity_id');

        $items = [];
        foreach ($people as $person) {
            $attendance = $attendanceRows->get((int) $person['id']);
            $items[] = [
                'id' => (int) $person['id'],
                'user_id' => $person['user_id'] ? (int) $person['user_id'] : null,
                'name' => $person['name'],
                'mobile' => $person['mobile'],
                'active' => (int) ($person['active'] ?? 1),
                'status' => $attendance ? $attendance->status : 'present',
                'remark' => $attendance ? (string) ($attendance->remark ?? '') : '',
            ];
        }

        return response()->json([
            'success' => true,
            'date' => $date,
            'type' => $type,
            'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $admin = User::resolveApiUser($request, 2);
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        if (!Schema::hasTable('attendances')) {
            return response()->json(['success' => false, 'message' => 'attendances table not found.'], 422);
        }

        $data = $request->validate([
            'type' => ['required', 'in:student,teacher'],
            'date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer'],
            'items.*.user_id' => ['nullable', 'integer'],
            'items.*.status' => ['required', 'in:present,absent,late,half_day'],
            'items.*.remark' => ['nullable', 'string'],
        ]);

        $clientId = (int) ($admin->client_id ?? 0);
        $date = $data['date'];
        $type = $data['type'];

        DB::beginTransaction();
        try {
            foreach ($data['items'] as $item) {
                Attendance::query()->updateOrCreate(
                    [
                        'client_id' => $clientId,
                        'attendance_date' => $date,
                        'user_type' => $type,
                        'entity_id' => (int) $item['id'],
                    ],
                    [
                        'user_id' => !empty($item['user_id']) ? (int) $item['user_id'] : null,
                        'status' => $item['status'],
                        'remark' => $item['remark'] ?? null,
                        'marked_by' => (int) $admin->id,
                    ]
                );
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' attendance saved successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function list(Request $request)
    {
        $admin = User::resolveApiUser($request, 2);
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        if (!Schema::hasTable('attendances')) {
            return response()->json(['success' => true, 'rows' => []]);
        }

        $clientId = (int) ($admin->client_id ?? 0);
        $payload = $request->validate([
            'type' => ['nullable', 'in:student,teacher'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
        ]);

        $query = Attendance::query()
            ->where('client_id', $clientId)
            ->orderByDesc('attendance_date')
            ->orderByDesc('id');

        if (!empty($payload['type'])) {
            $query->where('user_type', $payload['type']);
        }
        if (!empty($payload['from_date'])) {
            $query->whereDate('attendance_date', '>=', $payload['from_date']);
        }
        if (!empty($payload['to_date'])) {
            $query->whereDate('attendance_date', '<=', $payload['to_date']);
        }

        $rows = $query->limit(300)->get()->map(function (Attendance $attendance) {
            $name = $this->resolvePersonName($attendance->user_type, (int) $attendance->entity_id);
            return [
                'id' => (int) $attendance->id,
                'date' => $attendance->attendance_date,
                'type' => $attendance->user_type,
                'entity_id' => (int) $attendance->entity_id,
                'name' => $name,
                'status' => $attendance->status,
                'remark' => $attendance->remark,
                'marked_by' => $attendance->marked_by,
                'created_at' => $attendance->created_at,
            ];
        })->all();

        return response()->json(['success' => true, 'rows' => $rows]);
    }

    private function getPeople(string $type, int $clientId): array
    {
        $table = $type === 'teacher' ? 'teachers' : 'students';
        if (!Schema::hasTable($table)) {
            return [];
        }

        $idCol = ModelHelper::resolveColumn($table, ['id', $type . '_id', 'sid']);
        $firstNameCol = ModelHelper::resolveColumn($table, ['first_name', 'name', $type . '_name']);
        $lastNameCol = ModelHelper::resolveColumn($table, ['last_name']);
        if (!$idCol || !$firstNameCol) {
            return [];
        }

        $select = [
            "{$idCol} as id",
            "{$firstNameCol} as first_name",
        ];
        if ($lastNameCol) {
            $select[] = "{$lastNameCol} as last_name";
        }
        if (Schema::hasColumn($table, 'user_id')) {
            $select[] = 'user_id';
        }
        if (Schema::hasColumn($table, 'mobile')) {
            $select[] = 'mobile';
        }
        if (Schema::hasColumn($table, 'active')) {
            $select[] = 'active';
        }

        $query = DB::table($table)->select($select);
        ModelHelper::applyClientScope($query, $table, $clientId);
        $query->orderBy($idCol, 'desc');

        return $query->get()->map(function ($row) {
            $row = (array) $row;
            $name = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
            return [
                'id' => (int) ($row['id'] ?? 0),
                'name' => $name !== '' ? $name : 'Unknown',
                'mobile' => $row['mobile'] ?? null,
                'user_id' => $row['user_id'] ?? null,
                'active' => isset($row['active']) ? (int) $row['active'] : 1,
            ];
        })->all();
    }

    private function resolvePersonName(string $type, int $entityId): string
    {
        $table = $type === 'teacher' ? 'teachers' : 'students';
        if (!Schema::hasTable($table)) {
            return 'N/A';
        }

        $idCol = ModelHelper::resolveColumn($table, ['id', $type . '_id', 'sid']);
        $firstNameCol = ModelHelper::resolveColumn($table, ['first_name', 'name', $type . '_name']);
        $lastNameCol = ModelHelper::resolveColumn($table, ['last_name']);
        if (!$idCol || !$firstNameCol) {
            return 'N/A';
        }

        $query = DB::table($table)->where($idCol, $entityId);
        $columns = [$firstNameCol . ' as first_name'];
        if ($lastNameCol) {
            $columns[] = $lastNameCol . ' as last_name';
        }
        $person = $query->first($columns);
        if (!$person) {
            return 'N/A';
        }

        $name = trim(($person->first_name ?? '') . ' ' . ($person->last_name ?? ''));
        return $name !== '' ? $name : 'N/A';
    }
}
