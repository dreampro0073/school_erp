<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\AttendanceStatus;
use App\Models\ModelHelper;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminController extends Controller {
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function studentsIndex()
    {
        return view('admin.students.index');
    }

    public function addStudentPage(?string $student = null)
    {
        return view('admin.students.form', [
            'studentToken' => $student ?? '',
        ]);
    }

    public function teachersIndex()
    {
        return view('admin.teachers.index');
    }

    public function addTeacherPage(?string $teacher = null)
    {
        return view('admin.teachers.form', [
            'teacherToken' => $teacher ?? '',
        ]);
    }

    public function incomesPage()
    {
        return view('admin.finance.incomes');
    }

    public function incomeEntriesPage()
    {
        return view('admin.finance.income_entries');
    }

    public function expensesPage()
    {
        return view('admin.finance.expenses');
    }

    public function expenseEntriesPage()
    {
        return view('admin.finance.expense_entries');
    }

    public function studentProfilePage(string $student)
    {
        return view('admin.students.profile', [
            'studentToken' => $student,
        ]);
    }

    public function initDashboard(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized user.',
            ], 401);
        }

        $clientId = (int) ($user->client_id ?? 0);

        $stats = [
            'students' => $this->getTableStats(['students'], $clientId),
            'teachers' => $this->getTableStats(['teachers'], $clientId),
            'sections' => $this->getTableStats(['sections'], $clientId),
            'subjects' => $this->getTableStats(['subjects'], $clientId),
            'services' => $this->getTableStats(['services'], $clientId),
            'feeTypes' => $this->getTableStats(['fee_types', 'fee_type'], $clientId),
        ];

        $attendance = $this->buildAttendanceStats($clientId);

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'attendance' => $attendance,
        ]);
    }

    public function initStudents(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $clientId = (int) ($user->client_id ?? 0);
        $rows = $this->getClientRows('students', $clientId);

        foreach ($rows as &$row) {
            if (isset($row['id'])) {
                $row['enc_id'] = Crypt::encryptString((string) $row['id']);
            }
        }

        return response()->json([
            'success' => true,
            'students' => $rows,
        ]);
    }

    public function initTeachers(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $teachers = $this->getClientRows('teachers', (int) ($user->client_id ?? 0));
        foreach ($teachers as &$row) {
            if (isset($row['id'])) {
                $row['enc_id'] = Crypt::encryptString((string) $row['id']);
            }
        }

        return response()->json([
            'success' => true,
            'teachers' => $teachers,
        ]);
    }

    public function initIncomes(Request $request)
    {
        return $this->initMasterTable($request, 'incomes', ['name', 'income_name', 'title'], 'incomes');
    }

    public function storeIncome(Request $request)
    {
        return $this->storeMasterTable($request, 'incomes', ['name', 'income_name', 'title'], 'Income');
    }

    public function initExpenses(Request $request)
    {
        return $this->initMasterTable($request, 'expenses', ['name', 'expense_name', 'title'], 'expenses');
    }

    public function storeExpense(Request $request)
    {
        return $this->storeMasterTable($request, 'expenses', ['name', 'expense_name', 'title'], 'Expense');
    }

    public function initIncomeEntries(Request $request)
    {
        return $this->initEntryTable($request, 'income_entries', ['income_id', 'incomes_id']);
    }

    public function storeIncomeEntry(Request $request)
    {
        return $this->storeEntryTable($request, 'income_entries', ['income_id', 'incomes_id'], 'Income entry');
    }

    public function initExpenseEntries(Request $request)
    {
        return $this->initEntryTable($request, 'expense_entries', ['expense_id', 'expenses_id']);
    }

    public function storeExpenseEntry(Request $request)
    {
        return $this->storeEntryTable($request, 'expense_entries', ['expense_id', 'expenses_id'], 'Expense entry');
    }

    public function getTeacher(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
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

        $query = DB::table('teachers')->where('id', $teacherId);
        if (Schema::hasColumn('teachers', 'client_id')) {
            $query->where('client_id', (int) ($user->client_id ?? 0));
        }

        $teacher = $query->first();
        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'teacher' => (array) $teacher,
            'enc_id' => $payload['enc_id'],
        ]);
    }

    public function storeTeacher(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $admin = User::authUser($apiToken);
        if (!$admin || is_string($admin)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($admin->priv ?? $admin->privillage ?? $admin->privilege ?? 0);
        if ($priv !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $data = $request->validate([
            'enc_id' => ['nullable', 'string'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'aadhar_no' => ['nullable', 'string', 'max:50'],
            'active' => ['nullable', 'in:0,1'],
            'document_type' => ['nullable', 'string', 'max:100'],
            'document_no' => ['nullable', 'string', 'max:100'],
        ]);

        $clientId = (int) ($admin->client_id ?? 0);
        if ($clientId <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid client id.'], 422);
        }

        if (!Schema::hasTable('teachers')) {
            return response()->json(['success' => false, 'message' => 'teachers table not found.'], 422);
        }

        $teacherId = null;
        $isUpdate = false;
        if (!empty($data['enc_id'])) {
            try {
                $teacherId = (int) Crypt::decryptString($data['enc_id']);
                $isUpdate = true;
            } catch (DecryptException $e) {
                return response()->json(['success' => false, 'message' => 'Invalid teacher id.'], 422);
            }
        }

        DB::beginTransaction();
        try {
            $teacherUserId = $this->saveTeacherUser($data, $clientId, $teacherId);
            $teacherPayload = $this->buildTeacherPayload($data, $clientId, $teacherUserId);

            if ($teacherId) {
                $query = DB::table('teachers')->where('id', $teacherId);
                if (Schema::hasColumn('teachers', 'client_id')) {
                    $query->where('client_id', $clientId);
                }
                $teacherPayload = ModelHelper::applyTimestamps('teachers', $teacherPayload, false);
                $query->update($teacherPayload);
            } else {
                $teacherPayload = ModelHelper::applyTimestamps('teachers', $teacherPayload, true);
                $teacherId = (int) DB::table('teachers')->insertGetId($teacherPayload);
            }

            $this->saveAadharDetailsForTeacher($data, $clientId, $teacherId);
            $this->saveDocumentsForTeacher($data, $clientId, $teacherId);
            $this->saveTeacherAccess($clientId, $teacherId, $teacherUserId);
            $this->logUserActivity(
                $clientId,
                $teacherUserId,
                $isUpdate ? 'Teacher updated' : 'Teacher created',
                'teacher',
                'teachers'
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $isUpdate ? 'Teacher updated successfully.' : 'Teacher created successfully.',
                'enc_id' => Crypt::encryptString((string) $teacherId),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getStudent(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $payload = $request->validate([
            'enc_id' => ['required', 'string'],
        ]);

        try {
            $studentId = (int) Crypt::decryptString($payload['enc_id']);
        } catch (DecryptException $e) {
            return response()->json(['success' => false, 'message' => 'Invalid student id.'], 422);
        }

        if (!Schema::hasTable('students')) {
            return response()->json(['success' => false, 'message' => 'students table not found.'], 422);
        }

        $studentQuery = DB::table('students')->where('id', $studentId);
        if (Schema::hasColumn('students', 'client_id')) {
            $studentQuery->where('client_id', (int) ($user->client_id ?? 0));
        }

        $student = $studentQuery->first();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.'], 404);
        }

        $studentArr = (array) $student;
        $parent = $this->fetchParentData($studentArr);

        return response()->json([
            'success' => true,
            'student' => $studentArr,
            'parent' => $parent,
            'enc_id' => $payload['enc_id'],
        ]);
    }

    public function updateStudentStatus(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $admin = User::authUser($apiToken);
        if (!$admin || is_string($admin)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($admin->priv ?? $admin->privillage ?? $admin->privilege ?? 0);
        if ($priv !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $payload = $request->validate([
            'enc_id' => ['required', 'string'],
            'active' => ['required', 'in:0,1'],
        ]);

        try {
            $studentId = (int) Crypt::decryptString($payload['enc_id']);
        } catch (DecryptException $e) {
            return response()->json(['success' => false, 'message' => 'Invalid student id.'], 422);
        }

        if (!Schema::hasTable('students')) {
            return response()->json(['success' => false, 'message' => 'students table not found.'], 422);
        }
        if (!Schema::hasColumn('students', 'active')) {
            return response()->json(['success' => false, 'message' => 'students.active column not found.'], 422);
        }

        $clientId = (int) ($admin->client_id ?? 0);
        $studentQuery = DB::table('students')->where('id', $studentId);
        if (Schema::hasColumn('students', 'client_id')) {
            $studentQuery->where('client_id', $clientId);
        }

        $student = $studentQuery->first();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.'], 404);
        }

        $update = ['active' => (int) $payload['active']];
        if (Schema::hasColumn('students', 'updated_at')) {
            $update['updated_at'] = now();
        }
        $studentQuery->update($update);

        if (
            Schema::hasTable('users') &&
            isset($student->user_id) &&
            $student->user_id &&
            Schema::hasColumn('users', 'active')
        ) {
            $userUpdate = ['active' => (int) $payload['active']];
            if (Schema::hasColumn('users', 'updated_at')) {
                $userUpdate['updated_at'] = now();
            }
            DB::table('users')->where('id', (int) $student->user_id)->update($userUpdate);
        }

        $this->logUserActivity(
            $clientId,
            isset($student->user_id) ? (int) $student->user_id : null,
            ((int) $payload['active'] === 1) ? 'Student activated' : 'Student deactivated'
        );

        return response()->json([
            'success' => true,
            'message' => ((int) $payload['active'] === 1) ? 'Student activated successfully.' : 'Student deactivated successfully.',
        ]);
    }

    public function storeStudent(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $admin = User::authUser($apiToken);
        if (!$admin || is_string($admin)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($admin->priv ?? $admin->privillage ?? $admin->privilege ?? 0);
        if ($priv !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $data = $request->validate([
            'enc_id' => ['nullable', 'string'],
            'admission_no' => ['nullable', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'aadhar_no' => ['nullable', 'string', 'max:50'],
            'active' => ['nullable', 'in:0,1'],

            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_mobile' => ['nullable', 'string', 'max:50'],
            'parent_email' => ['nullable', 'email', 'max:255'],
            'parent_address' => ['nullable', 'string', 'max:1000'],
            'parent_aadhar_no' => ['nullable', 'string', 'max:50'],

            'document_type' => ['nullable', 'string', 'max:100'],
            'document_no' => ['nullable', 'string', 'max:100'],
        ]);

        $clientId = (int) ($admin->client_id ?? 0);
        if ($clientId <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid client id.'], 422);
        }

        $studentId = null;
        $isUpdate = false;
        if (!empty($data['enc_id'])) {
            try {
                $studentId = (int) Crypt::decryptString($data['enc_id']);
                $isUpdate = true;
            } catch (DecryptException $e) {
                return response()->json(['success' => false, 'message' => 'Invalid student id.'], 422);
            }
        }

        if (!Schema::hasTable('students')) {
            return response()->json(['success' => false, 'message' => 'students table not found.'], 422);
        }

        DB::beginTransaction();
        try {
            $studentUserId = $this->saveStudentUser($data, $clientId, $studentId);
            $parentUserId = $this->saveParentUser($data, $clientId, $studentId);
            $parentId = $this->saveParent($data, $clientId, $parentUserId, $studentId);

            $studentPayload = $this->buildStudentPayload($data, $clientId, $studentUserId, $parentId);

            if ($studentId) {
                $query = DB::table('students')->where('id', $studentId);
                if (Schema::hasColumn('students', 'client_id')) {
                    $query->where('client_id', $clientId);
                }
                $studentPayload = ModelHelper::applyTimestamps('students', $studentPayload, false);
                $query->update($studentPayload);
            } else {
                $studentPayload = ModelHelper::applyTimestamps('students', $studentPayload, true);
                $studentId = (int) DB::table('students')->insertGetId($studentPayload);
            }

            $this->saveAadharDetails($data, $clientId, $studentId, $parentId);
            $this->saveDocuments($data, $clientId, $studentId, $parentId);
            $this->saveParentAccess($clientId, $studentId, $parentId);
            $this->logUserActivity($clientId, $studentUserId, $isUpdate ? 'Student updated' : 'Student created');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $data['enc_id'] ? 'Student updated successfully.' : 'Student created successfully.',
                'enc_id' => Crypt::encryptString((string) $studentId),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function saveStudentUser(array $data, int $clientId, ?int $studentId): ?int
    {
        if (!Schema::hasTable('users')) {
            return null;
        }

        $existingUserId = null;
        if ($studentId && Schema::hasColumn('students', 'user_id')) {
            $existingUserId = DB::table('students')->where('id', $studentId)->value('user_id');
        }

        $payload = [];
        if (Schema::hasColumn('users', 'name')) {
            $payload['name'] = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')) ?: ($data['first_name'] ?? 'Student');
        }
        if (Schema::hasColumn('users', 'email') && !empty($data['email'])) {
            $payload['email'] = $data['email'];
        }
        if (Schema::hasColumn('users', 'mobile') && !empty($data['mobile'])) {
            $payload['mobile'] = $data['mobile'];
        }
        if (Schema::hasColumn('users', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('users', 'active')) {
            $payload['active'] = (int) ($data['active'] ?? 1);
        }
        foreach (['priv', 'privillage', 'privilege', 'privilege_id', 'role_id', 'user_type'] as $col) {
            if (Schema::hasColumn('users', $col)) {
                $payload[$col] = 4;
                break;
            }
        }

        if ($existingUserId) {
            $payload = ModelHelper::applyTimestamps('users', $payload, false);
            DB::table('users')->where('id', $existingUserId)->update($payload);
            return (int) $existingUserId;
        }

        if (Schema::hasColumn('users', 'password')) {
            $payload['password'] = Hash::make(Str::password(10));
        }
        $payload = ModelHelper::applyTimestamps('users', $payload, true);

        return (int) DB::table('users')->insertGetId($payload);
    }

    private function initMasterTable(Request $request, string $table, array $nameCandidates, string $responseKey)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        if (!Schema::hasTable($table)) {
            return response()->json(['success' => true, $responseKey => []]);
        }

        $idColumn = ModelHelper::resolveFirstExistingColumn($table, ['id', Str::singular($table) . '_id']);
        $nameColumn = ModelHelper::resolveFirstExistingColumn($table, $nameCandidates);
        if (!$idColumn || !$nameColumn) {
            return response()->json(['success' => false, 'message' => "Unsupported {$table} table structure."], 422);
        }

        $select = ["{$idColumn} as id", "{$nameColumn} as name"];
        foreach (['active', 'created_at'] as $col) {
            if (Schema::hasColumn($table, $col)) {
                $select[] = $col;
            }
        }

        $query = DB::table($table)->select($select);
        ModelHelper::applyClientScope($query, $table, (int) ($user->client_id ?? 0));
        $query->orderByDesc($idColumn);

        return response()->json(['success' => true, $responseKey => $query->get()]);
    }

    private function storeMasterTable(Request $request, string $table, array $nameCandidates, string $label)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!Schema::hasTable($table)) {
            return response()->json(['success' => false, 'message' => "{$table} table not found."], 422);
        }

        $idColumn = ModelHelper::resolveFirstExistingColumn($table, ['id', Str::singular($table) . '_id']);
        $nameColumn = ModelHelper::resolveFirstExistingColumn($table, $nameCandidates);
        if (!$idColumn || !$nameColumn) {
            return response()->json(['success' => false, 'message' => "Unsupported {$table} table structure."], 422);
        }

        $data = $request->validate([
            'id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'active' => ['nullable', 'in:0,1'],
        ]);

        $payload = [$nameColumn => $data['name']];
        if (Schema::hasColumn($table, 'active')) {
            $payload['active'] = isset($data['active']) ? (int) $data['active'] : 1;
        }
        if (Schema::hasColumn($table, 'client_id')) {
            $payload['client_id'] = (int) ($user->client_id ?? 0);
        }
        $payload = ModelHelper::applyTimestamps($table, $payload, empty($data['id']));

        if (!empty($data['id'])) {
            $query = DB::table($table)->where($idColumn, (int) $data['id']);
            ModelHelper::applyClientScope($query, $table, (int) ($user->client_id ?? 0));
            $query->update($payload);
            return response()->json(['success' => true, 'message' => "{$label} updated successfully."]);
        }

        DB::table($table)->insert($payload);
        return response()->json(['success' => true, 'message' => "{$label} created successfully."]);
    }

    private function initEntryTable(Request $request, string $table, array $masterIdCandidates)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!Schema::hasTable($table)) {
            return response()->json(['success' => true, 'entries' => []]);
        }

        $query = DB::table($table);
        ModelHelper::applyClientScope($query, $table, (int) ($user->client_id ?? 0));

        if (Schema::hasColumn($table, 'id')) {
            $query->orderByDesc('id');
        } elseif (Schema::hasColumn($table, 'created_at')) {
            $query->orderByDesc('created_at');
        }

        return response()->json(['success' => true, 'entries' => $query->get()]);
    }

    private function storeEntryTable(Request $request, string $table, array $masterIdCandidates, string $label)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $priv = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
        if ($priv !== 2) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }
        if (!Schema::hasTable($table)) {
            return response()->json(['success' => false, 'message' => "{$table} table not found."], 422);
        }

        $masterIdColumn = ModelHelper::resolveFirstExistingColumn($table, $masterIdCandidates);
        if (!$masterIdColumn) {
            return response()->json(['success' => false, 'message' => "Unsupported {$table} table structure."], 422);
        }

        $data = $request->validate([
            'id' => ['nullable', 'integer'],
            'master_id' => ['required', 'integer'],
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric'],
            'remark' => ['nullable', 'string'],
            'active' => ['nullable', 'in:0,1'],
        ]);

        $payload = [
            $masterIdColumn => (int) $data['master_id'],
        ];
        if (Schema::hasColumn($table, 'date')) {
            $payload['date'] = $data['date'];
        }
        if (Schema::hasColumn($table, 'amount')) {
            $payload['amount'] = $data['amount'];
        }
        if (Schema::hasColumn($table, 'remark')) {
            $payload['remark'] = $data['remark'] ?? null;
        }
        if (Schema::hasColumn($table, 'active')) {
            $payload['active'] = isset($data['active']) ? (int) $data['active'] : 1;
        }
        if (Schema::hasColumn($table, 'client_id')) {
            $payload['client_id'] = (int) ($user->client_id ?? 0);
        }
        $payload = ModelHelper::applyTimestamps($table, $payload, empty($data['id']));

        if (!empty($data['id'])) {
            $query = DB::table($table)->where('id', (int) $data['id']);
            ModelHelper::applyClientScope($query, $table, (int) ($user->client_id ?? 0));
            $query->update($payload);
            return response()->json(['success' => true, 'message' => "{$label} updated successfully."]);
        }

        DB::table($table)->insert($payload);
        return response()->json(['success' => true, 'message' => "{$label} created successfully."]);
    }

    private function saveParentUser(array $data, int $clientId, ?int $studentId): ?int
    {
        if (!Schema::hasTable('users')) {
            return null;
        }

        $existingParentUserId = null;
        if ($studentId && Schema::hasTable('parents') && Schema::hasColumn('students', 'parent_id')) {
            $parentId = DB::table('students')->where('id', $studentId)->value('parent_id');
            if ($parentId && Schema::hasColumn('parents', 'user_id')) {
                $existingParentUserId = DB::table('parents')->where('id', $parentId)->value('user_id');
            }
        }

        $payload = [];
        if (Schema::hasColumn('users', 'name')) {
            $payload['name'] = $data['parent_name'] ?? 'Parent';
        }
        if (Schema::hasColumn('users', 'email') && !empty($data['parent_email'])) {
            $payload['email'] = $data['parent_email'];
        }
        if (Schema::hasColumn('users', 'mobile') && !empty($data['parent_mobile'])) {
            $payload['mobile'] = $data['parent_mobile'];
        }
        if (Schema::hasColumn('users', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('users', 'active')) {
            $payload['active'] = 1;
        }
        foreach (['priv', 'privillage', 'privilege', 'privilege_id', 'role_id', 'user_type'] as $col) {
            if (Schema::hasColumn('users', $col)) {
                $payload[$col] = 5;
                break;
            }
        }

        if ($existingParentUserId) {
            $payload = ModelHelper::applyTimestamps('users', $payload, false);
            DB::table('users')->where('id', $existingParentUserId)->update($payload);
            return (int) $existingParentUserId;
        }

        if (Schema::hasColumn('users', 'password')) {
            $payload['password'] = Hash::make(Str::password(10));
        }
        $payload = ModelHelper::applyTimestamps('users', $payload, true);

        return (int) DB::table('users')->insertGetId($payload);
    }

    private function saveParent(array $data, int $clientId, ?int $parentUserId, ?int $studentId): ?int
    {
        if (!Schema::hasTable('parents')) {
            return null;
        }

        $payload = [];
        foreach (['name', 'parent_name', 'full_name'] as $col) {
            if (Schema::hasColumn('parents', $col) && !empty($data['parent_name'])) {
                $payload[$col] = $data['parent_name'];
                break;
            }
        }
        foreach (['mobile', 'phone'] as $col) {
            if (Schema::hasColumn('parents', $col) && !empty($data['parent_mobile'])) {
                $payload[$col] = $data['parent_mobile'];
                break;
            }
        }
        if (Schema::hasColumn('parents', 'email') && !empty($data['parent_email'])) {
            $payload['email'] = $data['parent_email'];
        }
        if (Schema::hasColumn('parents', 'address') && !empty($data['parent_address'])) {
            $payload['address'] = $data['parent_address'];
        }
        if (Schema::hasColumn('parents', 'aadhar_no') && !empty($data['parent_aadhar_no'])) {
            $payload['aadhar_no'] = $data['parent_aadhar_no'];
        }
        if (Schema::hasColumn('parents', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('parents', 'user_id') && $parentUserId) {
            $payload['user_id'] = $parentUserId;
        }
        if (Schema::hasColumn('parents', 'active')) {
            $payload['active'] = 1;
        }

        $existingParentId = null;
        if ($studentId && Schema::hasColumn('students', 'parent_id')) {
            $existingParentId = DB::table('students')->where('id', $studentId)->value('parent_id');
        }

        if ($existingParentId) {
            $payload = ModelHelper::applyTimestamps('parents', $payload, false);
            DB::table('parents')->where('id', $existingParentId)->update($payload);
            return (int) $existingParentId;
        }

        $payload = ModelHelper::applyTimestamps('parents', $payload, true);
        return (int) DB::table('parents')->insertGetId($payload);
    }

    private function buildStudentPayload(array $data, int $clientId, ?int $studentUserId, ?int $parentId): array
    {
        $payload = [];

        if (Schema::hasColumn('students', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('students', 'user_id') && $studentUserId) {
            $payload['user_id'] = $studentUserId;
        }
        if (Schema::hasColumn('students', 'parent_id') && $parentId) {
            $payload['parent_id'] = $parentId;
        }
        if (Schema::hasColumn('students', 'first_name')) {
            $payload['first_name'] = $data['first_name'];
        }
        if (Schema::hasColumn('students', 'last_name')) {
            $payload['last_name'] = $data['last_name'] ?? null;
        }
        if (Schema::hasColumn('students', 'name')) {
            $payload['name'] = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        }
        if (Schema::hasColumn('students', 'admission_no')) {
            $payload['admission_no'] = $data['admission_no'] ?? null;
        }
        if (Schema::hasColumn('students', 'dob')) {
            $payload['dob'] = $data['dob'] ?? null;
        }
        if (Schema::hasColumn('students', 'gender')) {
            $payload['gender'] = $data['gender'] ?? null;
        }
        if (Schema::hasColumn('students', 'mobile')) {
            $payload['mobile'] = $data['mobile'] ?? null;
        }
        if (Schema::hasColumn('students', 'email')) {
            $payload['email'] = $data['email'] ?? null;
        }
        if (Schema::hasColumn('students', 'address')) {
            $payload['address'] = $data['address'] ?? null;
        }
        if (Schema::hasColumn('students', 'aadhar_no')) {
            $payload['aadhar_no'] = $data['aadhar_no'] ?? null;
        }
        if (Schema::hasColumn('students', 'active')) {
            $payload['active'] = (int) ($data['active'] ?? 1);
        }

        return $payload;
    }

    private function saveAadharDetails(array $data, int $clientId, int $studentId, ?int $parentId): void
    {
        if (!Schema::hasTable('aadhar_details')) {
            return;
        }

        $payload = [];
        if (Schema::hasColumn('aadhar_details', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('aadhar_details', 'student_id')) {
            $payload['student_id'] = $studentId;
        }
        if (Schema::hasColumn('aadhar_details', 'parent_id') && $parentId) {
            $payload['parent_id'] = $parentId;
        }
        if (Schema::hasColumn('aadhar_details', 'aadhar_no')) {
            $payload['aadhar_no'] = $data['aadhar_no'] ?? null;
        }

        $query = DB::table('aadhar_details');
        if (Schema::hasColumn('aadhar_details', 'student_id')) {
            $query->where('student_id', $studentId);
        }
        if (Schema::hasColumn('aadhar_details', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        $existing = $query->first();
        if ($existing) {
            $payload = ModelHelper::applyTimestamps('aadhar_details', $payload, false);
            DB::table('aadhar_details')->where('id', $existing->id)->update($payload);
        } else {
            $payload = ModelHelper::applyTimestamps('aadhar_details', $payload, true);
            DB::table('aadhar_details')->insert($payload);
        }
    }

    private function saveDocuments(array $data, int $clientId, int $studentId, ?int $parentId): void
    {
        if (!Schema::hasTable('documents')) {
            return;
        }

        $payload = [];
        if (Schema::hasColumn('documents', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('documents', 'student_id')) {
            $payload['student_id'] = $studentId;
        }
        if (Schema::hasColumn('documents', 'parent_id') && $parentId) {
            $payload['parent_id'] = $parentId;
        }
        if (Schema::hasColumn('documents', 'document_type')) {
            $payload['document_type'] = $data['document_type'] ?? 'Aadhar';
        }
        if (Schema::hasColumn('documents', 'document_no')) {
            $payload['document_no'] = $data['document_no'] ?? ($data['aadhar_no'] ?? null);
        }

        $query = DB::table('documents');
        if (Schema::hasColumn('documents', 'student_id')) {
            $query->where('student_id', $studentId);
        }
        if (Schema::hasColumn('documents', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        $existing = $query->first();
        if ($existing) {
            $payload = ModelHelper::applyTimestamps('documents', $payload, false);
            DB::table('documents')->where('id', $existing->id)->update($payload);
        } else {
            $payload = ModelHelper::applyTimestamps('documents', $payload, true);
            DB::table('documents')->insert($payload);
        }
    }

    private function saveParentAccess(int $clientId, int $studentId, ?int $parentId): void
    {
        if (!Schema::hasTable('parent_access') || !$parentId) {
            return;
        }

        $payload = [];
        if (Schema::hasColumn('parent_access', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('parent_access', 'student_id')) {
            $payload['student_id'] = $studentId;
        }
        if (Schema::hasColumn('parent_access', 'parent_id')) {
            $payload['parent_id'] = $parentId;
        }
        if (Schema::hasColumn('parent_access', 'active')) {
            $payload['active'] = 1;
        }

        $query = DB::table('parent_access');
        if (Schema::hasColumn('parent_access', 'student_id')) {
            $query->where('student_id', $studentId);
        }
        if (Schema::hasColumn('parent_access', 'parent_id')) {
            $query->where('parent_id', $parentId);
        }

        $existing = $query->first();
        if ($existing) {
            $payload = ModelHelper::applyTimestamps('parent_access', $payload, false);
            DB::table('parent_access')->where('id', $existing->id)->update($payload);
        } else {
            $payload = ModelHelper::applyTimestamps('parent_access', $payload, true);
            DB::table('parent_access')->insert($payload);
        }
    }

    private function saveTeacherUser(array $data, int $clientId, ?int $teacherId): ?int
    {
        if (!Schema::hasTable('users')) {
            return null;
        }

        $existingUserId = null;
        if ($teacherId && Schema::hasColumn('teachers', 'user_id')) {
            $existingUserId = DB::table('teachers')->where('id', $teacherId)->value('user_id');
        }

        $payload = [];
        if (Schema::hasColumn('users', 'name')) {
            $payload['name'] = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')) ?: ($data['first_name'] ?? 'Teacher');
        }
        if (Schema::hasColumn('users', 'email') && !empty($data['email'])) {
            $payload['email'] = $data['email'];
        }
        if (Schema::hasColumn('users', 'mobile') && !empty($data['mobile'])) {
            $payload['mobile'] = $data['mobile'];
        }
        if (Schema::hasColumn('users', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('users', 'active')) {
            $payload['active'] = (int) ($data['active'] ?? 1);
        }
        foreach (['priv', 'privillage', 'privilege', 'privilege_id', 'role_id', 'user_type'] as $col) {
            if (Schema::hasColumn('users', $col)) {
                $payload[$col] = 3;
                break;
            }
        }

        if ($existingUserId) {
            $payload = ModelHelper::applyTimestamps('users', $payload, false);
            DB::table('users')->where('id', $existingUserId)->update($payload);
            return (int) $existingUserId;
        }

        if (Schema::hasColumn('users', 'password')) {
            $payload['password'] = Hash::make(Str::password(10));
        }
        $payload = ModelHelper::applyTimestamps('users', $payload, true);

        return (int) DB::table('users')->insertGetId($payload);
    }

    private function buildTeacherPayload(array $data, int $clientId, ?int $teacherUserId): array
    {
        $payload = [];

        if (Schema::hasColumn('teachers', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('teachers', 'user_id') && $teacherUserId) {
            $payload['user_id'] = $teacherUserId;
        }
        if (Schema::hasColumn('teachers', 'first_name')) {
            $payload['first_name'] = $data['first_name'];
        }
        if (Schema::hasColumn('teachers', 'last_name')) {
            $payload['last_name'] = $data['last_name'] ?? null;
        }
        if (Schema::hasColumn('teachers', 'name')) {
            $payload['name'] = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        }
        if (Schema::hasColumn('teachers', 'dob')) {
            $payload['dob'] = $data['dob'] ?? null;
        }
        if (Schema::hasColumn('teachers', 'gender')) {
            $payload['gender'] = $data['gender'] ?? null;
        }
        if (Schema::hasColumn('teachers', 'mobile')) {
            $payload['mobile'] = $data['mobile'] ?? null;
        }
        if (Schema::hasColumn('teachers', 'email')) {
            $payload['email'] = $data['email'] ?? null;
        }
        if (Schema::hasColumn('teachers', 'address')) {
            $payload['address'] = $data['address'] ?? null;
        }
        if (Schema::hasColumn('teachers', 'aadhar_no')) {
            $payload['aadhar_no'] = $data['aadhar_no'] ?? null;
        }
        if (Schema::hasColumn('teachers', 'active')) {
            $payload['active'] = (int) ($data['active'] ?? 1);
        }

        return $payload;
    }

    private function saveAadharDetailsForTeacher(array $data, int $clientId, int $teacherId): void
    {
        if (!Schema::hasTable('aadhar_details')) {
            return;
        }

        $payload = [];
        if (Schema::hasColumn('aadhar_details', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('aadhar_details', 'teacher_id')) {
            $payload['teacher_id'] = $teacherId;
        }
        if (Schema::hasColumn('aadhar_details', 'aadhar_no')) {
            $payload['aadhar_no'] = $data['aadhar_no'] ?? null;
        }

        $query = DB::table('aadhar_details');
        if (Schema::hasColumn('aadhar_details', 'teacher_id')) {
            $query->where('teacher_id', $teacherId);
        } elseif (Schema::hasColumn('aadhar_details', 'user_type') && Schema::hasColumn('aadhar_details', 'user_id')) {
            $query->where('user_type', 'teacher')->where('user_id', $teacherId);
            $payload['user_type'] = 'teacher';
            $payload['user_id'] = $teacherId;
        } else {
            return;
        }

        if (Schema::hasColumn('aadhar_details', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        $existing = $query->first();
        if ($existing) {
            $payload = ModelHelper::applyTimestamps('aadhar_details', $payload, false);
            DB::table('aadhar_details')->where('id', $existing->id)->update($payload);
        } else {
            $payload = ModelHelper::applyTimestamps('aadhar_details', $payload, true);
            DB::table('aadhar_details')->insert($payload);
        }
    }

    private function saveDocumentsForTeacher(array $data, int $clientId, int $teacherId): void
    {
        if (!Schema::hasTable('documents')) {
            return;
        }

        $payload = [];
        if (Schema::hasColumn('documents', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('documents', 'teacher_id')) {
            $payload['teacher_id'] = $teacherId;
        } elseif (Schema::hasColumn('documents', 'user_type') && Schema::hasColumn('documents', 'user_id')) {
            $payload['user_type'] = 'teacher';
            $payload['user_id'] = $teacherId;
        }
        if (Schema::hasColumn('documents', 'document_type')) {
            $payload['document_type'] = $data['document_type'] ?? 'Aadhar';
        }
        if (Schema::hasColumn('documents', 'document_no')) {
            $payload['document_no'] = $data['document_no'] ?? ($data['aadhar_no'] ?? null);
        }

        $query = DB::table('documents');
        if (Schema::hasColumn('documents', 'teacher_id')) {
            $query->where('teacher_id', $teacherId);
        } elseif (Schema::hasColumn('documents', 'user_type') && Schema::hasColumn('documents', 'user_id')) {
            $query->where('user_type', 'teacher')->where('user_id', $teacherId);
        } else {
            return;
        }
        if (Schema::hasColumn('documents', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        $existing = $query->first();
        if ($existing) {
            $payload = ModelHelper::applyTimestamps('documents', $payload, false);
            DB::table('documents')->where('id', $existing->id)->update($payload);
        } else {
            $payload = ModelHelper::applyTimestamps('documents', $payload, true);
            DB::table('documents')->insert($payload);
        }
    }

    private function saveTeacherAccess(int $clientId, int $teacherId, ?int $teacherUserId): void
    {
        if (!Schema::hasTable('teacher_access')) {
            return;
        }

        $payload = [];
        if (Schema::hasColumn('teacher_access', 'client_id')) {
            $payload['client_id'] = $clientId;
        }
        if (Schema::hasColumn('teacher_access', 'teacher_id')) {
            $payload['teacher_id'] = $teacherId;
        }
        if (Schema::hasColumn('teacher_access', 'user_id') && $teacherUserId) {
            $payload['user_id'] = $teacherUserId;
        }
        if (Schema::hasColumn('teacher_access', 'active')) {
            $payload['active'] = 1;
        }

        $query = DB::table('teacher_access');
        if (Schema::hasColumn('teacher_access', 'teacher_id')) {
            $query->where('teacher_id', $teacherId);
        } elseif (Schema::hasColumn('teacher_access', 'user_id') && $teacherUserId) {
            $query->where('user_id', $teacherUserId);
        } else {
            return;
        }

        $existing = $query->first();
        if ($existing) {
            $payload = ModelHelper::applyTimestamps('teacher_access', $payload, false);
            DB::table('teacher_access')->where('id', $existing->id)->update($payload);
        } else {
            $payload = ModelHelper::applyTimestamps('teacher_access', $payload, true);
            DB::table('teacher_access')->insert($payload);
        }
    }

    private function logUserActivity(
        int $clientId,
        ?int $userId,
        string $activity,
        string $activityType = 'student',
        string $module = 'students'
    ): void
    {
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

    private function fetchParentData(array $student): ?array
    {
        if (!Schema::hasTable('parents')) {
            return null;
        }

        $query = DB::table('parents');
        if (isset($student['parent_id']) && Schema::hasColumn('parents', 'id')) {
            $query->where('id', (int) $student['parent_id']);
        } elseif (isset($student['client_id']) && Schema::hasColumn('parents', 'client_id')) {
            $query->where('client_id', (int) $student['client_id']);
            if (Schema::hasColumn('parents', 'mobile') && !empty($student['mobile'])) {
                $query->where('mobile', $student['mobile']);
            }
        }

        $row = $query->first();
        return $row ? (array) $row : null;
    }
    private function getTableStats(array $tableCandidates, int $clientId): array
    {
        $table = ModelHelper::resolveTable($tableCandidates);
        if (!$table) {
            return ['active' => 0, 'inactive' => 0, 'total' => 0];
        }

        if ($clientId <= 0 || !Schema::hasColumn($table, 'client_id')) {
            return ['active' => 0, 'inactive' => 0, 'total' => 0];
        }

        $base = DB::table($table);
        $base->where('client_id', $clientId);
        $total = (int) (clone $base)->count();

        if (!Schema::hasColumn($table, 'active')) {
            return ['active' => $total, 'inactive' => 0, 'total' => $total];
        }

        $active = (int) (clone $base)->where('active', 1)->count();
        $inactive = (int) (clone $base)->where('active', 0)->count();

        return ['active' => $active, 'inactive' => $inactive, 'total' => $total];
    }

    private function buildAttendanceStats(int $clientId): array
    {
        $statuses = $this->getAttendanceStatuses();
        if (empty($statuses)) {
            return [];
        }

        $counts = [];
        foreach ($statuses as $status) {
            $counts[(string) ($status['code'] ?? '')] = 0;
        }

        if (Schema::hasTable('attendances') && $clientId > 0) {
            $rows = DB::table('attendances')
                ->select('status', DB::raw('COUNT(*) as total'))
                ->where('client_id', $clientId)
                ->where('user_type', 'student')
                ->whereDate('attendance_date', now()->toDateString())
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
                'key' => $code,
                'label' => (string) ($status['label'] ?? $code),
                'count' => $count,
                'percent' => $grandTotal > 0 ? (int) round(($count / $grandTotal) * 100) : 0,
                'bar_class' => (string) ($status['bar_class'] ?? 'bg-neutral-300'),
                'badge_class' => (string) ($status['badge_class'] ?? 'text-bg-secondary'),
            ];
        }, $statuses);
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
    private function getClientRows(string $table, int $clientId): array
    {
        if ($clientId <= 0 || !Schema::hasTable($table) || !Schema::hasColumn($table, 'client_id')) {
            return [];
        }

        $query = DB::table($table)->where('client_id', $clientId);

        if (Schema::hasColumn($table, 'id')) {
            $query->orderByDesc('id');
        } elseif (Schema::hasColumn($table, 'created_at')) {
            $query->orderByDesc('created_at');
        }

        return $query->get()->map(function ($row) {
            return (array) $row;
        })->all();
    }

}
