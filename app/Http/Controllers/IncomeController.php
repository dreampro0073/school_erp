<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ModelHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class IncomeController extends Controller
{
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
        return $this->initEntryTable($request, 'income_entries');
    }

    public function storeIncomeEntry(Request $request)
    {
        return $this->storeEntryTable($request, 'income_entries', ['income_id', 'incomes_id'], 'Income entry');
    }

    public function initExpenseEntries(Request $request)
    {
        return $this->initEntryTable($request, 'expense_entries');
    }

    public function storeExpenseEntry(Request $request)
    {
        return $this->storeEntryTable($request, 'expense_entries', ['expense_id', 'expenses_id'], 'Expense entry');
    }

    private function initMasterTable(Request $request, string $table, array $nameCandidates, string $responseKey)
    {
        $user = User::resolveApiUser($request, 2);
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
        $user = User::resolveApiUser($request, 2);
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

    private function initEntryTable(Request $request, string $table)
    {
        $user = User::resolveApiUser($request, 2);
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
        $user = User::resolveApiUser($request, 2);
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

        $payload = [$masterIdColumn => (int) $data['master_id']];
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
}
