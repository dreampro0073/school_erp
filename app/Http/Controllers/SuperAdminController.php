<?php

namespace App\Http\Controllers;
use App\Models\Client;
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

    public function addSchool(){
        return view('super-admin.schools.add_school', [
            'isEdit' => true,
        ]);
    }

    public function submitUsers(Request $request){

        $apiToken = $request->header('apiToken');
        $authUser = User::authUser($apiToken);

        if (!$authUser || (int) $authUser->priv !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized user.',
            ], 200, []);
        }

        $schoolId = $request->id ?: ($request->sch_id ?: null);

        $user = null;
        $client = null;

        if ($schoolId) {
            $user = User::find($schoolId);

            if (!$user && Schema::hasTable('clients')) {
                $client = Client::find($schoolId);
                if ($client && Schema::hasColumn('users', 'client_id')) {
                    $user = User::where('client_id', $client->id)->where('priv', 2)->first();
                }
            }

            if (!$client && $user && Schema::hasTable('clients') && Schema::hasColumn('users', 'client_id')) {
                $client = Client::find($user->client_id);
            }

            if (!$user && !$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not found!',
                ], 200, []);
            }
        }

        $userIdForUnique = $user ? $user->id : null;

        $validator = Validator::make($request->all(), [
            'client_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userIdForUnique)],
            'mobile' => ['nullable', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 200, []);
        }

        DB::beginTransaction();
        try {
            if (Schema::hasTable('clients')) {
                if (!$client) {
                    $client = new Client;
                }

                $clientPayload = [];

                if (Schema::hasColumn('clients', 'client_name')) {
                    $clientPayload['client_name'] = $request->client_name;
                }
                if (Schema::hasColumn('clients', 'name')) {
                    $clientPayload['name'] = $request->client_name;
                }
                if (Schema::hasColumn('clients', 'owner_name')) {
                    $clientPayload['owner_name'] = $request->name;
                }
                if (Schema::hasColumn('clients', 'email')) {
                    $clientPayload['email'] = $request->email;
                }
                if (Schema::hasColumn('clients', 'mobile')) {
                    $clientPayload['mobile'] = $request->mobile;
                }
                if (Schema::hasColumn('clients', 'address')) {
                    $clientPayload['address'] = $request->previous_school_address;
                }
                if (Schema::hasColumn('clients', 'gst_number')) {
                    $clientPayload['gst_number'] = $request->gst_number;
                }
                if (Schema::hasColumn('clients', 'start_date')) {
                    $clientPayload['start_date'] = $request->subscription_start_date;
                }
                if (Schema::hasColumn('clients', 'end_date')) {
                    $clientPayload['end_date'] = $request->subscription_end_date;
                }
                if (Schema::hasColumn('clients', 'subscription_start_date')) {
                    $clientPayload['subscription_start_date'] = $request->subscription_start_date;
                }
                if (Schema::hasColumn('clients', 'subscription_end_date')) {
                    $clientPayload['subscription_end_date'] = $request->subscription_end_date;
                }

                if (Schema::hasColumn('clients', 'created_at') && !$client->exists) {
                    $clientPayload['created_at'] = now();
                }
                if (Schema::hasColumn('clients', 'updated_at')) {
                    $clientPayload['updated_at'] = now();
                }

                $client->fill($clientPayload);
                $client->save();
            }

            $isNewUser = false;
            if (!$user) {
                $user = new User;
                $isNewUser = true;

                $password = User::getRandPassword();
                if (Schema::hasColumn('users', 'password')) {
                    $user->password = Hash::make($password);
                }
                if (Schema::hasColumn('users', 'password_check')) {
                    $user->password_check = $password;
                }
                if (Schema::hasColumn('users', 'check_password')) {
                    $user->check_password = $password;
                }
                if (Schema::hasColumn('users', 'active')) {
                    $user->active = 0;
                }
                if (Schema::hasColumn('users', 'priv')) {
                    $user->priv = 2;
                }
                if (Schema::hasColumn('users', 'added_by')) {
                    $user->added_by = $authUser->id;
                }
            }

            $displayName = $request->client_name ?: $request->name;

            if (Schema::hasColumn('users', 'name')) {
                $user->name = $displayName;
            }
            if (Schema::hasColumn('users', 'owner_name')) {
                $user->owner_name = $request->name;
            }
            if (Schema::hasColumn('users', 'school_name')) {
                $user->school_name = $request->client_name;
            }
            if (Schema::hasColumn('users', 'email')) {
                $user->email = $request->email;
            }
            if (Schema::hasColumn('users', 'mobile')) {
                $user->mobile = $request->mobile;
            }
            if (Schema::hasColumn('users', 'address')) {
                $user->address = $request->previous_school_address;
            }
            if (Schema::hasColumn('users', 'gst_number')) {
                $user->gst_number = $request->gst_number;
            }
            if (Schema::hasColumn('users', 'start_date')) {
                $user->start_date = $request->subscription_start_date;
            }
            if (Schema::hasColumn('users', 'end_date')) {
                $user->end_date = $request->subscription_end_date;
            }
            if (Schema::hasColumn('users', 'org_id')) {
                $user->org_id = $authUser->org_id;
            }
            if (Schema::hasColumn('users', 'client_id') && $client) {
                $user->client_id = $client->id;
            }

            $user->save();

            if ($isNewUser && Schema::hasColumn('users', 'parent_user_id')) {
                if (!$user->parent_user_id) {
                    $user->parent_user_id = $user->id;
                    $user->save();
                }
            }

            if ($client) {
                $clientLinkPayload = [];
                if (Schema::hasColumn('clients', 'user_id')) {
                    $clientLinkPayload['user_id'] = $user->id;
                }
                if (Schema::hasColumn('clients', 'owner_id')) {
                    $clientLinkPayload['owner_id'] = $user->id;
                }
                if (!empty($clientLinkPayload)) {
                    $client->fill($clientLinkPayload);
                    $client->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $schoolId ? 'Successfully Updated' : 'Successfully Stored',
            ], 200, []);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Unable to save school.',
            ], 200, []);
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

    public function createSchoolPage() {
        $services = $this->getSelectableServices();
        $standards = $this->getSelectableStandards();

        return view('super-admin.create_school', [
            'isEdit' => false,
            'school' => null,
            'services' => $services,
            'standards' => $standards,
            'selectedServiceIds' => $this->extractSelectedIdsFromInput(old('services', [])),
            'selectedStandardIds' => $this->extractSelectedIdsFromInput(old('standards', [])),
        ]);
    }

    public function editSchoolPage($id) {
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

        return view('super-admin.create_school', [
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
