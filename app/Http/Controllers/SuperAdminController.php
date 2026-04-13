<?php

namespace App\Http\Controllers;
use App\Models\School;
use App\Models\ClientService;
use App\Models\ClientStandard;
use App\Models\Service;
use App\Models\Standard;
use App\Models\User;
use App\Models\ModelHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SuperAdminController extends Controller {
    public function dashboard(){
        return view('super-admin.dashboard');
    }

    public function addSchool($id = 0){
        return view('super-admin.schools.add_school', ['sch_id'=>$id]);
    }

    public function editUsers(Request $request){
        $user = DB::table('schools')->select('schools.name','schools.id','schools.school_name','schools.email','schools.mobile','schools.status','schools.gst','schools.address','users.start_date','users.end_date')
            ->leftJoin('users','users.id','=','schools.user_id')
            ->where('schools.user_id',$request->id)->first();
        if ($user) {
            $data['success'] = true;
            $data['user'] = $user;
        } else{
            $data['success'] = false;
            $data['message'] = "Not found";
        }

        return response()->json($data);
    }

    public function submitUsers(Request $request)
    {
        $authUser = User::resolveApiUser($request);

        $school = School::find($request->id);
        $user = null;

        if ($school) {
            $user = User::where('client_id', $school->id)->where('priv', 2)->first();
        }

        $user_id = $user ? $user->id : 'NULL';
        $message = $school ? "User details successfully updated!" : "User details successfully saved!";

        $validator = Validator::make($request->all(), [
            'school_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:100'],
            'email' => 'required|email|unique:Users,email,' . $user_id,
            'mobile' => ['nullable', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            $data['message'] = $validator->errors()->first();
            $data['success'] = false;
            return response()->json($data);
        }

        DB::beginTransaction();
        try {

            if (!$school) {
                $school = new School;
            }

            $school->name = $request->name;
            $school->school_name = $request->school_name;
            $school->email = $request->email;
            $school->mobile = $request->mobile;
            $school->address = $request->address;
            $school->org_id = $authUser->org_id;
            $school->gst = $request->gst;
            $school->max_users = $request->max_users ?? 0;
            $school->max_logins = $request->max_logins ?? 0;
            $school->status = $request->status ?? 0;
            $school->save();

            if (!$user) {
                $password = User::getRandPassword();

                $user = new User;
                $user->org_id = $authUser->org_id;
                $user->client_id = $school->id;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->mobile = $request->mobile;
                $user->address = $request->address;
                $user->password = Hash::make($password);
                $user->password_check = $password;
                $user->priv = 2;
                $user->active = $request->status ?? 0;
                $user->added_by = $authUser->id;
                $user->start_date = $request->start_date;
                $user->end_date = $request->end_date;
                $user->save();

                $school->user_id = $user->id;
                $school->save();

                $user->parent_user_id = $user->id;
                $user->save();
            } else {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->mobile = $request->mobile;
                $user->address = $request->address;
                $user->active = $request->status ?? 0;
                $user->start_date = $request->start_date;
                $user->end_date = $request->end_date;
                $user->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'school' => $school,
                'user' => $user,
                'url' => '/super-admin/schools',
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function initDashboard(Request $request) {

        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);

        if($user && $user->priv == 2){

            $parent_user_id = $user->parent_user_id;

            $students["active_students"] = User::clientUsersCount($parent_user_id,4, 0)->count();
            $students["inactive_students"] = User::clientUsersCount($parent_user_id,4, 1)->count();
            $students["total_students"] = User::clientUsersCount($parent_user_id, 4)->count();
            
            $teachers["active_teachers"] = User::clientUsersCount($parent_user_id, 3, 0)->count();
            $teachers["inactive_teachers"] = User::clientUsersCount($parent_user_id, 3, 1)->count();
            $teachers["total_teachers"] = User::clientUsersCount($parent_user_id, 3)->count();

            $data["students"] = $students;
            $data["teachers"] = $teachers;
            $data["success"] = true;
        } else {
            $data = ['success' => false, 'message' => 'Unauthorized user.'];
        }

        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }


    public function usersList(Request $request){

        $type = $request->segment(count($request->segments()));

        return view('super-admin.users', ['type'=> $type]);
    }

    public function initUsers(Request $request){
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        
        if (!$request->type || !$user || $user->priv != 1) {
            return response()->json([
                "success" => false,
                "message" => "Invalid Request"
            ], 400);
        }

        $type_row = DB::table("privileges")->where("plural_name", $request->type)->first();

        $sql = User::select(User::selectUsersColumns())->where("users.priv", $type_row->priv)->get();

        $data["dataSet"] = $sql;
        $data["success"] = true;

        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    // *** Doubt ***

    public function schoolServicesPage($id) {
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

        $serviceIdColumn = ModelHelper::resolveColumn('services', ['id', 'service_id', 'sid']);
        $serviceNameColumn = ModelHelper::resolveColumn('services', ['name', 'service_name', 'title']);
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

        $clientColumn = ModelHelper::resolveColumn('client_services', ['client_id', 'school_id', 'user_id', 'users_id']);
        $serviceColumn = ModelHelper::resolveColumn('client_services', ['service_id', 'services_id']);
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

        return view('super-admin.school_services', [
            'school' => $school,
            'services' => $services,
            'selected' => $selected,
        ]);
    }

    public function saveSchoolServices(Request $request, $id){
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

        $validated = $request->validate([
            'services' => ['nullable', 'array'],
            'services.*' => ['array'],
            'services.*.enabled' => ['nullable', 'accepted'],
        ]);
        $servicesInput = $validated['services'] ?? [];

        $clientColumn = ModelHelper::resolveColumn('client_services', ['client_id', 'school_id', 'user_id', 'users_id']);
        $serviceColumn = ModelHelper::resolveColumn('client_services', ['service_id', 'services_id']);
        if (!$clientColumn || !$serviceColumn) {
            return back()->with('failure', 'client_services table structure is not supported.');
        }
        $serviceIdColumn = ModelHelper::resolveColumn('services', ['id', 'service_id', 'sid']);
        if (!$serviceIdColumn) {
            return back()->with('failure', 'Services table structure is not supported.');
        }

        $permissionColumn = ModelHelper::resolveColumn('client_services', ['permissions', 'permission', 'rights', 'access']);

        $enabledServiceIds = [];
        foreach ($servicesInput as $serviceId => $payload) {
            if (!ctype_digit((string) $serviceId) || !is_array($payload) || empty($payload['enabled'])) {
                continue;
            }
            $enabledServiceIds[] = (int) $serviceId;
        }
        $enabledServiceIds = array_values(array_unique(array_filter($enabledServiceIds, fn (int $value) => $value > 0)));

        if (!empty($enabledServiceIds)) {
            $existingServiceCount = DB::table('services')
                ->whereIn($serviceIdColumn, $enabledServiceIds)
                ->count();
            if ($existingServiceCount !== count($enabledServiceIds)) {
                return back()->withErrors(['services' => 'One or more selected services are invalid.'])->withInput();
            }
        }

        ClientService::query()->where($clientColumn, $id)->delete();

        foreach ($enabledServiceIds as $serviceId) {
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

    public function usersByType($type) {
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

    public function userProfile($id) {
        if (!Schema::hasTable('users')) {
            return back()->with('failure', 'Users table not found.');
        }

        $query = User::query()->where('id', $id);
        $privColumn = $this->resolvePrivilegeColumnOnUsers();
        if ($privColumn) {
            $query->where($privColumn, '!=', 1);
        }

        $user = $query->first();
        if (!$user) {
            return back()->with('failure', 'User not found.');
        }

        $roleValue = (int) ($privColumn ? ($user->{$privColumn} ?? 0) : 0);
        $roleLabel = match ($roleValue) {
            2 => 'School',
            3 => 'Teacher',
            4 => 'Student',
            5 => 'Parent',
            default => 'User',
        };

        return view('admin.users.profile', compact('user', 'roleLabel'));
    }

    public function createSchool(Request $request) {
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

    public function updateSchool(Request $request, $id) {
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

    public function updateUserStatus(Request $request, $id) {
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

}
