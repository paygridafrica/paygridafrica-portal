<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\FundingRequest;
use App\Models\FinanceSettings;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index()
    {
        $settings = FinanceSettings::firstOrCreate([], [
            'starting_cash_balance' => 0,
            'annual_budget' => 0,
            'funding_goal' => 0,
        ]);

        $transactions = Transaction::orderBy('transaction_date', 'desc')->get();
        $fundingRequests = FundingRequest::orderBy('request_date', 'desc')->get();

        $totalIncome = $transactions->where('type', 'Income')->sum('amount');
        $totalExpenses = $transactions->where('type', 'Expense')->sum('amount');
        $currentBalance = $settings->starting_cash_balance + $totalIncome - $totalExpenses;

        // Average monthly burn = average net outflow over the last 3 months with activity
        $threeMonthsAgo = now()->subMonths(3);
        $recentExpenses = $transactions->where('type', 'Expense')
            ->where('transaction_date', '>=', $threeMonthsAgo)
            ->sum('amount');
        $recentIncome = $transactions->where('type', 'Income')
            ->where('transaction_date', '>=', $threeMonthsAgo)
            ->sum('amount');
        $monthlyBurn = max(0, ($recentExpenses - $recentIncome) / 3);

        $runwayMonths = $monthlyBurn > 0 ? round($currentBalance / $monthlyBurn, 1) : null;

        // Cash flow per month, last 6 months
        $cashFlow = ['labels' => [], 'income' => [], 'expenses' => []];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $cashFlow['labels'][] = $month->format('M');
            $cashFlow['income'][] = $transactions->where('type', 'Income')
                ->filter(fn ($t) => $t->transaction_date->isSameMonth($month))
                ->sum('amount');
            $cashFlow['expenses'][] = $transactions->where('type', 'Expense')
                ->filter(fn ($t) => $t->transaction_date->isSameMonth($month))
                ->sum('amount');
        }

        // Expense breakdown by category (for a quick budget picture)
        $expensesByCategory = $transactions->where('type', 'Expense')
            ->groupBy('category')
            ->map(fn ($group) => $group->sum('amount'));

        return view('finance.index', compact(
            'settings', 'transactions', 'fundingRequests',
            'totalIncome', 'totalExpenses', 'currentBalance',
            'monthlyBurn', 'runwayMonths', 'cashFlow', 'expensesByCategory'
        ));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'starting_cash_balance' => 'required|numeric|min:0',
            'annual_budget' => 'nullable|numeric|min:0',
            'funding_goal' => 'nullable|numeric|min:0',
        ]);

        $settings = FinanceSettings::firstOrCreate([]);
        $settings->update($validated);

        return redirect('/finance')->with('success', 'Finance settings updated.');
    }

    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:Income,Expense',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        Transaction::create($validated);

        return redirect('/finance')->with('success', 'Transaction recorded.');
    }

    public function destroyTransaction(string $id)
    {
        Transaction::findOrFail($id)->delete();
        return redirect('/finance')->with('success', 'Transaction removed.');
    }

    public function storeFundingRequest(Request $request)
    {
        $validated = $request->validate([
            'investor_name' => 'required|string|max:255',
            'amount_requested' => 'required|numeric|min:0',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'request_date' => 'required|date',
        ]);

        FundingRequest::create($validated);

        return redirect('/finance')->with('success', 'Funding request added.');
    }

    public function destroyFundingRequest(string $id)
    {
        FundingRequest::findOrFail($id)->delete();
        return redirect('/finance')->with('success', 'Funding request removed.');
    }
}
