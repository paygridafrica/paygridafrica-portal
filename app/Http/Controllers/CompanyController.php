<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Milestone;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        // "firstOrCreate" — get the one company profile document,
        // or silently create an empty one if this is the very first visit.
        $profile = CompanyProfile::firstOrCreate([], [
            'core_values' => [],
        ]);

        $milestones = Milestone::orderBy('milestone_date', 'desc')->get();

        return view('company.index', compact('profile', 'milestones'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'core_values' => 'nullable|string', // submitted as one string, we'll split it
            'company_description' => 'nullable|string',
            'registration_number' => 'nullable|string',
            'registration_date' => 'nullable|date',
            'registered_country' => 'nullable|string',
            'trademark_status' => 'nullable|string',
            'trademark_number' => 'nullable|string',
            'brand_guidelines_notes' => 'nullable|string',
        ]);

        // Turn the textarea (one value per line) into a clean array
        $validated['core_values'] = collect(explode("\n", $request->input('core_values', '')))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->toArray();

        $profile = CompanyProfile::firstOrCreate([]);
        $profile->update($validated);

        return redirect('/company')->with('success', 'Company profile updated.');
    }

    public function storeMilestone(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'milestone_date' => 'required|date',
            'status' => 'required|string',
        ]);

        Milestone::create($validated);

        return redirect('/company')->with('success', 'Milestone added.');
    }

    public function destroyMilestone(string $id)
    {
        Milestone::findOrFail($id)->delete();
        return redirect('/company')->with('success', 'Milestone removed.');
    }
}
