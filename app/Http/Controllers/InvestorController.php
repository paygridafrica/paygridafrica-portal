<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    public function index()
    {
        $investors = Investor::orderBy('created_at', 'desc')->get();
        return view('investors.index', compact('investors'));
    }

    public function create()
    {
        return view('investors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'firm' => 'nullable|string|max:255',
            'funding_stage' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'pitch_deck_sent' => 'nullable|boolean',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'investment_probability' => 'required|string',
            'required_documents' => 'nullable|array',
        ]);

        $validated['pitch_deck_sent'] = $request->boolean('pitch_deck_sent');

        Investor::create($validated);

        return redirect('/investors')->with('success', 'Investor added.');
    }

    public function edit(string $id)
    {
        $investor = Investor::findOrFail($id);
        return view('investors.edit', compact('investor'));
    }

    public function update(Request $request, string $id)
    {
        $investor = Investor::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'firm' => 'nullable|string|max:255',
            'funding_stage' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'pitch_deck_sent' => 'nullable|boolean',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'investment_probability' => 'required|string',
            'required_documents' => 'nullable|array',
        ]);

        $validated['pitch_deck_sent'] = $request->boolean('pitch_deck_sent');

        $investor->update($validated);

        return redirect('/investors')->with('success', 'Investor updated.');
    }

    public function destroy(string $id)
    {
        Investor::findOrFail($id)->delete();
        return redirect('/investors')->with('success', 'Investor removed.');
    }
}
