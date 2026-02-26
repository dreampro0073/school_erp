<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\ClientStandard;
use App\Models\Service;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function initDashboard(Request $request)
    {
        $privColumn = $this->resolvePrivilegeColumnOnUsers();

        $stats = [
            'users' => $this->getUsersStats(),
            'clients' => $this->getRoleStatsByPriv($privColumn, 2),
            'students' => $this->getRoleStatsByPriv($privColumn, 4),
            'teachers' => $this->getRoleStatsByPriv($privColumn, 3),
            'parents' => $this->getRoleStatsByPriv($privColumn, 5),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }

    public function usersList()
    {
        return $this->usersByType('users');
    }

    public function createSchoolPage()
    {
        $services = $this->getSelectableServices();
        $standards = $this->getSelectableStandards();

        return view('admin.users.create-school', [
            'isEdit' => false,
            'school' => null,
            'services' => $services,
            'standards' => $standards,
            'selectedServiceIds' => $this->extractSelectedIdsFromInput(old('services', [])),
            'selectedStandardIds' => $this->extractSelectedIdsFromInput(old('standards', [])),
        ]);
    }

    public function editSchoolPage(int $id)
    {
        if (!Schema::hasTable('users')) {
            return back()->with('failure', 'Users table not found.');
        }

        $query = User::query()->where('id', $id);
        $privColumn = $this->resolvePrivilegeColumnOnUsers();
        if ($privColumn) {
            $query->where($privColumn, 2);
        }

        $school = $query->first();
        if (!$school) {
            return back()->with('failure', 'School not found.');
        }

        $services = $this->getSelectableServices();
        $standards = $this->getSelectableStandards();

        return view('admin.users.create-school', [
            'isEdit' => true,
            'school' => $school,
            'services' => $services,
            'standards' => $standards,
            'selectedServiceIds' => $this->extractSelectedIdsFromInput(
                old('services', $this->getSelectedServiceIdsForSchool((int) $id))
            ),
            'selectedStandardIds' => $this->extractSelectedIdsFromInput(
                old('standards', $this->getSelectedStandardIdsForSchool((int) $id))
            ),
        ]);
    }

    public function schoolServicesPage(int $id)
    {
        if (!Schema::hasTable('users') || !Schema::hasTable('services') || !Schema::hasTable('client_services')) {
            return back()->with('failure', 'Required tables not found for services mapping.');
        }

        $privColumn = $this->resolvePrivilegeColumnOnUsers();
        $schoolQuery = User::query()->where('id', $id);
        if ($privColumn) {
            $schoolQuery->where($privColumn, 2);
        }
        $school = $schoolQuery->first();

        if (!$school) {
            return back()->with('failure', 'School not found.');
        }

        $serviceIdColumn = $this->resolveColumn('services', ['id', 'service_id', 'sid']);
        $serviceNameColumn = $this->resolveColumn('services', ['name', 'service_name', 'title']);
        if (!$serviceIdColumn || !$serviceNameColumn) {
            return back()->with('failure', 'Services table structure is not supported.');
        }

        $services = Service::query()
            ->select([
                "{$serviceIdColumn} as id",
                "{$serviceNameColumn} as name",
            ])
            ->orderBy($serviceNameColumn)
            ->get();

        $clientColumn = $this->resolveColumn('client_services', ['client_id', 'school_id', 'user_id', 'users_id']);
        $serviceColumn = $this->resolveColumn('client_services', ['service_id', 'services_id']);
        if (!$clientColumn || !$serviceColumn) {
            return back()->with('failure', 'client_services table structure is not supported.');
        }

        $mappings = ClientService::query()
            ->where($clientColumn, $id)
            ->get();

        $selected = [];

        foreach ($mappings as $mapping) {
            $sid = (int) $mapping->{$serviceColumn};
            $selected[$sid] = ['enabled' => true];
        }

        return view('admin.users.school-services', [
            'school' => $school,
            'services' => $services,
            'selected' => $selected,
        ]);
    }

    public function saveSchoolServices(Request $request, int $id)
    {
        if (!Schema::hasTable('users') || !Schema::hasTable('services') || !Schema::hasTable('client_services')) {
            return back()->with('failure', 'Required tables not found for services mapping.');
        }

        $privColumn = $this->resolvePrivilegeColumnOnUsers();
        $schoolQuery = User::query()->where('id', $id);
        if ($privColumn) {
            $schoolQuery->where($privColumn, 2);
        }
        $school = $schoolQuery->first();
        if (!$school) {
            return back()->with('failure', 'School not found.');
        }

        $servicesInput = $request->input('services', []);
        if (!is_array($servicesInput)) {
            $servicesInput = [];
        }

        $clientColumn = $this->resolveColumn('client_services', ['client_id', 'school_id', 'user_id', 'users_id']);
        $serviceColumn = $this->resolveColumn('client_services', ['service_id', 'services_id']);
        if (!$clientColumn || !$serviceColumn) {
            return back()->with('failure', 'client_services table structure is not supported.');
        }

        $permissionColumn = $this->resolveColumn('client_services', ['permissions', 'permission', 'rights', 'access']);

        ClientService::query()->where($clientColumn, $id)->delete();

        foreach ($servicesInput as $serviceId => $payload) {
            if (!is_array($payload) || empty($payload['enabled'])) {
                continue;
            }

            $insert = [
                $clientColumn => $id,
                $serviceColumn => (int) $serviceId,
            ];

            if ($permissionColumn) {
                $insert[$permissionColumn] = '1';
            }

            foreach (['view', 'add', 'edit', 'delete'] as $flag) {
                if (Schema::hasColumn('client_services', $flag)) {
                    $insert[$flag] = 1;
                }
            }

            if (Schema::hasColumn('client_services', 'active')) {
                $insert['active'] = 1;
            }
            if (Schema::hasColumn('client_services', 'created_at')) {
                $insert['created_at'] = now();
            }
            if (Schema::hasColumn('client_services', 'updated_at')) {
                $insert['updated_at'] = now();
            }

            ClientService::query()->insert($insert);
        }

        return back()->with('success', 'School services updated successfully.');
    }

    public function usersByType(string $type)
    {
        if (!Schema::hasTable('users')) {
            return view('admin.users.index', [
                'users' => collect(),
                'columns' => [],
                'pageTitle' => 'Users',
                'pageSubtitle' => 'No users table found.',
            ]);
        }

        $preferredColumns = ['id', 'erp_id', 'name', 'email', 'active', 'created_at'];
        $columns = array_values(array_filter($preferredColumns, fn ($column) => Schema::hasColumn('users', $column)));

        $queryMeta = $this->buildUsersTypeQuery($type);
        $query = $queryMeta['query'];

        if (!empty($columns)) {
            $query->select($columns);
        }

        if (Schema::hasColumn('users', 'id')) {
            $query->orderByDesc('id');
        } elseif (Schema::hasColumn('users', 'created_at')) {
            $query->orderByDesc('created_at');
        }

        $users = $query->paginate(15)->withQueryString();

        $pageTitle = $queryMeta['title'];
        $pageSubtitle = $queryMeta['subtitle'];
        $currentType = $queryMeta['type'];

        return view('admin.users.index', compact('users', 'columns', 'pageTitle', 'pageSubtitle', 'currentType'));
    }

    public function createSchool(Request $request)
    {
        if (!Schema::hasTable('users')) {
            return back()->with('failure', 'Users table not found.');
        }

        $validated = $this->validateSchoolPayload($request, null);
        $validated['erp_id'] = $this->generateUniqueErpId();
        $validated['password'] = $this->generateRandomPassword();

        $insert = $this->buildSchoolUserPayload($validated, true);
        User::query()->insert($insert);

        $userId = 0;
        if (Schema::hasColumn('users', 'id')) {
            $userId = (int) User::query()
                ->where('email', $validated['email'])
                ->orderByDesc('id')
                ->value('id');
        }

        if ($userId > 0) {
            $this->syncClientRecordFromUser($userId, $validated, null);
            $this->syncClientServicesFromIds($userId, $this->extractSelectedIdsFromInput($request->input('services', [])));
            $this->syncClientStandardsFromIds($userId, $this->extractSelectedIdsFromInput($request->input('standards', [])));
        }

        return redirect()->route('super-admin.users.type', ['type' => 'schools'])
            ->with('success', 'School created successfully.');
    }

    public function updateSchool(Request $request, int $id)
    {
        if (!Schema::hasTable('users')) {
            return back()->with('failure', 'Users table not found.');
        }

        $query = User::query()->where('id', $id);
        $privColumn = $this->resolvePrivilegeColumnOnUsers();
        if ($privColumn) {
            $query->where($privColumn, 2);
        }
        $school = $query->first();
        if (!$school) {
            return back()->with('failure', 'School not found.');
        }

        $validated = $this->validateSchoolPayload($request, $id);
        $update = $this->buildSchoolUserPayload($validated, false);
        if (!empty($update)) {
            User::query()->where('id', $id)->update($update);
        }

        $syncPayload = array_merge($validated, [
            'erp_id' => $school->erp_id ?? null,
        ]);
        $this->syncClientRecordFromUser($id, $syncPayload, $school);
        $this->syncClientServicesFromIds($id, $this->extractSelectedIdsFromInput($request->input('services', [])));
        $this->syncClientStandardsFromIds($id, $this->extractSelectedIdsFromInput($request->input('standards', [])));

        return redirect()->route('super-admin.users.type', ['type' => 'schools'])
            ->with('success', 'School updated successfully.');
    }

    public function updateUserStatus(Request $request, int $id)
    {
        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'active') || !Schema::hasColumn('users', 'id')) {
            return back()->with('failure', 'Unable to update status.');
        }

        $data = $request->validate([
            'active' => ['required', 'in:0,1'],
        ]);

        $query = User::query()->where('id', $id);
        $privColumn = $this->resolvePrivilegeColumnOnUsers();
        if ($privColumn) {
            $query->where($privColumn, '!=', 1);
        }

        $updated = $query->update(['active' => (int) $data['active']]);

        if (!$updated) {
            return back()->with('failure', 'User status not changed.');
        }

        return back()->with('success', 'User status updated successfully.');
    }

    private function getUsersStats(): array
    {
        if (!Schema::hasTable('users')) {
            return ['active' => 0, 'inactive' => 0, 'total' => 0];
        }

        $base = User::query();
        $privColumn = $this->resolvePrivilegeColumnOnUsers();
        if ($privColumn) {
            $base->where($privColumn, '!=', 1);
        }

        if (!Schema::hasColumn('users', 'active')) {
            $total = (int) (clone $base)->count();
            return ['active' => $total, 'inactive' => 0, 'total' => $total];
        }

        $active = (int) (clone $base)->where('active', 1)->count();
        $inactive = (int) (clone $base)->where('active', 0)->count();
        $total = (int) (clone $base)->count();

        return ['active' => $active, 'inactive' => $inactive, 'total' => $total];
    }

    private function getSimpleStats(array $tableCandidates): array
    {
        $table = $this->resolveTable($tableCandidates);
        if (!$table) {
            return ['active' => 0, 'inactive' => 0, 'total' => 0];
        }

        $base = DB::table($table);
        $total = (int) (clone $base)->count();

        if (!Schema::hasColumn($table, 'active')) {
            return ['active' => $total, 'inactive' => 0, 'total' => $total];
        }

        $active = (int) (clone $base)->where('active', 1)->count();
        $inactive = (int) (clone $base)->where('active', 0)->count();

        return ['active' => $active, 'inactive' => $inactive, 'total' => $total];
    }

    private function getEntityStats(array $tableCandidates): array
    {
        $table = $this->resolveTable($tableCandidates);
        if (!$table) {
            return ['active' => 0, 'inactive' => 0, 'total' => 0];
        }

        $total = (int) DB::table($table)->count();

        if (!Schema::hasTable('users')) {
            return ['active' => $total, 'inactive' => 0, 'total' => $total];
        }

        $userForeignKey = $this->resolveUserForeignKey($table);
        if (!$userForeignKey || !Schema::hasColumn('users', 'id') || !Schema::hasColumn('users', 'active')) {
            return ['active' => $total, 'inactive' => 0, 'total' => $total];
        }

        $active = (int) DB::table($table)
            ->join('users', 'users.id', '=', "{$table}.{$userForeignKey}")
            ->where('users.active', 1)
            ->count();

        $inactive = (int) DB::table($table)
            ->join('users', 'users.id', '=', "{$table}.{$userForeignKey}")
            ->where('users.active', 0)
            ->count();

        return ['active' => $active, 'inactive' => $inactive, 'total' => $total];
    }

    private function getClientsAsSchoolStats(): array
    {
        if (!Schema::hasTable('clients')) {
            return ['active' => 0, 'inactive' => 0, 'total' => 0];
        }

        $total = (int) Client::query()->count();
        $userForeignKey = $this->resolveUserForeignKey('clients');

        if (
            Schema::hasTable('users') &&
            $userForeignKey &&
            Schema::hasColumn('users', 'id') &&
            Schema::hasColumn('users', 'active')
        ) {
            $active = (int) Client::query()
                ->join('users', 'users.id', '=', "clients.{$userForeignKey}")
                ->where('users.active', 1)
                ->count();

            $inactive = (int) Client::query()
                ->join('users', 'users.id', '=', "clients.{$userForeignKey}")
                ->where('users.active', 0)
                ->count();

            return ['active' => $active, 'inactive' => $inactive, 'total' => $total];
        }

        if (Schema::hasColumn('clients', 'active')) {
            $active = (int) Client::query()->where('active', 1)->count();
            $inactive = (int) Client::query()->where('active', 0)->count();
            return ['active' => $active, 'inactive' => $inactive, 'total' => $total];
        }

        return ['active' => $total, 'inactive' => 0, 'total' => $total];
    }

    private function getRoleStatsByPriv(?string $privColumn, int $privValue): array
    {
        if (!Schema::hasTable('users') || !$privColumn) {
            return ['active' => 0, 'inactive' => 0, 'total' => 0];
        }

        $base = User::query()->where($privColumn, $privValue);
        $total = (int) (clone $base)->count();

        if (!Schema::hasColumn('users', 'active')) {
            return ['active' => $total, 'inactive' => 0, 'total' => $total];
        }

        $active = (int) (clone $base)->where('active', 1)->count();
        $inactive = (int) (clone $base)->where('active', 0)->count();

        return ['active' => $active, 'inactive' => $inactive, 'total' => $total];
    }

    private function resolveTable(array $candidates): ?string
    {
        foreach ($candidates as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }

    private function resolveUserForeignKey(string $table): ?string
    {
        foreach (['user_id', 'users_id', 'userid', 'userId'] as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }

        return null;
    }

    private function resolvePrivilegeColumnOnUsers(): ?string
    {
        foreach (['priv', 'privillage', 'privilege', 'privilege_id', 'role_id', 'user_type'] as $column) {
            if (Schema::hasColumn('users', $column)) {
                return $column;
            }
        }

        return null;
    }

    private function resolveColumn(string $table, array $candidates): ?string
    {
        if (!Schema::hasTable($table)) {
            return null;
        }

        foreach ($candidates as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }

        return null;
    }

    private function buildUsersTypeQuery(string $type): array
    {
        $privColumn = $this->resolvePrivilegeColumnOnUsers();
        $query = User::query();
        $type = strtolower($type);

        $title = 'Users';
        $subtitle = 'All users list (Super Admin skipped).';

        if ($privColumn) {
            $query->where($privColumn, '!=', 1);
        }

        if ($type === 'schools') {
            $title = 'Schools';
            $subtitle = 'Users with school/client role.';
            if ($privColumn) {
                $query->where($privColumn, 2);
            }
        } elseif ($type === 'students') {
            $title = 'Students';
            $subtitle = 'Users with student role.';
            if ($privColumn) {
                $query->where($privColumn, 4);
            }
        } elseif ($type === 'teachers') {
            $title = 'Teachers';
            $subtitle = 'Users with teacher role.';
            if ($privColumn) {
                $query->where($privColumn, 3);
            }
        } elseif ($type === 'parents') {
            $title = 'Parents';
            $subtitle = 'Users with parent role.';
            if ($privColumn) {
                $query->where($privColumn, 5);
            }
        } elseif ($type === 'inactive-users') {
            $title = 'Inactive Users';
            $subtitle = 'Users where active = 0 (Super Admin skipped).';
            if (Schema::hasColumn('users', 'active')) {
                $query->where('active', 0);
            }
        } else {
            $type = 'users';
            if ($privColumn) {
                $query->whereIn($privColumn, [2, 3, 4, 5]);
            }
        }

        return [
            'query' => $query,
            'title' => $title,
            'subtitle' => $subtitle,
            'type' => $type,
        ];
    }

    private function validateSchoolPayload(Request $request, ?int $userId): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'services' => ['nullable', 'array'],
            'services.*' => ['integer'],
            'standards' => ['nullable', 'array'],
            'standards.*' => ['integer'],
        ];

        if (Schema::hasColumn('users', 'email')) {
            $emailRule = Rule::unique('users', 'email');
            if ($userId !== null) {
                $emailRule = $emailRule->ignore($userId, 'id');
            }
            $rules['email'][] = $emailRule;
        }

        return $request->validate($rules);
    }

    private function buildSchoolUserPayload(array $validated, bool $isCreate): array
    {
        $payload = [];

        if (Schema::hasColumn('users', 'name')) {
            $payload['name'] = $validated['name'];
        }
        if (Schema::hasColumn('users', 'email')) {
            $payload['email'] = $validated['email'];
        }
        if (Schema::hasColumn('users', 'erp_id') && !empty($validated['erp_id'])) {
            $payload['erp_id'] = $validated['erp_id'];
        }
        if (Schema::hasColumn('users', 'password') && !empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }
        if ($isCreate) {
            $privColumn = $this->resolvePrivilegeColumnOnUsers();
            if ($privColumn) {
                $payload[$privColumn] = 2;
            }
            if (Schema::hasColumn('users', 'active')) {
                $payload['active'] = 1;
            }
            if (Schema::hasColumn('users', 'created_at')) {
                $payload['created_at'] = now();
            }
        }
        if (Schema::hasColumn('users', 'updated_at')) {
            $payload['updated_at'] = now();
        }

        return $payload;
    }

    private function syncClientRecordFromUser(int $userId, array $validated, $previousUser = null): void
    {
        if (!Schema::hasTable('clients')) {
            return;
        }

        $clientUserFk = $this->resolveColumn('clients', ['user_id', 'users_id', 'userid', 'userId']);
        $clientNameCol = $this->resolveColumn('clients', ['name', 'client_name', 'school_name', 'title']);
        $clientEmailCol = $this->resolveColumn('clients', ['email', 'client_email']);
        $clientErpCol = $this->resolveColumn('clients', ['erp_id', 'client_code', 'school_code']);

        $clientPayload = [];
        if ($clientUserFk) {
            $clientPayload[$clientUserFk] = $userId;
        }
        if ($clientNameCol) {
            $clientPayload[$clientNameCol] = $validated['name'];
        }
        if ($clientEmailCol) {
            $clientPayload[$clientEmailCol] = $validated['email'];
        }
        if ($clientErpCol) {
            $clientPayload[$clientErpCol] = $validated['erp_id'];
        }
        if (Schema::hasColumn('clients', 'active')) {
            $clientPayload['active'] = 1;
        }
        if (Schema::hasColumn('clients', 'updated_at')) {
            $clientPayload['updated_at'] = now();
        }

        if (empty($clientPayload)) {
            return;
        }

        $query = Client::query();
        if ($clientUserFk) {
            $query->where($clientUserFk, $userId);
        } elseif ($clientErpCol) {
            $query->whereIn($clientErpCol, array_values(array_unique(array_filter([
                $validated['erp_id'] ?? null,
                $previousUser->{$clientErpCol} ?? null,
            ]))));
        } elseif ($clientEmailCol) {
            $query->whereIn($clientEmailCol, array_values(array_unique(array_filter([
                $validated['email'] ?? null,
                $previousUser->{$clientEmailCol} ?? null,
            ]))));
        } else {
            return;
        }

        $existing = $query->first();
        if ($existing) {
            if ($clientUserFk) {
                Client::query()->where($clientUserFk, $userId)->update($clientPayload);
            } elseif ($clientErpCol) {
                Client::query()
                    ->whereIn($clientErpCol, array_values(array_unique(array_filter([
                        $validated['erp_id'] ?? null,
                        $previousUser->{$clientErpCol} ?? null,
                    ]))))
                    ->update($clientPayload);
            } elseif ($clientEmailCol) {
                Client::query()
                    ->whereIn($clientEmailCol, array_values(array_unique(array_filter([
                        $validated['email'] ?? null,
                        $previousUser->{$clientEmailCol} ?? null,
                    ]))))
                    ->update($clientPayload);
            }
            return;
        }

        if (Schema::hasColumn('clients', 'created_at')) {
            $clientPayload['created_at'] = now();
        }
        Client::query()->insert($clientPayload);
    }

    private function generateUniqueErpId(): string
    {
        $prefix = 'SCH';

        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'erp_id')) {
            return $prefix . date('ymdHis');
        }

        do {
            $candidate = $prefix . strtoupper(Str::random(8));
            $exists = User::query()->where('erp_id', $candidate)->exists();
        } while ($exists);

        return $candidate;
    }

    private function generateRandomPassword(int $length = 12): string
    {
        return Str::random($length) . rand(10, 99);
    }

    private function getSelectableServices()
    {
        if (!Schema::hasTable('services')) {
            return collect();
        }

        $serviceIdColumn = $this->resolveColumn('services', ['id', 'service_id', 'sid']);
        $serviceNameColumn = $this->resolveColumn('services', ['name', 'service_name', 'title']);
        if (!$serviceIdColumn || !$serviceNameColumn) {
            return collect();
        }

        return Service::query()
            ->select([
                "{$serviceIdColumn} as id",
                "{$serviceNameColumn} as name",
            ])
            ->orderBy($serviceNameColumn)
            ->get();
    }

    private function getSelectableStandards()
    {
        if (!Schema::hasTable('standards')) {
            return collect();
        }

        $standardIdColumn = $this->resolveColumn('standards', ['id', 'standard_id', 'sid']);
        $standardNameColumn = $this->resolveColumn('standards', ['name', 'standard_name', 'title']);
        if (!$standardIdColumn || !$standardNameColumn) {
            return collect();
        }

        return Standard::query()
            ->select([
                "{$standardIdColumn} as id",
                "{$standardNameColumn} as name",
            ])
            ->orderBy($standardNameColumn)
            ->get();
    }

    private function getSelectedServiceIdsForSchool(int $schoolId): array
    {
        if (!Schema::hasTable('client_services')) {
            return [];
        }

        $clientColumn = $this->resolveColumn('client_services', ['client_id', 'school_id', 'user_id', 'users_id']);
        $serviceColumn = $this->resolveColumn('client_services', ['service_id', 'services_id']);
        if (!$clientColumn || !$serviceColumn) {
            return [];
        }

        return ClientService::query()
            ->where($clientColumn, $schoolId)
            ->pluck($serviceColumn)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();
    }

    private function getSelectedStandardIdsForSchool(int $schoolId): array
    {
        if (!Schema::hasTable('client_standards')) {
            return [];
        }

        $clientColumn = $this->resolveColumn('client_standards', ['client_id', 'school_id', 'user_id', 'users_id']);
        $standardColumn = $this->resolveColumn('client_standards', ['standard_id', 'standards_id', 'sid']);
        if (!$clientColumn || !$standardColumn) {
            return [];
        }

        return ClientStandard::query()
            ->where($clientColumn, $schoolId)
            ->pluck($standardColumn)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();
    }

    private function extractSelectedIdsFromInput($input): array
    {
        if (!is_array($input)) {
            return [];
        }

        return collect($input)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();
    }

    private function syncClientServicesFromIds(int $schoolId, array $serviceIds): void
    {
        if (!Schema::hasTable('client_services')) {
            return;
        }

        $clientColumn = $this->resolveColumn('client_services', ['client_id', 'school_id', 'user_id', 'users_id']);
        $serviceColumn = $this->resolveColumn('client_services', ['service_id', 'services_id']);
        if (!$clientColumn || !$serviceColumn) {
            return;
        }

        $permissionColumn = $this->resolveColumn('client_services', ['permissions', 'permission', 'rights', 'access']);
        ClientService::query()->where($clientColumn, $schoolId)->delete();

        foreach ($serviceIds as $serviceId) {
            $insert = [
                $clientColumn => $schoolId,
                $serviceColumn => (int) $serviceId,
            ];

            if ($permissionColumn) {
                $insert[$permissionColumn] = '1';
            }

            foreach (['view', 'add', 'edit', 'delete'] as $flag) {
                if (Schema::hasColumn('client_services', $flag)) {
                    $insert[$flag] = 1;
                }
            }
            if (Schema::hasColumn('client_services', 'active')) {
                $insert['active'] = 1;
            }
            if (Schema::hasColumn('client_services', 'created_at')) {
                $insert['created_at'] = now();
            }
            if (Schema::hasColumn('client_services', 'updated_at')) {
                $insert['updated_at'] = now();
            }

            ClientService::query()->insert($insert);
        }
    }

    private function syncClientStandardsFromIds(int $schoolId, array $standardIds): void
    {
        if (!Schema::hasTable('client_standards')) {
            return;
        }

        $clientColumn = $this->resolveColumn('client_standards', ['client_id', 'school_id', 'user_id', 'users_id']);
        $standardColumn = $this->resolveColumn('client_standards', ['standard_id', 'standards_id', 'sid']);
        if (!$clientColumn || !$standardColumn) {
            return;
        }

        ClientStandard::query()->where($clientColumn, $schoolId)->delete();

        foreach ($standardIds as $standardId) {
            $insert = [
                $clientColumn => $schoolId,
                $standardColumn => (int) $standardId,
            ];

            if (Schema::hasColumn('client_standards', 'active')) {
                $insert['active'] = 1;
            }
            if (Schema::hasColumn('client_standards', 'created_at')) {
                $insert['created_at'] = now();
            }
            if (Schema::hasColumn('client_standards', 'updated_at')) {
                $insert['updated_at'] = now();
            }

            ClientStandard::query()->insert($insert);
        }
    }

}
