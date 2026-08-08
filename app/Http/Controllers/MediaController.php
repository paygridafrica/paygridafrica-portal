<?php

namespace App\Http\Controllers;

use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaAsset::orderBy('created_at', 'desc');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $assets = $query->get();

        $categories = ['Logos', 'Images', 'Videos', 'Screenshots', 'Marketing Materials', 'Brand Assets'];

        return view('media.index', compact('assets', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'file' => 'required|file|max:51200', // 50MB max, for video
        ]);

        $file = $request->file('file');
        // Media files go on the 'public' disk, so images can be displayed directly (unlike private Documents).
        $path = $file->store('media', 'public');

        MediaAsset::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => auth()->user()->name,
        ]);

        return redirect('/media')->with('success', 'Media uploaded.');
    }

    public function destroy(string $id)
    {
        $asset = MediaAsset::findOrFail($id);
        Storage::disk('public')->delete($asset->file_path);
        $asset->delete();

        return redirect('/media')->with('success', 'Media deleted.');
    }
}
