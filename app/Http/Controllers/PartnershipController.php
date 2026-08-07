<?php

namespace App\Http\Controllers;

use App\Models\Partnership;
use Illuminate\Http\Request;

class PartnershipController extends Controller
{
    protected array $stages = [
        'Prospect', 'Contacted', 'Meeting Scheduled', 'Negotiation',
        'Pilot Discussion', 'Agreement', 'Active Partner',
    ];

    public function index()
    {
        $partnerships = Partnership::all();
        $stages = $this->stages;

        return view('partnerships.index', compact('partnerships', 'stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['stage'] = 'Prospect';

        Partnership::create($validated);

        return redirect('/partnerships')->with('success', 'Partnership added.');
    }

    public function updateStage(Request $request, string $id)
    {
        $validated = $request->validate([
            'stage' => 'required|string|in:' . implode(',', $this->stages),
        ]);

        $partnership = Partnership::findOrFail($id);
        $partnership->update(['stage' => $validated['stage']]);

        return response()->json(['success' => true]);
    }

    public function destroy(string $id)
    {
        Partnership::findOrFail($id)->delete();
        return redirect('/partnerships')->with('success', 'Partnership removed.');
    }
}
