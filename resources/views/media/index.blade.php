<x-layouts.app title="Media Library">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Media Library</h1>
        <p class="text-pg-muted text-sm mt-1">Logos, images, videos, and brand assets.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">{{ session('success') }}</div>
    @endif
    @error('file')
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-600 text-sm">{{ $message }}</div>
    @enderror

    {{-- UPLOAD FORM --}}
    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5 mb-6">
        <h2 class="font-semibold text-pg-text mb-4">Upload Media</h2>
        <form action="/media" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
            @csrf
            <div class="md:col-span-2">
                <label class="text-xs font-medium text-pg-text">Title</label>
                <input type="text" name="title" required class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-pg-text">Category</label>
                <select name="category" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach ($categories as $cat)
                        <option>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-medium text-pg-text">File</label>
                <input type="file" name="file" required class="mt-1 w-full text-xs border border-pg-border rounded-lg px-2 py-1.5">
            </div>
            <div class="md:col-span-4">
                <button type="submit" class="px-5 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">Upload</button>
            </div>
        </form>
    </div>

    {{-- CATEGORY FILTERS --}}
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="/media" class="px-3 py-1.5 text-xs rounded-full border {{ !request('category') ? 'bg-pg-blue text-white border-pg-blue' : 'border-pg-border text-pg-muted' }}">All</a>
        @foreach ($categories as $cat)
            <a href="/media?category={{ $cat }}" class="px-3 py-1.5 text-xs rounded-full border {{ request('category') === $cat ? 'bg-pg-blue text-white border-pg-blue' : 'border-pg-border text-pg-muted' }}">{{ $cat }}</a>
        @endforeach
    </div>

    {{-- MEDIA GRID --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse ($assets as $asset)
            <div class="bg-white rounded-xl border border-pg-border shadow-sm overflow-hidden group">
                <div class="aspect-square bg-pg-bg flex items-center justify-center overflow-hidden">
                    @if (str_starts_with($asset->mime_type, 'image/'))
                        <img src="/storage/{{ $asset->file_path }}" alt="{{ $asset->title }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-3xl text-pg-muted">📄</span>
                    @endif
                </div>
                <div class="p-3">
                    <p class="text-xs font-medium text-pg-text truncate">{{ $asset->title }}</p>
                    <p class="text-[10px] text-pg-muted">{{ $asset->category }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <a href="/storage/{{ $asset->file_path }}" target="_blank" class="text-[10px] text-pg-blue hover:underline">View</a>
                        <form action="/media/{{ $asset->id }}" method="POST" onsubmit="return confirm('Delete this file?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[10px] text-red-400 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-full text-sm text-pg-muted">No media uploaded yet.</p>
        @endforelse
    </div>
</x-layouts.app>
