<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\LegalDocument;
use App\Models\ComplianceItem;
use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function index()
    {
        $companyProfile = CompanyProfile::firstOrCreate([]);
        $documents = LegalDocument::orderBy('created_at', 'desc')->get();
        $complianceItems = ComplianceItem::orderBy('created_at')->get();

        return view('legal.index', compact('companyProfile', 'documents', 'complianceItems'));
    }

    public function storeDocument(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'party_name' => 'nullable|string|max:255',
            'status' => 'required|string',
            'signed_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        LegalDocument::create($validated);

        return redirect('/legal')->with('success', 'Document logged.');
    }

    public function destroyDocument(string $id)
    {
        LegalDocument::findOrFail($id)->delete();
        return redirect('/legal')->with('success', 'Document removed.');
    }

    public function storeCompliance(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['is_complete'] = false;

        ComplianceItem::create($validated);

        return redirect('/legal')->with('success', 'Compliance item added.');
    }

    public function toggleCompliance(string $id)
    {
        $item = ComplianceItem::findOrFail($id);
        $item->update(['is_complete' => ! $item->is_complete]);

        return redirect('/legal')->with('success', 'Checklist updated.');
    }

    public function destroyCompliance(string $id)
    {
        ComplianceItem::findOrFail($id)->delete();
        return redirect('/legal')->with('success', 'Compliance item removed.');
    }
}
