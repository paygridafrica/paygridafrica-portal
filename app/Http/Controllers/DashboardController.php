<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Investor;
use App\Models\Partnership;
use App\Models\CompanyProfile;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $companyProfile = CompanyProfile::firstOrCreate([]);

        $kpis = [
            ['label' => 'Meetings', 'value' => Meeting::count(), 'change' => $this->weeklyChange(Meeting::class)],
            ['label' => 'Investors', 'value' => Investor::count(), 'change' => $this->weeklyChange(Investor::class)],
            ['label' => 'Partners', 'value' => Partnership::where('stage', 'Active Partner')->count(), 'change' => 'Active partners'],
            ['label' => 'Pilot Events', 'value' => Partnership::where('stage', 'Pilot Discussion')->count(), 'change' => 'In discussion'],
            ['label' => 'Tasks Completed', 'value' => 0, 'change' => 'Module not built yet'],
            ['label' => 'Documents', 'value' => 0, 'change' => 'Module not built yet'],
        ];

        // Monthly progress = meetings logged per month, last 6 months
        $monthlyProgress = $this->meetingsPerMonth();

        // Partnership pipeline = live counts per stage, in the correct order
        $stages = ['Prospect', 'Contacted', 'Meeting Scheduled', 'Negotiation', 'Pilot Discussion', 'Agreement', 'Active Partner'];
        $pipeline = [
            'labels' => $stages,
            'data' => collect($stages)->map(fn ($stage) => Partnership::where('stage', $stage)->count())->toArray(),
        ];

        // Upcoming tasks = meetings with a future follow-up date
        $tasks = Meeting::where('follow_up_date', '>=', now())
            ->orderBy('follow_up_date')
            ->take(5)
            ->get()
            ->map(fn ($m) => [
                'title' => 'Follow up with ' . $m->contact_name . ($m->organization ? ' (' . $m->organization . ')' : ''),
                'due' => $m->follow_up_date->format('d M Y'),
            ])
            ->toArray();

        // Latest activity = most recent meetings, investors, partnerships combined
        $activity = $this->recentActivity();

        return view('dashboard.index', compact('kpis', 'monthlyProgress', 'pipeline', 'tasks', 'activity', 'companyProfile'));
    }

    private function weeklyChange(string $model): string
    {
        $count = $model::where('created_at', '>=', now()->subDays(7))->count();
        return $count > 0 ? "+{$count} this week" : 'No change this week';
    }

    private function meetingsPerMonth(): array
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M');
            $data[] = Meeting::where('meeting_date', '>=', $month->copy()->startOfMonth())
                ->where('meeting_date', '<=', $month->copy()->endOfMonth())
                ->count();
        }

        return compact('labels', 'data');
    }

    private function recentActivity(): array
    {
        $items = collect();

        foreach (Meeting::orderBy('created_at', 'desc')->take(3)->get() as $m) {
            $items->push(['text' => 'Meeting logged with ' . $m->contact_name, 'time' => $m->created_at]);
        }

        foreach (Investor::orderBy('created_at', 'desc')->take(3)->get() as $inv) {
            $items->push(['text' => 'Investor added: ' . $inv->name, 'time' => $inv->created_at]);
        }

        foreach (Partnership::orderBy('updated_at', 'desc')->take(3)->get() as $p) {
            $items->push(['text' => $p->organization . ' moved to ' . $p->stage, 'time' => $p->updated_at]);
        }

        return $items->sortByDesc('time')
            ->take(6)
            ->map(fn ($item) => ['text' => $item['text'], 'time' => $item['time']->diffForHumans()])
            ->values()
            ->toArray();
    }
}
