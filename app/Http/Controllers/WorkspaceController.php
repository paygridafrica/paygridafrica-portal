<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Goal;
use App\Models\DecisionLogEntry;
use App\Models\Risk;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function index()
    {
        $notes = Note::orderBy('entry_date', 'desc')->take(20)->get();
        $goals = Goal::orderBy('created_at', 'desc')->get();
        $decisions = DecisionLogEntry::orderBy('decision_date', 'desc')->get();
        $risks = Risk::orderBy('created_at', 'desc')->get();
        $companyProfile = CompanyProfile::firstOrCreate([]);

        return view('workspace.index', compact('notes', 'goals', 'decisions', 'risks', 'companyProfile'));
    }

    public function storeNote(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'content' => 'required|string',
            'entry_date' => 'required|date',
        ]);

        Note::create($validated);

        return redirect('/workspace')->with('success', 'Note saved.');
    }

    public function destroyNote(string $id)
    {
        Note::findOrFail($id)->delete();
        return redirect('/workspace')->with('success', 'Note removed.');
    }

    public function storeGoal(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'timeframe' => 'required|string',
            'period_label' => 'required|string|max:100',
        ]);

        $validated['is_complete'] = false;
        Goal::create($validated);

        return redirect('/workspace')->with('success', 'Goal added.');
    }

    public function toggleGoal(string $id)
    {
        $goal = Goal::findOrFail($id);
        $goal->update(['is_complete' => ! $goal->is_complete]);

        return redirect('/workspace')->with('success', 'Goal updated.');
    }

    public function destroyGoal(string $id)
    {
        Goal::findOrFail($id)->delete();
        return redirect('/workspace')->with('success', 'Goal removed.');
    }

    public function storeDecision(Request $request)
    {
        $validated = $request->validate([
            'decision' => 'required|string',
            'reasoning' => 'nullable|string',
            'decision_date' => 'required|date',
        ]);

        DecisionLogEntry::create($validated);

        return redirect('/workspace')->with('success', 'Decision logged.');
    }

    public function destroyDecision(string $id)
    {
        DecisionLogEntry::findOrFail($id)->delete();
        return redirect('/workspace')->with('success', 'Decision removed.');
    }

    public function storeRisk(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'likelihood' => 'required|string',
            'impact' => 'required|string',
            'mitigation_plan' => 'nullable|string',
            'status' => 'required|string',
        ]);

        Risk::create($validated);

        return redirect('/workspace')->with('success', 'Risk logged.');
    }

    public function destroyRisk(string $id)
    {
        Risk::findOrFail($id)->delete();
        return redirect('/workspace')->with('success', 'Risk removed.');
    }

    public function updateSwot(Request $request)
    {
        $validated = $request->validate([
            'swot_strengths' => 'nullable|string',
            'swot_weaknesses' => 'nullable|string',
            'swot_opportunities' => 'nullable|string',
            'swot_threats' => 'nullable|string',
        ]);

        $profile = CompanyProfile::firstOrCreate([]);
        $profile->update($validated);

        return redirect('/workspace')->with('success', 'SWOT analysis updated.');
    }
}
