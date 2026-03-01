<?php

namespace App\Http\Controllers;
use App\Models\AttendanceStatus;
use App\Models\ModelHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TeacherController extends Controller {
    public function dashboard()
    {
        return view('teachers.dashboard');
    }

    public function examMarksPage()
    {
        return view('teachers.exam_marks');
    }

    public function initDashboard(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 3) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
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

    public function initExamMarks(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 3) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        if (!Schema::hasTable('exam_marks')) {
            return response()->json(['success' => false, 'message' => 'exam_marks table not found.'], 422);
        }

        $clientId = (int) ($user->client_id ?? 0);

        return response()->json([
            'success' => true,
            'students' => $this->resolveExamStudents($clientId),
            'subjects' => $this->resolveExamSubjects($clientId),
            'rows' => $this->resolveExamMarkRows($clientId, [
                'student_id' => null,
                'subject_id' => null,
                'exam_name' => '',
            ]),
        ]);
    }

    public function listExamMarks(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 3) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        if (!Schema::hasTable('exam_marks')) {
            return response()->json(['success' => false, 'message' => 'exam_marks table not found.'], 422);
        }

        $filters = $request->validate([
            'student_id' => ['nullable', 'integer', 'min:1'],
            'subject_id' => ['nullable', 'integer', 'min:1'],
            'exam_name' => ['nullable', 'string', 'max:120'],
        ]);

        $clientId = (int) ($user->client_id ?? 0);
        return response()->json([
            'success' => true,
            'rows' => $this->resolveExamMarkRows($clientId, [
                'student_id' => isset($filters['student_id']) ? (int) $filters['student_id'] : null,
                'subject_id' => isset($filters['subject_id']) ? (int) $filters['subject_id'] : null,
                'exam_name' => trim((string) ($filters['exam_name'] ?? '')),
            ]),
        ]);
    }

    public function storeExamMark(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 3) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        if (!Schema::hasTable('exam_marks')) {
            return response()->json(['success' => false, 'message' => 'exam_marks table not found.'], 422);
        }

        $data = $request->validate([
            'id' => ['nullable', 'integer', 'min:1'],
            'student_id' => ['required', 'integer', 'min:1'],
            'subject_id' => ['nullable', 'integer', 'min:1'],
            'exam_name' => ['required', 'string', 'max:120'],
            'exam_date' => ['required', 'date'],
            'total_marks' => ['required', 'numeric', 'min:1'],
            'obtained_marks' => ['required', 'numeric', 'min:0'],
            'remark' => ['nullable', 'string', 'max:2000'],
        ]);

        if ((float) $data['obtained_marks'] > (float) $data['total_marks']) {
            return response()->json(['success' => false, 'message' => 'Obtained marks cannot be greater than total marks.'], 422);
        }

        $clientId = (int) ($user->client_id ?? 0);
        if (!$this->isValidExamStudent((int) $data['student_id'], $clientId)) {
            return response()->json(['success' => false, 'message' => 'Invalid student.'], 422);
        }

        $subjectId = isset($data['subject_id']) ? (int) $data['subject_id'] : 0;
        if ($subjectId > 0 && !$this->isValidExamSubject($subjectId, $clientId)) {
            return response()->json(['success' => false, 'message' => 'Invalid subject.'], 422);
        }

        $payload = [
            'student_id' => (int) $data['student_id'],
            'subject_id' => $subjectId,
            'exam_name' => trim((string) $data['exam_name']),
            'exam_date' => $data['exam_date'],
            'total_marks' => (float) $data['total_marks'],
            'obtained_marks' => (float) $data['obtained_marks'],
            'remark' => isset($data['remark']) ? trim((string) $data['remark']) : null,
        ];

        if (Schema::hasColumn('exam_marks', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('exam_marks', 'marked_by')) {
            $payload['marked_by'] = (int) ($user->id ?? 0);
        }
        if (Schema::hasColumn('exam_marks', 'updated_at')) {
            $payload['updated_at'] = now();
        }

        if (!empty($data['id'])) {
            $query = DB::table('exam_marks')->where('id', (int) $data['id']);
            if (Schema::hasColumn('exam_marks', 'client_id')) {
                $query->where('client_id', $clientId);
            }
            if (Schema::hasColumn('exam_marks', 'marked_by')) {
                $query->where('marked_by', (int) ($user->id ?? 0));
            }

            if (!$query->exists()) {
                return response()->json(['success' => false, 'message' => 'Exam mark entry not found.'], 404);
            }

            $query->update($payload);
            $message = 'Exam mark updated successfully.';
        } else {
            if (Schema::hasColumn('exam_marks', 'created_at')) {
                $payload['created_at'] = now();
            }
            DB::table('exam_marks')->insert($payload);
            $message = 'Exam mark added successfully.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'rows' => $this->resolveExamMarkRows($clientId, [
                'student_id' => null,
                'subject_id' => null,
                'exam_name' => '',
            ]),
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

    private function resolveExamStudents(int $clientId): array
    {
        if (!Schema::hasTable('students')) {
            return [];
        }

        $idCol = ModelHelper::resolveColumn('students', ['id', 'student_id', 'sid']);
        $nameCol = ModelHelper::resolveColumn('students', ['first_name', 'name', 'student_name']);
        $lastNameCol = ModelHelper::resolveColumn('students', ['last_name']);
        $admissionCol = ModelHelper::resolveColumn('students', ['admission_no', 'erp_id']);
        if (!$idCol || !$nameCol) {
            return [];
        }

        $query = DB::table('students');
        if ($clientId > 0 && Schema::hasColumn('students', 'client_id')) {
            $query->where('client_id', $clientId);
        }
        if (Schema::hasColumn('students', 'active')) {
            $query->where('active', 1);
        }

        $select = ["{$idCol} as id", "{$nameCol} as first_name"];
        if ($lastNameCol) {
            $select[] = "{$lastNameCol} as last_name";
        }
        if ($admissionCol) {
            $select[] = "{$admissionCol} as admission_no";
        }

        return $query->orderByDesc($idCol)->limit(1000)->get($select)->map(function ($row) {
            $fullName = trim((string) ($row->first_name ?? '') . ' ' . (string) ($row->last_name ?? ''));
            return [
                'id' => (int) ($row->id ?? 0),
                'name' => $fullName !== '' ? $fullName : (string) ($row->first_name ?? 'Student'),
                'admission_no' => (string) ($row->admission_no ?? ''),
            ];
        })->all();
    }

    private function resolveExamSubjects(int $clientId): array
    {
        if (!Schema::hasTable('subjects')) {
            return [];
        }

        $idCol = ModelHelper::resolveColumn('subjects', ['id', 'subject_id']);
        $nameCol = ModelHelper::resolveColumn('subjects', ['name', 'subject_name', 'title']);
        if (!$idCol || !$nameCol) {
            return [];
        }

        $query = DB::table('subjects');
        if ($clientId > 0 && Schema::hasColumn('subjects', 'client_id')) {
            $query->where('client_id', $clientId);
        }
        if (Schema::hasColumn('subjects', 'active')) {
            $query->where('active', 1);
        }

        return $query->orderBy($nameCol)->limit(500)->get(["{$idCol} as id", "{$nameCol} as name"])->map(function ($row) {
            return [
                'id' => (int) ($row->id ?? 0),
                'name' => (string) ($row->name ?? 'Subject'),
            ];
        })->all();
    }

    private function isValidExamStudent(int $studentId, int $clientId): bool
    {
        if (!Schema::hasTable('students') || $studentId <= 0) {
            return false;
        }

        $idCol = ModelHelper::resolveColumn('students', ['id', 'student_id', 'sid']);
        if (!$idCol) {
            return false;
        }

        $query = DB::table('students')->where($idCol, $studentId);
        if ($clientId > 0 && Schema::hasColumn('students', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        return $query->exists();
    }

    private function isValidExamSubject(int $subjectId, int $clientId): bool
    {
        if (!Schema::hasTable('subjects') || $subjectId <= 0) {
            return false;
        }

        $idCol = ModelHelper::resolveColumn('subjects', ['id', 'subject_id']);
        if (!$idCol) {
            return false;
        }

        $query = DB::table('subjects')->where($idCol, $subjectId);
        if ($clientId > 0 && Schema::hasColumn('subjects', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        return $query->exists();
    }

    private function resolveExamMarkRows(int $clientId, array $filters): array
    {
        if (!Schema::hasTable('exam_marks')) {
            return [];
        }

        $query = DB::table('exam_marks');
        if ($clientId > 0 && Schema::hasColumn('exam_marks', 'client_id')) {
            $query->where('client_id', $clientId);
        }
        if (!empty($filters['student_id'])) {
            $query->where('student_id', (int) $filters['student_id']);
        }
        if (!empty($filters['subject_id'])) {
            $query->where('subject_id', (int) $filters['subject_id']);
        }
        if (!empty($filters['exam_name'])) {
            $query->where('exam_name', 'like', '%' . $filters['exam_name'] . '%');
        }

        $rows = $query->orderByDesc('exam_date')->orderByDesc('id')->limit(500)->get();
        $studentMap = collect($this->resolveExamStudents($clientId))->keyBy('id');
        $subjectMap = collect($this->resolveExamSubjects($clientId))->keyBy('id');

        return $rows->map(function ($row) use ($studentMap, $subjectMap) {
            $student = $studentMap->get((int) ($row->student_id ?? 0), null);
            $subject = $subjectMap->get((int) ($row->subject_id ?? 0), null);
            $percent = ((float) ($row->total_marks ?? 0) > 0)
                ? round(((float) ($row->obtained_marks ?? 0) / (float) $row->total_marks) * 100, 2)
                : 0;

            return [
                'id' => (int) ($row->id ?? 0),
                'student_id' => (int) ($row->student_id ?? 0),
                'student_name' => (string) ($student['name'] ?? 'N/A'),
                'subject_id' => (int) ($row->subject_id ?? 0),
                'subject_name' => (string) ($subject['name'] ?? 'General'),
                'exam_name' => (string) ($row->exam_name ?? ''),
                'exam_date' => (string) ($row->exam_date ?? ''),
                'total_marks' => (float) ($row->total_marks ?? 0),
                'obtained_marks' => (float) ($row->obtained_marks ?? 0),
                'percentage' => $percent,
                'remark' => (string) ($row->remark ?? ''),
            ];
        })->all();
    }
}
