<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductTask;
use App\Models\FeatureRequest;
use App\Models\Bug;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        // First-time setup: if no products exist yet, create the 5 known ones automatically.
        if ($products->isEmpty()) {
            $defaults = ['Fan App', 'Vendor App', 'Agent App', 'Organizer Dashboard', 'Founder Portal'];
            foreach ($defaults as $name) {
                Product::create([
                    'name' => $name,
                    'development_stage' => 'Concept',
                    'priority' => 'Medium',
                    'version' => '0.1.0',
                    'screens_completed' => 0,
                    'screens_total' => 0,
                ]);
            }
            $products = Product::all();
        }

        return view('products.index', compact('products'));
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        $tasks = ProductTask::where('product_id', $id)->orderBy('due_date')->get();
        $features = FeatureRequest::where('product_id', $id)->orderBy('created_at', 'desc')->get();
        $bugs = Bug::where('product_id', $id)->orderBy('created_at', 'desc')->get();

        return view('products.show', compact('product', 'tasks', 'features', 'bugs'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'description' => 'nullable|string',
            'development_stage' => 'required|string',
            'priority' => 'required|string',
            'version' => 'nullable|string',
            'screens_completed' => 'nullable|integer|min:0',
            'screens_total' => 'nullable|integer|min:0',
        ]);

        $product->update($validated);

        return redirect("/products/{$id}")->with('success', 'Product updated.');
    }

    public function storeTask(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|string',
            'due_date' => 'nullable|date',
        ]);

        $validated['product_id'] = $id;
        ProductTask::create($validated);

        return redirect("/products/{$id}")->with('success', 'Task added.');
    }

    public function storeFeature(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $validated['product_id'] = $id;
        FeatureRequest::create($validated);

        return redirect("/products/{$id}")->with('success', 'Feature request added.');
    }

    public function storeBug(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'severity' => 'required|string',
            'status' => 'required|string',
        ]);

        $validated['product_id'] = $id;
        Bug::create($validated);

        return redirect("/products/{$id}")->with('success', 'Bug logged.');
    }
}
