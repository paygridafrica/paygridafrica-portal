<?php

namespace App\Http\Controllers;

use App\Models\Objective;
use App\Models\Milestone;
use App\Models\Product;
use Illuminate\Http\Request;

class RoadmapController extends Controller
{
    public function index()
    {
        $objectives = Objective::orderBy('quarter')->get()->groupBy('quarter');
        $milestones = Milestone::orderBy('milestone_date')->get();
        $products = Product::all();

        return view('roadmap.index', compact('objectives', 'milestones', 'products'));
    }

    public function storeObjective(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'quarter' => 'required|string|max:20',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Objective::create($validated);

        return redirect('/roadmap')->with('success', 'Objective added.');
    }

    public function destroyObjective(string $id)
    {
        Objective::findOrFail($id)->delete();
        return redirect('/roadmap')->with('success', 'Objective removed.');
    }
}
