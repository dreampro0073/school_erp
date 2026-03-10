<?php

namespace App\Http\Controllers;

use App\Models\ModelHelper;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SalaryController extends Controller
{
    public function getTeacherSalaryProfile(Request $request)
    {
        $admin = $this->resolveAdmin($request);
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $payload = $request->validate([
            'enc_id' => ['required', 'string'],
        ]);

        try {
            $teacherId = (int) Crypt::decryptString($payload['enc_id']);
        } catch (DecryptException $e) {
            return response()->json(['success' => false, 'message' => 'Invalid teacher id.'], 422);
        }

        if (!Schema::hasTable('teachers')) {
            return response()->json(['success' => false, 'message' => 'teachers table not found.'], 422);
        }

        $clientId = (int) ($admin->client_id ?? 0);
        $query = DB::table('teachers')->where('id', $teacherId);
        if (Schema::hasColumn('teachers', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        $teacher = $query->first();
        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher not found.'], 404);
        }

        $ownerId = $this->resolveTeacherSalaryOwnerId((array) $teacher);

        return response()->json([
            'success' => true,
            'salary_components' => $this->getTeacherSalaryStructure($clientId, $ownerId),
            'bank_details' => $this->getTeacherBankDetails($clientId, $ownerId),
        ]);
    }

    public function saveTeacherSalaryProfile(Request $request)
    {
        $admin = $this->resolveAdmin($request);
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $data = $request->validate([
            'enc_id' => ['required', 'string'],
            'salary_components' => ['nullable', 'array'],
            'salary_components.*.component_name' => ['required', 'string', 'max:120'],
            'salary_components.*.component_type' => ['nullable', 'in:earning,deduction'],
            'salary_components.*.amount' => ['required', 'numeric', 'min:0'],
            'salary_components.*.active' => ['nullable', 'in:0,1'],
            'bank_details' => ['nullable', 'array'],
            'bank_details.account_holder_name' => ['nullable', 'string', 'max:150'],
            'bank_details.bank_name' => ['nullable', 'string', 'max:150'],
            'bank_details.account_number' => ['nullable', 'string', 'max:50'],
            'bank_details.ifsc_code' => ['nullable', 'string', 'max:20'],
            'bank_details.branch_name' => ['nullable', 'string', 'max:150'],
            'bank_details.upi_id' => ['nullable', 'string', 'max:120'],
        ]);

        $bank = $data['bank_details'] ?? [];
        $hasAnyBankField = !empty(trim((string) ($bank['account_holder_name'] ?? '')))
            || !empty(trim((string) ($bank['bank_name'] ?? '')))
            || !empty(trim((string) ($bank['account_number'] ?? '')))
            || !empty(trim((string) ($bank['ifsc_code'] ?? '')))
            || !empty(trim((string) ($bank['branch_name'] ?? '')))
            || !empty(trim((string) ($bank['upi_id'] ?? '')));
        if ($hasAnyBankField) {
            foreach (['account_holder_name', 'bank_name', 'account_number', 'ifsc_code'] as $requiredField) {
                if (empty(trim((string) ($bank[$requiredField] ?? '')))) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please fill all required bank details fields.',
                        'errors' => ['bank_details.' . $requiredField => ['This field is required.']],
                    ], 422);
                }
            }
        }

        try {
            $teacherId = (int) Crypt::decryptString($data['enc_id']);
        } catch (DecryptException $e) {
            return response()->json(['success' => false, 'message' => 'Invalid teacher id.'], 422);
        }

        if (!Schema::hasTable('teachers')) {
            return response()->json(['success' => false, 'message' => 'teachers table not found.'], 422);
        }

        $clientId = (int) ($admin->client_id ?? 0);
        $query = DB::table('teachers')->where('id', $teacherId);
        if (Schema::hasColumn('teachers', 'client_id')) {
            $query->where('client_id', $clientId);
        }
        $teacher = $query->first();
        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher not found.'], 404);
        }

        $ownerId = $this->resolveTeacherSalaryOwnerId((array) $teacher);
        $this->saveTeacherSalaryStructure($data, $clientId, $ownerId);
        $this->saveTeacherBankDetails($data, $clientId, $ownerId);

        $this->logUserActivity(
            $clientId,
            isset($admin->id) ? (int) $admin->id : null,
            'Teacher salary profile updated',
            'teacher',
            'teacher_salary_profile'
        );

        return response()->json([
            'success' => true,
            'message' => 'Teacher salary profile saved successfully.',
        ]);
    }

    public function initTeacherSalaryLogs(Request $request)
    {
        $admin = $this->resolveAdmin($request);
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $filters = $request->validate([
            'teacher_id' => ['nullable', 'integer', 'min:1'],
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $clientId = (int) ($admin->client_id ?? 0);
        $teacherOptions = $this->getTeacherSalaryOptions($clientId);

        if (!Schema::hasTable('teacher_salary_logs')) {
            return response()->json([
                'success' => true,
                'teacher_options' => $teacherOptions,
                'logs' => [],
            ]);
        }

        $query = Salary::query()->orderByDesc('salary_month')->orderByDesc('id');
        if (Schema::hasColumn('teacher_salary_logs', 'client_id')) {
            $query->where('client_id', $clientId);
        }
        if (!empty($filters['teacher_id']) && Schema::hasColumn('teacher_salary_logs', 'teacher_id')) {
            $query->where('teacher_id', (int) $filters['teacher_id']);
        }
        if (!empty($filters['month']) && Schema::hasColumn('teacher_salary_logs', 'salary_month')) {
            $monthDate = $filters['month'] . '-01';
            $query->whereYear('salary_month', date('Y', strtotime($monthDate)));
            $query->whereMonth('salary_month', date('m', strtotime($monthDate)));
        }

        $teacherMap = [];
        foreach ($teacherOptions as $item) {
            $teacherMap[(int) ($item['teacher_id'] ?? 0)] = $item['teacher_name'] ?? 'Teacher';
        }

        $logs = $query->get()->map(function ($row) use ($teacherMap) {
            $item = (array) $row;
            $teacherId = (int) ($item['teacher_id'] ?? 0);
            $item['teacher_name'] = $teacherMap[$teacherId] ?? 'Unknown';
            if (!empty($item['salary_month'])) {
                $item['salary_month_label'] = date('M Y', strtotime((string) $item['salary_month']));
            }
            return $item;
        })->all();

        return response()->json([
            'success' => true,
            'teacher_options' => $teacherOptions,
            'logs' => $logs,
        ]);
    }

    public function storeTeacherSalaryLog(Request $request)
    {
        $admin = $this->resolveAdmin($request);
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        if (!Schema::hasTable('teacher_salary_logs')) {
            return response()->json(['success' => false, 'message' => 'teacher_salary_logs table not found.'], 422);
        }

        $data = $request->validate([
            'teacher_id' => ['required', 'integer', 'min:1'],
            'salary_month' => ['required', 'date_format:Y-m'],
            'gross_amount' => ['required', 'numeric', 'min:0'],
            'deduction_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_date' => ['nullable', 'date'],
            'payment_mode' => ['nullable', 'string', 'max:50'],
            'transaction_ref' => ['nullable', 'string', 'max:120'],
            'remark' => ['nullable', 'string', 'max:1000'],
        ]);

        $gross = (float) ($data['gross_amount'] ?? 0);
        $deduction = (float) ($data['deduction_amount'] ?? 0);
        $net = max(0, $gross - $deduction);
        $salaryMonth = $data['salary_month'] . '-01';
        $clientId = (int) ($admin->client_id ?? 0);

        $payload = [];
        if (Schema::hasColumn('teacher_salary_logs', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('teacher_salary_logs', 'teacher_id')) {
            $payload['teacher_id'] = (int) $data['teacher_id'];
        }
        if (Schema::hasColumn('teacher_salary_logs', 'salary_month')) {
            $payload['salary_month'] = $salaryMonth;
        }
        if (Schema::hasColumn('teacher_salary_logs', 'gross_amount')) {
            $payload['gross_amount'] = $gross;
        }
        if (Schema::hasColumn('teacher_salary_logs', 'deduction_amount')) {
            $payload['deduction_amount'] = $deduction;
        }
        if (Schema::hasColumn('teacher_salary_logs', 'net_amount')) {
            $payload['net_amount'] = $net;
        }
        if (Schema::hasColumn('teacher_salary_logs', 'payment_date')) {
            $payload['payment_date'] = $data['payment_date'] ?? null;
        }
        if (Schema::hasColumn('teacher_salary_logs', 'payment_mode')) {
            $payload['payment_mode'] = $data['payment_mode'] ?? null;
        }
        if (Schema::hasColumn('teacher_salary_logs', 'transaction_ref')) {
            $payload['transaction_ref'] = $data['transaction_ref'] ?? null;
        }
        if (Schema::hasColumn('teacher_salary_logs', 'remark')) {
            $payload['remark'] = $data['remark'] ?? null;
        }
        if (Schema::hasColumn('teacher_salary_logs', 'created_by')) {
            $payload['created_by'] = (int) ($admin->id ?? 0);
        }

        $existingQuery = Salary::query()
            ->where('teacher_id', (int) $data['teacher_id'])
            ->where('salary_month', $salaryMonth);
        if (Schema::hasColumn('teacher_salary_logs', 'client_id')) {
            $existingQuery->where('client_id', $clientId);
        }
        $existing = $existingQuery->first();

        if ($existing) {
            $payload = ModelHelper::applyTimestamps('teacher_salary_logs', $payload, false);
            Salary::query()->where('id', $existing->id)->update($payload);
            $message = 'Teacher salary log updated successfully.';
        } else {
            $payload = ModelHelper::applyTimestamps('teacher_salary_logs', $payload, true);
            Salary::query()->create($payload);
            $message = 'Teacher salary log created successfully.';
        }

        $this->syncSalaryExpenseEntry($clientId, $data, $net);

        $this->logUserActivity(
            $clientId,
            isset($admin->id) ? (int) $admin->id : null,
            'Teacher salary log saved',
            'teacher',
            'teacher_salary_logs'
        );

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    private function resolveAdmin(Request $request): ?User
    {
        $apiToken = $request->header('apiToken');
        $admin = User::authUser($apiToken);
        if (!$admin || is_string($admin)) {
            return null;
        }

        $priv = (int) ($admin->priv ?? $admin->privillage ?? $admin->privilege ?? 0);
        if ($priv !== 2) {
            return null;
        }

        return $admin;
    }

    private function resolveTeacherSalaryOwnerId(?array $teacher = null, ?int $teacherUserId = null, ?int $teacherId = null): int
    {
        if (!empty($teacherUserId)) {
            return (int) $teacherUserId;
        }

        if (!empty($teacher) && isset($teacher['user_id']) && (int) $teacher['user_id'] > 0) {
            return (int) $teacher['user_id'];
        }

        if ($teacherId && Schema::hasTable('teachers') && Schema::hasColumn('teachers', 'user_id')) {
            $query = DB::table('teachers')->where('id', $teacherId);
            if (is_array($teacher) && !empty($teacher['client_id']) && Schema::hasColumn('teachers', 'client_id')) {
                $query->where('client_id', (int) $teacher['client_id']);
            }
            $mappedUserId = (int) ($query->value('user_id') ?? 0);
            if ($mappedUserId > 0) {
                return $mappedUserId;
            }
        }

        if (!empty($teacher) && isset($teacher['id'])) {
            return (int) $teacher['id'];
        }

        return (int) ($teacherId ?? 0);
    }

    private function saveTeacherSalaryStructure(array $data, int $clientId, int $teacherOwnerId): void
    {
        if (
            $teacherOwnerId <= 0
            || !Schema::hasTable('teacher_salary_structures')
            || !array_key_exists('salary_components', $data)
            || !is_array($data['salary_components'])
        ) {
            return;
        }

        $baseQuery = DB::table('teacher_salary_structures')->where('teacher_id', $teacherOwnerId);
        if (Schema::hasColumn('teacher_salary_structures', 'client_id')) {
            $baseQuery->where('client_id', $clientId);
        }
        $baseQuery->delete();

        $sort = 1;
        foreach ($data['salary_components'] as $item) {
            $name = trim((string) ($item['component_name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $payload = [];
            if (Schema::hasColumn('teacher_salary_structures', 'client_id')) {
                $payload['client_id'] = $clientId;
            }
            if (Schema::hasColumn('teacher_salary_structures', 'teacher_id')) {
                $payload['teacher_id'] = $teacherOwnerId;
            }
            if (Schema::hasColumn('teacher_salary_structures', 'component_name')) {
                $payload['component_name'] = $name;
            }
            if (Schema::hasColumn('teacher_salary_structures', 'component_type')) {
                $payload['component_type'] = ($item['component_type'] ?? 'earning') === 'deduction' ? 'deduction' : 'earning';
            }
            if (Schema::hasColumn('teacher_salary_structures', 'amount')) {
                $payload['amount'] = (float) ($item['amount'] ?? 0);
            }
            if (Schema::hasColumn('teacher_salary_structures', 'sort_order')) {
                $payload['sort_order'] = $sort++;
            }
            if (Schema::hasColumn('teacher_salary_structures', 'active')) {
                $payload['active'] = (int) (($item['active'] ?? 1) ? 1 : 0);
            }

            $payload = ModelHelper::applyTimestamps('teacher_salary_structures', $payload, true);
            DB::table('teacher_salary_structures')->insert($payload);
        }
    }

    private function saveTeacherBankDetails(array $data, int $clientId, int $teacherOwnerId): void
    {
        if ($teacherOwnerId <= 0 || !Schema::hasTable('bank_details')) {
            return;
        }

        $bank = isset($data['bank_details']) && is_array($data['bank_details']) ? $data['bank_details'] : [];
        $hasAnyBankField = !empty(trim((string) ($bank['account_holder_name'] ?? '')))
            || !empty(trim((string) ($bank['bank_name'] ?? '')))
            || !empty(trim((string) ($bank['account_number'] ?? '')))
            || !empty(trim((string) ($bank['ifsc_code'] ?? '')))
            || !empty(trim((string) ($bank['branch_name'] ?? '')))
            || !empty(trim((string) ($bank['upi_id'] ?? '')));

        $query = DB::table('bank_details')->where('teacher_id', $teacherOwnerId);
        if (Schema::hasColumn('bank_details', 'client_id')) {
            $query->where('client_id', $clientId);
        }
        $existing = $query->first();

        if (!$hasAnyBankField) {
            if ($existing) {
                DB::table('bank_details')->where('id', $existing->id)->delete();
            }
            return;
        }

        $payload = [];
        if (Schema::hasColumn('bank_details', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('bank_details', 'teacher_id')) {
            $payload['teacher_id'] = $teacherOwnerId;
        }
        if (Schema::hasColumn('bank_details', 'account_holder_name')) {
            $payload['account_holder_name'] = trim((string) ($bank['account_holder_name'] ?? ''));
        }
        if (Schema::hasColumn('bank_details', 'bank_name')) {
            $payload['bank_name'] = trim((string) ($bank['bank_name'] ?? ''));
        }
        if (Schema::hasColumn('bank_details', 'account_number')) {
            $payload['account_number'] = trim((string) ($bank['account_number'] ?? ''));
        }
        if (Schema::hasColumn('bank_details', 'ifsc_code')) {
            $payload['ifsc_code'] = strtoupper(trim((string) ($bank['ifsc_code'] ?? '')));
        }
        if (Schema::hasColumn('bank_details', 'branch_name')) {
            $payload['branch_name'] = trim((string) ($bank['branch_name'] ?? '')) ?: null;
        }
        if (Schema::hasColumn('bank_details', 'upi_id')) {
            $payload['upi_id'] = trim((string) ($bank['upi_id'] ?? '')) ?: null;
        }
        if (Schema::hasColumn('bank_details', 'active')) {
            $payload['active'] = 1;
        }

        if ($existing) {
            $payload = ModelHelper::applyTimestamps('bank_details', $payload, false);
            DB::table('bank_details')->where('id', $existing->id)->update($payload);
            return;
        }

        $payload = ModelHelper::applyTimestamps('bank_details', $payload, true);
        DB::table('bank_details')->insert($payload);
    }

    private function getTeacherSalaryStructure(int $clientId, int $teacherOwnerId): array
    {
        if ($teacherOwnerId <= 0 || !Schema::hasTable('teacher_salary_structures')) {
            return [];
        }

        $query = DB::table('teacher_salary_structures')
            ->where('teacher_id', $teacherOwnerId)
            ->orderBy('sort_order')
            ->orderBy('id');
        if (Schema::hasColumn('teacher_salary_structures', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        return $query->get()->map(function ($row) {
            return [
                'component_name' => (string) ($row->component_name ?? ''),
                'component_type' => (string) ($row->component_type ?? 'earning'),
                'amount' => (float) ($row->amount ?? 0),
                'active' => (int) ($row->active ?? 1),
            ];
        })->all();
    }

    private function getTeacherBankDetails(int $clientId, int $teacherOwnerId): array
    {
        if ($teacherOwnerId <= 0 || !Schema::hasTable('bank_details')) {
            return [];
        }

        $query = DB::table('bank_details')->where('teacher_id', $teacherOwnerId);
        if (Schema::hasColumn('bank_details', 'client_id')) {
            $query->where('client_id', $clientId);
        }
        $row = $query->first();
        if (!$row) {
            return [];
        }

        return [
            'account_holder_name' => (string) ($row->account_holder_name ?? ''),
            'bank_name' => (string) ($row->bank_name ?? ''),
            'account_number' => (string) ($row->account_number ?? ''),
            'ifsc_code' => (string) ($row->ifsc_code ?? ''),
            'branch_name' => (string) ($row->branch_name ?? ''),
            'upi_id' => (string) ($row->upi_id ?? ''),
        ];
    }

    private function getTeacherSalaryOptions(int $clientId): array
    {
        if (!Schema::hasTable('teachers')) {
            return [];
        }

        $query = DB::table('teachers');
        if (Schema::hasColumn('teachers', 'client_id')) {
            $query->where('client_id', $clientId);
        }
        if (Schema::hasColumn('teachers', 'active')) {
            $query->where('active', 1);
        }

        $rows = $query->orderByDesc('id')->get();
        $options = $rows->map(function ($row) {
            $item = (array) $row;
            $teacherId = (int) ($item['user_id'] ?? 0);
            if ($teacherId <= 0) {
                $teacherId = (int) ($item['id'] ?? 0);
            }

            $name = trim((string) (($item['first_name'] ?? $item['name'] ?? '') . ' ' . ($item['last_name'] ?? '')));
            if ($name === '') {
                $name = 'Teacher #' . $teacherId;
            }

            return [
                'teacher_id' => $teacherId,
                'teacher_name' => $name,
            ];
        })->filter(function (array $item) {
            return (int) ($item['teacher_id'] ?? 0) > 0;
        })->values()->all();

        $unique = [];
        foreach ($options as $item) {
            $key = (int) ($item['teacher_id'] ?? 0);
            if ($key > 0 && !isset($unique[$key])) {
                $unique[$key] = $item;
            }
        }

        return array_values($unique);
    }

    private function ensureSalaryExpenseMaster(int $clientId): int
    {
        if (!Schema::hasTable('expenses')) {
            return 0;
        }

        $idColumn = ModelHelper::resolveFirstExistingColumn('expenses', ['id', 'expense_id']);
        $nameColumn = ModelHelper::resolveFirstExistingColumn('expenses', ['name', 'expense_name', 'title']);
        if (!$idColumn || !$nameColumn) {
            return 0;
        }

        $query = DB::table('expenses')->whereRaw('LOWER(' . $nameColumn . ') = ?', ['salary']);
        if (Schema::hasColumn('expenses', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        $existingId = (int) ($query->value($idColumn) ?? 0);
        if ($existingId > 0) {
            return $existingId;
        }

        $payload = [$nameColumn => 'Salary'];
        if (Schema::hasColumn('expenses', 'active')) {
            $payload['active'] = 1;
        }
        if (Schema::hasColumn('expenses', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        $payload = ModelHelper::applyTimestamps('expenses', $payload, true);

        return (int) DB::table('expenses')->insertGetId($payload);
    }

    private function syncSalaryExpenseEntry(int $clientId, array $salaryData, float $netAmount): void
    {
        if (!Schema::hasTable('expense_entries')) {
            return;
        }

        $salaryExpenseId = $this->ensureSalaryExpenseMaster($clientId);
        if ($salaryExpenseId <= 0) {
            return;
        }

        $masterIdColumn = ModelHelper::resolveFirstExistingColumn('expense_entries', ['expense_id', 'expenses_id']);
        if (!$masterIdColumn) {
            return;
        }

        $teacherId = (int) ($salaryData['teacher_id'] ?? 0);
        $salaryMonth = (string) ($salaryData['salary_month'] ?? '');
        $monthDate = $salaryMonth !== '' ? ($salaryMonth . '-01') : now()->toDateString();
        $entryDate = !empty($salaryData['payment_date']) ? $salaryData['payment_date'] : $monthDate;
        $teacherName = $this->resolveTeacherNameByOwnerId($clientId, $teacherId);
        $salaryMarker = '[SALARY:' . $teacherId . ':' . $salaryMonth . ']';
        $remark = 'Salary paid';
        if ($teacherName !== '') {
            $remark .= ' to ' . $teacherName;
        }
        if ($salaryMonth !== '') {
            $remark .= ' for ' . $salaryMonth;
        }
        $remark .= ' ' . $salaryMarker;

        $payload = [];
        $payload[$masterIdColumn] = $salaryExpenseId;
        if (Schema::hasColumn('expense_entries', 'date')) {
            $payload['date'] = $entryDate;
        }
        if (Schema::hasColumn('expense_entries', 'amount')) {
            $payload['amount'] = $netAmount;
        }
        if (Schema::hasColumn('expense_entries', 'remark')) {
            $payload['remark'] = $remark;
        }
        if (Schema::hasColumn('expense_entries', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('expense_entries', 'active')) {
            $payload['active'] = 1;
        }
        if (Schema::hasColumn('expense_entries', 'teacher_id')) {
            $payload['teacher_id'] = $teacherId;
        }
        if (Schema::hasColumn('expense_entries', 'salary_month')) {
            $payload['salary_month'] = $monthDate;
        }

        $query = DB::table('expense_entries')->where($masterIdColumn, $salaryExpenseId);
        if (Schema::hasColumn('expense_entries', 'client_id')) {
            $query->where('client_id', $clientId);
        }
        if (Schema::hasColumn('expense_entries', 'teacher_id')) {
            $query->where('teacher_id', $teacherId);
        }
        if (Schema::hasColumn('expense_entries', 'salary_month')) {
            $query->where('salary_month', $monthDate);
        } elseif (Schema::hasColumn('expense_entries', 'remark')) {
            $query->where('remark', 'like', '%' . $salaryMarker . '%');
        }

        $existing = $query->first();
        if ($existing) {
            $payload = ModelHelper::applyTimestamps('expense_entries', $payload, false);
            DB::table('expense_entries')->where('id', $existing->id)->update($payload);
            return;
        }

        $payload = ModelHelper::applyTimestamps('expense_entries', $payload, true);
        DB::table('expense_entries')->insert($payload);
    }

    private function resolveTeacherNameByOwnerId(int $clientId, int $teacherOwnerId): string
    {
        if ($teacherOwnerId <= 0 || !Schema::hasTable('teachers')) {
            return '';
        }

        $query = DB::table('teachers');
        if (Schema::hasColumn('teachers', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        if (Schema::hasColumn('teachers', 'user_id')) {
            $query->where(function ($q) use ($teacherOwnerId) {
                $q->where('user_id', $teacherOwnerId)->orWhere('id', $teacherOwnerId);
            });
        } else {
            $query->where('id', $teacherOwnerId);
        }

        $row = $query->first();
        if (!$row) {
            return '';
        }

        $item = (array) $row;
        return trim((string) (($item['first_name'] ?? $item['name'] ?? '') . ' ' . ($item['last_name'] ?? '')));
    }

    private function logUserActivity(
        int $clientId,
        ?int $userId,
        string $activity,
        string $activityType = 'teacher',
        string $module = 'teacher_salary'
    ): void {
        if (!Schema::hasTable('user_activities')) {
            return;
        }

        $payload = [];
        if (Schema::hasColumn('user_activities', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('user_activities', 'user_id') && $userId) {
            $payload['user_id'] = $userId;
        }
        if (Schema::hasColumn('user_activities', 'activity')) {
            $payload['activity'] = $activity;
        }
        if (Schema::hasColumn('user_activities', 'activity_type')) {
            $payload['activity_type'] = $activityType;
        }
        if (Schema::hasColumn('user_activities', 'module')) {
            $payload['module'] = $module;
        }

        $payload = ModelHelper::applyTimestamps('user_activities', $payload, true);
        DB::table('user_activities')->insert($payload);
    }
}
