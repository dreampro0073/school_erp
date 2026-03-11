<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\ModelHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class IncomeController extends Controller {
    public function incomesPage(){
        return view('admin.finance.incomes');
    }

    public function initIncomes(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function incomeEntriesPage() {
        return view('admin.finance.income_entries');
    }

    public function initIncomeEntrie(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function storeIncome(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function expensesPage() {
        return view('admin.finance.expenses');
    }

    public function initExpenses(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

    public function expenseEntriesPage() {
        return view('admin.finance.expense_entries');
    }

    public function initExpense(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }


    public function storeExpense(Request $request) {
        $apiToken = $request->header('apiToken');
        $user = User::authUser($apiToken);
        $data["success"] = true;

        return response()->json($data,200,[]);
    }

}
