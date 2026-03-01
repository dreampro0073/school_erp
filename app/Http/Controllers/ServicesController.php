<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\ModelHelper;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ServicesController extends Controller {
    public function index()
    {
        return view('admin.services.index');
    }

    public function initServices(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!Schema::hasTable('services')) {
            return response()->json(['success' => true, 'services' => []]);
        }

        $idColumn = ModelHelper::resolveColumn('services', ['id', 'service_id', 'sid']);
        $nameColumn = ModelHelper::resolveColumn('services', ['name', 'service_name', 'title']);
        if (!$idColumn || !$nameColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported services table structure.'], 422);
        }

        $select = [
            "{$idColumn} as id",
            "{$nameColumn} as name",
        ];
        if (Schema::hasColumn('services', 'active')) {
            $select[] = 'active';
        }
        if (Schema::hasColumn('services', 'created_at')) {
            $select[] = 'created_at';
        }

        $query = Service::query()->select($select);
        $query->orderBy($idColumn, 'desc');

        return response()->json([
            'success' => true,
            'services' => $query->get(),
        ]);
    }

    public function storeService(Request $request)
    {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!Schema::hasTable('services')) {
            return response()->json(['success' => false, 'message' => 'services table not found.'], 422);
        }

        $idColumn = ModelHelper::resolveColumn('services', ['id', 'service_id', 'sid']);
        $nameColumn = ModelHelper::resolveColumn('services', ['name', 'service_name', 'title']);
        if (!$idColumn || !$nameColumn) {
            return response()->json(['success' => false, 'message' => 'Unsupported services table structure.'], 422);
        }

        $payload = $request->validate([
            'id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'active' => ['nullable', 'in:0,1'],
        ]);

        $updateOrInsert = [
            $nameColumn => $payload['name'],
        ];
        if (Schema::hasColumn('services', 'active')) {
            $updateOrInsert['active'] = isset($payload['active']) ? (int) $payload['active'] : 1;
        }
        if (Schema::hasColumn('services', 'updated_at')) {
            $updateOrInsert['updated_at'] = now();
        }

        $serviceId = isset($payload['id']) ? (int) $payload['id'] : 0;

        if ($serviceId > 0) {
            Service::query()->where($idColumn, $serviceId)->update($updateOrInsert);
            return response()->json(['success' => true, 'message' => 'Service updated successfully.']);
        }

        if (Schema::hasColumn('services', 'created_at')) {
            $updateOrInsert['created_at'] = now();
        }

        Service::query()->insert($updateOrInsert);
        return response()->json(['success' => true, 'message' => 'Service created successfully.']);
    }
}
