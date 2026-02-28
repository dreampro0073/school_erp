<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Worklog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorklogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $selectedUserId = $request->query('user_id');

        $query = Worklog::query()->with('user:id,name');
        $usersQuery = User::query();

        if (!empty($user->client_id)) {
            $query->where('client_id', (int) $user->client_id);
            $usersQuery->where('client_id', (int) $user->client_id);
        }

        if (!empty($selectedUserId)) {
            $query->where('user_id', (int) $selectedUserId);
        }

        if (!empty($fromDate)) {
            $query->whereDate('date', '>=', $fromDate);
        }

        if (!empty($toDate)) {
            $query->whereDate('date', '<=', $toDate);
        }

        $worklogs = $query->orderByDesc('date')->orderByDesc('id')->paginate(20)->withQueryString();
        $users = $usersQuery->select(['id', 'name'])->orderBy('name')->get();

        return view('worklog.index', compact('worklogs', 'fromDate', 'toDate', 'users', 'selectedUserId'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'date' => ['required', 'date'],
            'remark' => ['nullable', 'string'],
        ]);

        Worklog::query()->create([
            'user_id' => (int) $user->id,
            'client_id' => isset($user->client_id) ? (int) $user->client_id : null,
            'date' => $data['date'],
            'remark' => $data['remark'] ?? null,
        ]);

        return redirect()->route('worklog.index')->with('success', 'Worklog entry created successfully.');
    }
}
