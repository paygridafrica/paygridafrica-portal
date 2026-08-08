<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::orderBy('created_at', 'desc');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $documents = $query->get();

        $categories = ['Proposal', 'Business Plan', 'Pitch Deck', 'Prototype Book', 'Presentations', 'Company Policies', 'Letters', 'Meeting Minutes'];

        return view('documents.index', compact('documents', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'file' => 'required|file|max:20480', // 20MB max
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'local'); // saved in storage/app/private/documents

        Document::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->user()->name,
        ]);

        return redirect('/documents')->with('success', 'Document uploaded.');
    }

    public function download(string $id)
    {
        $document = Document::findOrFail($id);

        return Storage::disk('local')->download($document->file_path, $document->original_filename);
    }

    public function destroy(string $id)
    {
        $document = Document::findOrFail($id);
        Storage::disk('local')->delete($document->file_path);
        $document->delete();

        return redirect('/documents')->with('success', 'Document deleted.');
    }
}
