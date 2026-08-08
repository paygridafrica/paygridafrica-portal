<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Investor;
use App\Models\ProductTask;

class CalendarController extends Controller
{
    public function index()
    {
        $events = collect();

        foreach (Meeting::whereNotNull('meeting_date')->get() as $m) {
            $events->push([
                'title' => 'Meeting: ' . $m->contact_name,
                'date' => $m->meeting_date->format('Y-m-d'),
                'type' => 'Meeting',
                'link' => "/meetings",
            ]);
        }

        foreach (Meeting::whereNotNull('follow_up_date')->get() as $m) {
            $events->push([
                'title' => 'Follow up: ' . $m->contact_name,
                'date' => $m->follow_up_date->format('Y-m-d'),
                'type' => 'Follow-up',
                'link' => "/meetings",
            ]);
        }

        foreach (Investor::whereNotNull('follow_up_date')->get() as $inv) {
            $events->push([
                'title' => 'Investor follow-up: ' . $inv->name,
                'date' => $inv->follow_up_date->format('Y-m-d'),
                'type' => 'Investor',
                'link' => "/investors",
            ]);
        }

        foreach (ProductTask::whereNotNull('due_date')->where('status', '!=', 'Done')->get() as $task) {
            $events->push([
                'title' => 'Task due: ' . $task->title,
                'date' => $task->due_date->format('Y-m-d'),
                'type' => 'Task',
                'link' => "/products/" . $task->product_id,
            ]);
        }

        // Group all events by date, e.g. "2026-08-15" => [event, event]
        $eventsByDate = $events->groupBy('date');

        // Upcoming = today onward, sorted, next 10
        $upcoming = $events
            ->filter(fn ($e) => $e['date'] >= now()->format('Y-m-d'))
            ->sortBy('date')
            ->take(10)
            ->values();

        return view('calendar.index', compact('eventsByDate', 'upcoming'));
    }
}
