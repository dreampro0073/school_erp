<?php

namespace App\Http\Controllers;

use App\Models\AttendanceStatus;
use App\Models\ModelHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TeacherController extends Controller
{
    public function dashboard()
    {
        return view('teachers.dashboard');
    }

    public function initDashboard(Request $request)
    {
        $user = User::resolveApiUser($request, 3);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $clientId = (int) ($user->client_id ?? 0);
        $statuses = $this->getAttendanceStatuses();
        $statusMap = collect($statuses)->keyBy('code');
        $teacherProfile = $this->resolveTeacherProfile($user, $clientId);
        $studentsStats = $this->getTableStats(['students'], $clientId);
        $teachersStats = $this->getTableStats(['teachers'], $clientId);
        $sectionsStats = $this->getTableStats(['sections'], $clientId);
        $subjectsStats = $this->getTableStats(['subjects'], $clientId);

        $cards = [
            [
                'label' => 'Students',
                'value' => $studentsStats['total'],
                'active' => $studentsStats['active'],
                'inactive' => $studentsStats['inactive'],
                'icon' => 'ri-graduation-cap-line',
                'gradientClass' => 'gradient-bg-end-1',
                'iconClass' => 'bg-warning-600',
            ],
            [
                'label' => 'Teachers',
                'value' => $teachersStats['total'],
                'active' => $teachersStats['active'],
                'inactive' => $teachersStats['inactive'],
                'icon' => 'ri-user-star-line',
                'gradientClass' => 'gradient-bg-end-2',
                'iconClass' => 'bg-blue-600',
            ],
            [
                'label' => 'Sections',
                'value' => $sectionsStats['total'],
                'active' => $sectionsStats['active'],
                'inactive' => $sectionsStats['inactive'],
                'icon' => 'ri-layout-grid-line',
                'gradientClass' => 'gradient-bg-end-3',
                'iconClass' => 'bg-purple-600',
            ],
            [
                'label' => 'Subjects',
                'value' => $subjectsStats['total'],
                'active' => $subjectsStats['active'],
                'inactive' => $subjectsStats['inactive'],
                'icon' => 'ri-book-open-line',
                'gradientClass' => 'gradient-bg-end-4',
                'iconClass' => 'bg-primary-600',
            ],
        ];

        $today = now()->toDateString();
        $studentAttendanceToday = $this->getAttendanceBreakdown($clientId, 'student', $today, $statuses);
        $teacherAttendanceToday = $this->getAttendanceBreakdown($clientId, 'teacher', $today, $statuses);
        $myAttendance = $this->getTeacherAttendanceLastDays(
            $clientId,
            $teacherProfile['teacher_id'],
            (int) ($user->id ?? 0),
            30,
            $statuses
        );
        $myAttendanceTotal = array_reduce($myAttendance, function (int $carry, array $row) {
            return $carry + (int) ($row['count'] ?? 0);
        }, 0);
        $recentStudentAttendance = $this->getRecentAttendance($clientId, 'student', 6, $statusMap);

        return response()->json([
            'success' => true,
            'today' => now()->format('d M Y'),
            'teacherProfile' => $teacherProfile,
            'cards' => $cards,
            'studentAttendanceToday' => $studentAttendanceToday,
            'teacherAttendanceToday' => $teacherAttendanceToday,
            'myAttendance' => $myAttendance,
            'myAttendanceTotal' => $myAttendanceTotal,
            'recentStudentAttendance' => $recentStudentAttendance,
        ]);
    }

    private function resolveTeacherProfile(object $user, int $clientId): array
    {
        $profile = [
            'teacher_id' => null,
            'name' => (string) ($user->name ?? 'Teacher'),
            'email' => (string) ($user->email ?? 'N/A'),
            'mobile' => 'N/A',
            'active' => (int) ($user->active ?? 1),
        ];

        if (!Schema::hasTable('teachers')) {
            return $profile;
        }

        $idCol = ModelHelper::resolveColumn('teachers', ['id', 'teacher_id']);
        $nameCol = ModelHelper::resolveColumn('teachers', ['name', 'first_name', 'teacher_name']);
        $lastNameCol = ModelHelper::resolveColumn('teachers', ['last_name']);
        if (!$idCol || !$nameCol) {
            return $profile;
        }

        $query = DB::table('teachers');
        if ($clientId > 0 && Schema::hasColumn('teachers', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        if (Schema::hasColumn('teachers', 'user_id') && isset($user->id)) {
            $query->where('user_id', (int) $user->id);
        } elseif (Schema::hasColumn('teachers', 'email') && !empty($user->email)) {
            $query->where('email', (string) $user->email);
        } else {
            return $profile;
        }

        $select = ["{$idCol} as id", "{$nameCol} as first_name"];
        if ($lastNameCol) {
            $select[] = "{$lastNameCol} as last_name";
        }
        if (Schema::hasColumn('teachers', 'mobile')) {
            $select[] = 'mobile';
        }
        if (Schema::hasColumn('teachers', 'active')) {
            $select[] = 'active';
        }
        if (Schema::hasColumn('teachers', 'email')) {
            $select[] = 'email';
        }

        $row = $query->first($select);
        if (!$row) {
            return $profile;
        }

        $fullName = trim((string) ($row->first_name ?? '') . ' ' . (string) ($row->last_name ?? ''));
        $profile['teacher_id'] = (int) ($row->id ?? 0);
        $profile['name'] = $fullName !== '' ? $fullName : $profile['name'];
        $profile['email'] = (string) ($row->email ?? $profile['email']);
        $profile['mobile'] = (string) ($row->mobile ?? $profile['mobile']);
        $profile['active'] = isset($row->active) ? (int) $row->active : $profile['active'];

        return $profile;
    }

    private function getTableStats(array $tableCandidates, int $clientId): array
    {
        $table = ModelHelper::resolveTable($tableCandidates);
        if (!$table || $clientId <= 0 || !Schema::hasColumn($table, 'client_id')) {
            return ['active' => 0, 'inactive' => 0, 'total' => 0];
        }

        $base = DB::table($table)->where('client_id', $clientId);
        $total = (int) (clone $base)->count();
        if (!Schema::hasColumn($table, 'active')) {
            return ['active' => $total, 'inactive' => 0, 'total' => $total];
        }

        return [
            'active' => (int) (clone $base)->where('active', 1)->count(),
            'inactive' => (int) (clone $base)->where('active', 0)->count(),
            'total' => $total,
        ];
    }

    private function getAttendanceStatuses(): array
    {
        if (!Schema::hasTable('attendance_statuses')) {
            return [];
        }

        return AttendanceStatus::query()
            ->where('active', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['code', 'label', 'badge_class', 'bar_class'])
            ->map(function (AttendanceStatus $status) {
                return [
                    'code' => (string) $status->code,
                    'label' => (string) $status->label,
                    'badge_class' => (string) ($status->badge_class ?? 'text-bg-secondary'),
                    'bar_class' => (string) ($status->bar_class ?? 'bg-neutral-300'),
                ];
            })
            ->all();
    }

    private function getAttendanceBreakdown(int $clientId, string $type, string $date, array $statuses): array
    {
        $counts = [];
        foreach ($statuses as $status) {
            $counts[(string) ($status['code'] ?? '')] = 0;
        }

        if (Schema::hasTable('attendances') && $clientId > 0) {
            $rows = DB::table('attendances')
                ->select('status', DB::raw('COUNT(*) as total'))
                ->where('client_id', $clientId)
                ->where('user_type', $type)
                ->whereDate('attendance_date', $date)
                ->groupBy('status')
                ->get();

            foreach ($rows as $row) {
                $code = (string) ($row->status ?? '');
                if (array_key_exists($code, $counts)) {
                    $counts[$code] = (int) ($row->total ?? 0);
                }
            }
        }

        $grandTotal = array_sum($counts);
        return array_map(function (array $status) use ($counts, $grandTotal) {
            $code = (string) ($status['code'] ?? '');
            $count = (int) ($counts[$code] ?? 0);
            return [
                'code' => $code,
                'label' => (string) ($status['label'] ?? $code),
                'count' => $count,
                'percent' => $grandTotal > 0 ? (int) round(($count / $grandTotal) * 100) : 0,
                'badge_class' => (string) ($status['badge_class'] ?? 'text-bg-secondary'),
                'bar_class' => (string) ($status['bar_class'] ?? 'bg-neutral-300'),
            ];
        }, $statuses);
    }

    private function getTeacherAttendanceLastDays(
        int $clientId,
        ?int $teacherId,
        int $userId,
        int $days,
        array $statuses
    ): array {
        $counts = [];
        foreach ($statuses as $status) {
            $counts[(string) ($status['code'] ?? '')] = 0;
        }

        if (Schema::hasTable('attendances') && $clientId > 0) {
            $query = DB::table('attendances')
                ->select('status', DB::raw('COUNT(*) as total'))
                ->where('client_id', $clientId)
                ->where('user_type', 'teacher')
                ->whereDate('attendance_date', '>=', now()->subDays($days - 1)->toDateString());

            if ($teacherId) {
                $query->where('entity_id', $teacherId);
            } elseif ($userId > 0) {
                $query->where('user_id', $userId);
            } else {
                return [];
            }

            $rows = $query->groupBy('status')->get();
            foreach ($rows as $row) {
                $code = (string) ($row->status ?? '');
                if (array_key_exists($code, $counts)) {
                    $counts[$code] = (int) ($row->total ?? 0);
                }
            }
        }

        return array_map(function (array $status) use ($counts) {
            $code = (string) ($status['code'] ?? '');
            return [
                'code' => $code,
                'label' => (string) ($status['label'] ?? $code),
                'count' => (int) ($counts[$code] ?? 0),
                'badge_class' => (string) ($status['badge_class'] ?? 'text-bg-secondary'),
            ];
        }, $statuses);
    }

    private function getRecentAttendance(int $clientId, string $type, int $limit, $statusMap): array
    {
        if (!Schema::hasTable('attendances') || $clientId <= 0) {
            return [];
        }

        $rows = DB::table('attendances')
            ->where('client_id', $clientId)
            ->where('user_type', $type)
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();

        return $rows->map(function ($row) use ($type, $statusMap) {
            $statusCode = (string) ($row->status ?? '');
            $status = $statusMap->get($statusCode, []);
            return [
                'date' => (string) ($row->attendance_date ?? ''),
                'name' => $this->resolveEntityName($type, (int) ($row->entity_id ?? 0)),
                'status' => $statusCode,
                'status_label' => (string) ($status['label'] ?? ucwords(str_replace('_', ' ', $statusCode))),
                'status_badge_class' => (string) ($status['badge_class'] ?? 'text-bg-secondary'),
                'remark' => (string) ($row->remark ?? ''),
            ];
        })->all();
    }

    private function resolveEntityName(string $type, int $entityId): string
    {
        $table = $type === 'teacher' ? 'teachers' : 'students';
        if (!Schema::hasTable($table) || $entityId <= 0) {
            return 'N/A';
        }

        $idCol = ModelHelper::resolveColumn($table, ['id', $type . '_id', 'sid']);
        $firstNameCol = ModelHelper::resolveColumn($table, ['first_name', 'name', $type . '_name']);
        $lastNameCol = ModelHelper::resolveColumn($table, ['last_name']);
        if (!$idCol || !$firstNameCol) {
            return 'N/A';
        }

        $query = DB::table($table)->where($idCol, $entityId);
        $select = ["{$firstNameCol} as first_name"];
        if ($lastNameCol) {
            $select[] = "{$lastNameCol} as last_name";
        }

        $row = $query->first($select);
        if (!$row) {
            return 'N/A';
        }

        $fullName = trim((string) ($row->first_name ?? '') . ' ' . (string) ($row->last_name ?? ''));
        return $fullName !== '' ? $fullName : 'N/A';
    }
}
