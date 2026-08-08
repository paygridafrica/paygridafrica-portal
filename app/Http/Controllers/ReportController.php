<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Investor;
use App\Models\Partnership;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\FinanceSettings;
use App\Models\Milestone;
use App\Models\Risk;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default to "This Month" if no range given
        $period = $request->query('period', 'month');
        $start = match ($period) {
            'week' => now()->subWeek(),
            'quarter' => now()->subMonths(3),
            'year' => now()->subYear(),
            default => now()->startOfMonth(),
        };

        $companyProfile = CompanyProfile::firstOrCreate([]);

        // Relationships summary
        $newMeetings = Meeting::where('created_at', '>=', $start)->count();
        $newInvestors = Investor::where('created_at', '>=', $start)->count();
        $activePartners = Partnership::where('stage', 'Active Partner')->count();
        $pipelineTotal = Partnership::count();

        // Product summary
        $products = Product::all()->map(function ($p) {
            $progress = $p->screens_total > 0 ? round(($p->screens_completed / $p->screens_total) * 100) : 0;
            return [
                'name' => $p->name,
                'stage' => $p->development_stage,
                'progress' => $progress,
            ];
        });

        // Finance summary
        $settings = FinanceSettings::firstOrCreate([]);
        $totalIncome = Transaction::where('type', 'Income')->sum('amount');
        $totalExpenses = Transaction::where('type', 'Expense')->sum('amount');
        $currentBalance = $settings->starting_cash_balance + $totalIncome - $totalExpenses;
        $periodIncome = Transaction::where('type', 'Income')->where('transaction_date', '>=', $start)->sum('amount');
        $periodExpenses = Transaction::where('type', 'Expense')->where('transaction_date', '>=', $start)->sum('amount');

        // Milestones achieved in period
        $recentMilestones = Milestone::where('milestone_date', '>=', $start)->orderBy('milestone_date', 'desc')->get();

        // Open risks
        $openRisks = Risk::where('status', '!=', 'Resolved')->orderBy('created_at', 'desc')->get();

        return view('reports.index', compact(
            'period', 'companyProfile',
            'newMeetings', 'newInvestors', 'activePartners', 'pipelineTotal',
            'products', 'currentBalance', 'periodIncome', 'periodExpenses',
            'recentMilestones', 'openRisks'
        ));
    }
}
