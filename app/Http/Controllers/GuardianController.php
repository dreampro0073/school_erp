<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GuardianController extends Controller
{
    public function dashboard()
    {
        return view('gurdian.dashboard');
    }

    public function initDashboard(Request $request)
    {
        $user = User::resolveApiUser($request, 5);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $userId = (int) ($user->id ?? 0);
        $clientId = (int) ($user->client_id ?? 0);
        $parent = $this->resolveParentProfile($userId, $clientId, (string) ($user->email ?? ''));
        $children = $this->resolveChildren($parent['id'], $clientId, $userId);

        return response()->json([
            'success' => true,
            'today' => now()->format('d M Y'),
            'guardian' => [
                'name' => (string) ($parent['name'] ?: ($user->name ?? 'Guardian')),
                'email' => (string) ($parent['email'] ?: ($user->email ?? '-')),
                'mobile' => (string) ($parent['mobile'] ?: ($user->mobile ?? '-')),
            ],
            'children' => $children,
        ]);
    }

    private function resolveParentProfile(int $userId, int $clientId, string $email): array
    {
        $result = ['id' => null, 'name' => '', 'email' => '', 'mobile' => ''];

        if (!Schema::hasTable('parents')) {
            return $result;
        }

        $query = DB::table('parents');
        if ($clientId > 0 && Schema::hasColumn('parents', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        if ($userId > 0 && Schema::hasColumn('parents', 'user_id')) {
            $query->where('user_id', $userId);
        } elseif ($email !== '' && Schema::hasColumn('parents', 'email')) {
            $query->where('email', $email);
        } else {
            return $result;
        }

        $select = ['id'];
        foreach (['name', 'parent_name', 'full_name'] as $col) {
            if (Schema::hasColumn('parents', $col)) {
                $select[] = $col;
                break;
            }
        }
        foreach (['mobile', 'phone'] as $col) {
            if (Schema::hasColumn('parents', $col)) {
                $select[] = $col;
                break;
            }
        }
        if (Schema::hasColumn('parents', 'email')) {
            $select[] = 'email';
        }

        $row = $query->first($select);
        if (!$row) {
            return $result;
        }

        $result['id'] = (int) ($row->id ?? 0);
        $result['name'] = (string) ($row->name ?? $row->parent_name ?? $row->full_name ?? '');
        $result['email'] = (string) ($row->email ?? '');
        $result['mobile'] = (string) ($row->mobile ?? $row->phone ?? '');

        return $result;
    }

    private function resolveChildren(?int $parentId, int $clientId, int $userId): array
    {
        if (!Schema::hasTable('students')) {
            return [];
        }

        $query = DB::table('students');
        if ($clientId > 0 && Schema::hasColumn('students', 'client_id')) {
            $query->where('client_id', $clientId);
        }

        $appliedParentScope = false;
        if ($parentId && Schema::hasColumn('students', 'parent_id')) {
            $query->where('parent_id', $parentId);
            $appliedParentScope = true;
        }

        if (!$appliedParentScope && Schema::hasTable('parent_access') && $parentId && Schema::hasColumn('parent_access', 'student_id') && Schema::hasColumn('parent_access', 'parent_id')) {
            $query->whereIn('id', function ($sub) use ($parentId, $clientId) {
                $sub->from('parent_access')->select('student_id')->where('parent_id', $parentId);
                if ($clientId > 0 && Schema::hasColumn('parent_access', 'client_id')) {
                    $sub->where('client_id', $clientId);
                }
                if (Schema::hasColumn('parent_access', 'active')) {
                    $sub->where('active', 1);
                }
            });
            $appliedParentScope = true;
        }

        if (!$appliedParentScope && $userId > 0 && Schema::hasColumn('students', 'user_id')) {
            $query->where('user_id', $userId);
            $appliedParentScope = true;
        }

        if (!$appliedParentScope) {
            return [];
        }

        $select = ['id'];
        foreach (['name', 'first_name'] as $col) {
            if (Schema::hasColumn('students', $col)) {
                $select[] = $col;
                break;
            }
        }
        if (Schema::hasColumn('students', 'last_name')) {
            $select[] = 'last_name';
        }
        foreach (['erp_id', 'admission_no'] as $col) {
            if (Schema::hasColumn('students', $col)) {
                $select[] = $col;
                break;
            }
        }
        if (Schema::hasColumn('students', 'active')) {
            $select[] = 'active';
        }

        return $query->orderByDesc('id')->limit(25)->get($select)->map(function ($row) {
            $name = trim((string) ($row->first_name ?? '') . ' ' . (string) ($row->last_name ?? ''));
            if ($name === '') {
                $name = (string) ($row->name ?? 'Student');
            }

            return [
                'id' => (int) ($row->id ?? 0),
                'name' => $name,
                'admission_no' => (string) ($row->erp_id ?? $row->admission_no ?? '-'),
                'active' => isset($row->active) ? (int) $row->active : 1,
            ];
        })->all();
    }
}
