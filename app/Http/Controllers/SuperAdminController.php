<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $privColumn = $this->resolvePrivilegeColumnOnUsers();

        $stats = [
            'users' => $this->getUsersStats(),
            'clients' => $this->getRoleStatsByPriv($privColumn, 2),  // Admin => School/Client
            'students' => $this->getRoleStatsByPriv($privColumn, 4), // Student
            'teachers' => $this->getRoleStatsByPriv($privColumn, 3), // Teacher
            'parents' => $this->getRoleStatsByPriv($privColumn, 5),  // Parent
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function usersList()
    {
        return $this->usersByType('users');
    }

    public function createSchoolPage()
    {
        return view('admin.users.create-school');
    }

    public function schoolServicesPage(int $id)
    {
        if (!Schema::hasTable('users') || !Schema::hasTable('services') || !Schema::hasTable('client_services')) {
            return back()->with('failure', 'Required tables not found for services mapping.');
        }

        $privColumn = $this->resolvePrivilegeColumnOnUsers();
        $schoolQuery = DB::table('users')->where('id', $id);
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

        $services = DB::table('services')
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

        $mappings = DB::table('client_services')
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
        $schoolQuery = DB::table('users')->where('id', $id);
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

        DB::table('client_services')->where($clientColumn, $id)->delete();

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

            DB::table('client_services')->insert($insert);
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
            return response()->json(['success' => false, 'message' => 'Users table not found.'], 422);
        }

        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'erp_id' => ['nullable', 'string', 'max:50'],
        ];

        if (Schema::hasColumn('users', 'email')) {
            $validationRules['email'][] = 'unique:users,email';
        }
        if (!Schema::hasColumn('users', 'erp_id')) {
            unset($validationRules['erp_id']);
        }

        $validated = $request->validate($validationRules);

        $insert = [];
        if (Schema::hasColumn('users', 'name')) {
            $insert['name'] = $validated['name'];
        }
        if (Schema::hasColumn('users', 'email')) {
            $insert['email'] = $validated['email'];
        }
        if (Schema::hasColumn('users', 'password')) {
            $insert['password'] = Hash::make($validated['password']);
        }
        if (Schema::hasColumn('users', 'erp_id')) {
            $insert['erp_id'] = $validated['erp_id'] ?? ('SCH' . date('ymdHis'));
        }

        $privColumn = $this->resolvePrivilegeColumnOnUsers();
        if ($privColumn) {
            $insert[$privColumn] = 2; // school/client role
        }
        if (Schema::hasColumn('users', 'active')) {
            $insert['active'] = 1;
        }
        if (Schema::hasColumn('users', 'created_at')) {
            $insert['created_at'] = now();
        }
        if (Schema::hasColumn('users', 'updated_at')) {
            $insert['updated_at'] = now();
        }

        DB::table('users')->insert($insert);

        return response()->json(['success' => true, 'message' => 'School created successfully.']);
    }

    public function updateUserStatus(Request $request, int $id)
    {
        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'active') || !Schema::hasColumn('users', 'id')) {
            return back()->with('failure', 'Unable to update status.');
        }

        $data = $request->validate([
            'active' => ['required', 'in:0,1'],
        ]);

        $query = DB::table('users')->where('id', $id);
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

        $base = DB::table('users');
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

        $total = (int) DB::table('clients')->count();
        $userForeignKey = $this->resolveUserForeignKey('clients');

        if (
            Schema::hasTable('users') &&
            $userForeignKey &&
            Schema::hasColumn('users', 'id') &&
            Schema::hasColumn('users', 'active')
        ) {
            $active = (int) DB::table('clients')
                ->join('users', 'users.id', '=', "clients.{$userForeignKey}")
                ->where('users.active', 1)
                ->count();

            $inactive = (int) DB::table('clients')
                ->join('users', 'users.id', '=', "clients.{$userForeignKey}")
                ->where('users.active', 0)
                ->count();

            return ['active' => $active, 'inactive' => $inactive, 'total' => $total];
        }

        if (Schema::hasColumn('clients', 'active')) {
            $active = (int) DB::table('clients')->where('active', 1)->count();
            $inactive = (int) DB::table('clients')->where('active', 0)->count();
            return ['active' => $active, 'inactive' => $inactive, 'total' => $total];
        }

        return ['active' => $total, 'inactive' => 0, 'total' => $total];
    }

    private function getRoleStatsByPriv(?string $privColumn, int $privValue): array
    {
        if (!Schema::hasTable('users') || !$privColumn) {
            return ['active' => 0, 'inactive' => 0, 'total' => 0];
        }

        $base = DB::table('users')->where($privColumn, $privValue);
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
        $query = DB::table('users');
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

}
