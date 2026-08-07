<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        $query = Meeting::query()->orderBy('meeting_date', 'desc');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $meetings = $query->get();

        $categories = ['Football', 'Music', 'Government', 'Telecom', 'Banks', 'Investors', 'Event Organizers'];

        return view('meetings.index', compact('meetings', 'categories'));
    }

    public function create()
    {
        $categories = ['Football', 'Music', 'Government', 'Telecom', 'Banks', 'Investors', 'Event Organizers'];
        return view('meetings.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'category' => 'required|string',
            'meeting_date' => 'required|date',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'status' => 'required|string',
            'probability' => 'required|string',
            'relationship_strength' => 'required|string',
        ]);

        Meeting::create($validated);

        return redirect('/meetings')->with('success', 'Meeting saved.');
    }

    public function edit(string $id)
    {
        $meeting = Meeting::findOrFail($id);
        $categories = ['Football', 'Music', 'Government', 'Telecom', 'Banks', 'Investors', 'Event Organizers'];
        return view('meetings.edit', compact('meeting', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $meeting = Meeting::findOrFail($id);

        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'category' => 'required|string',
            'meeting_date' => 'required|date',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'status' => 'required|string',
            'probability' => 'required|string',
            'relationship_strength' => 'required|string',
        ]);

        $meeting->update($validated);

        return redirect('/meetings')->with('success', 'Meeting updated.');
    }

    public function destroy(string $id)
    {
        Meeting::findOrFail($id)->delete();
        return redirect('/meetings')->with('success', 'Meeting deleted.');
    }
}
