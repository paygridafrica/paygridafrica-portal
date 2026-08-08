<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use App\Models\ResearchNote;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    public function index(Request $request)
    {
        $competitors = Competitor::orderBy('threat_level', 'desc')->get();

        $notesQuery = ResearchNote::orderBy('created_at', 'desc');
        if ($request->filled('category')) {
            $notesQuery->where('category', $request->category);
        }
        $notes = $notesQuery->get();

        $categories = ['Market Research', 'Ticketing Industry', 'Stadium Technology', 'Payment Industry', 'Sports Business', 'Innovation', 'Article'];

        return view('research.index', compact('competitors', 'notes', 'categories'));
    }

    public function storeCompetitor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'region' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'notes' => 'nullable|string',
            'threat_level' => 'required|string',
        ]);

        Competitor::create($validated);

        return redirect('/research')->with('success', 'Competitor added.');
    }

    public function destroyCompetitor(string $id)
    {
        Competitor::findOrFail($id)->delete();
        return redirect('/research')->with('success', 'Competitor removed.');
    }

    public function storeNote(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'nullable|string',
            'source_url' => 'nullable|string|max:500',
        ]);

        ResearchNote::create($validated);

        return redirect('/research')->with('success', 'Note saved.');
    }

    public function destroyNote(string $id)
    {
        ResearchNote::findOrFail($id)->delete();
        return redirect('/research')->with('success', 'Note removed.');
    }
}
