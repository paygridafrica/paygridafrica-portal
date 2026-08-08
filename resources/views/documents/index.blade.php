<x-layouts.app title="Document Center">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-pg-text">Document Center</h1>
            <p class="text-pg-muted text-sm mt-1">Every company document, searchable and organized.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">{{ session('success') }}</div>
    @endif
    @error('file')
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-600 text-sm">{{ $message }}</div>
    @enderror

    {{-- UPLOAD FORM --}}
    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5 mb-6">
        <h2 class="font-semibold text-pg-text mb-4">Upload Document</h2>
        <form action="/documents" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
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

    {{-- SEARCH + FILTERS --}}
    <form action="/documents" method="GET" class="flex flex-wrap gap-2 mb-4 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." class="px-3 py-1.5 border border-pg-border rounded-lg text-sm w-64">
        <button type="submit" class="px-3 py-1.5 text-sm rounded-lg border border-pg-border text-pg-muted hover:bg-pg-bg">Search</button>
        <a href="/documents" class="px-3 py-1.5 text-xs rounded-full border {{ !request('category') ? 'bg-pg-blue text-white border-pg-blue' : 'border-pg-border text-pg-muted' }}">All</a>
        @foreach ($categories as $cat)
            <a href="/documents?category={{ $cat }}" class="px-3 py-1.5 text-xs rounded-full border {{ request('category') === $cat ? 'bg-pg-blue text-white border-pg-blue' : 'border-pg-border text-pg-muted' }}">{{ $cat }}</a>
        @endforeach
    </form>

    {{-- DOCUMENT LIST --}}
    <div class="bg-white rounded-xl border border-pg-border shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-pg-bg text-pg-muted text-left">
                <tr>
                    <th class="px-6 py-3 font-medium">Title</th>
                    <th class="px-6 py-3 font-medium">Category</th>
                    <th class="px-6 py-3 font-medium">File</th>
                    <th class="px-6 py-3 font-medium">Size</th>
                    <th class="px-6 py-3 font-medium">Uploaded By</th>
                    <th class="px-6 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-pg-border">
                @forelse ($documents as $doc)
                    <tr class="hover:bg-pg-bg/50">
                        <td class="px-6 py-4 font-medium text-pg-text">{{ $doc->title }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-md bg-pg-blue-light text-pg-blue text-xs font-medium">{{ $doc->category }}</span>
                        </td>
                        <td class="px-6 py-4 text-pg-muted text-xs">{{ $doc->original_filename }}</td>
                        <td class="px-6 py-4 text-pg-muted text-xs">{{ number_format($doc->file_size / 1024, 0) }} KB</td>
                        <td class="px-6 py-4 text-pg-muted text-xs">{{ $doc->uploaded_by }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="/documents/{{ $doc->id }}/download" class="text-pg-blue hover:underline text-xs">Download</a>
                            <form action="/documents/{{ $doc->id }}" method="POST" class="inline" onsubmit="return confirm('Delete this document?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-pg-muted">No documents uploaded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
