<x-layouts.app title="Meetings CRM">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-pg-text">Meetings CRM</h1>
            <p class="text-pg-muted text-sm mt-1">Every conversation, tracked in one place.</p>
        </div>
        <a href="/meetings/create" class="px-4 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ New Meeting</a>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">{{ session('success') }}</div>
    @endif

    {{-- CATEGORY FILTERS --}}
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="/meetings" class="px-3 py-1.5 text-xs rounded-full border {{ !request('category') ? 'bg-pg-blue text-white border-pg-blue' : 'border-pg-border text-pg-muted' }}">All</a>
        @foreach ($categories as $cat)
            <a href="/meetings?category={{ $cat }}" class="px-3 py-1.5 text-xs rounded-full border {{ request('category') === $cat ? 'bg-pg-blue text-white border-pg-blue' : 'border-pg-border text-pg-muted' }}">{{ $cat }}</a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border border-pg-border shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-pg-bg text-pg-muted text-left">
                <tr>
                    <th class="px-6 py-3 font-medium">Contact</th>
                    <th class="px-6 py-3 font-medium">Organization</th>
                    <th class="px-6 py-3 font-medium">Category</th>
                    <th class="px-6 py-3 font-medium">Meeting Date</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Probability</th>
                    <th class="px-6 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-pg-border">
                @forelse ($meetings as $meeting)
                    <tr class="hover:bg-pg-bg/50">
                        <td class="px-6 py-4 font-medium text-pg-text">{{ $meeting->contact_name }}</td>
                        <td class="px-6 py-4 text-pg-muted">{{ $meeting->organization }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-md bg-pg-blue-light text-pg-blue text-xs font-medium">{{ $meeting->category }}</span>
                        </td>
                        <td class="px-6 py-4 text-pg-muted">{{ $meeting->meeting_date?->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-pg-muted">{{ $meeting->status }}</td>
                        <td class="px-6 py-4 text-pg-muted">{{ $meeting->probability }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="/meetings/{{ $meeting->id }}/edit" class="text-pg-blue hover:underline">Edit</a>
                            <form action="/meetings/{{ $meeting->id }}" method="POST" class="inline" onsubmit="return confirm('Delete this meeting?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-pg-muted">No meetings logged yet. Click "+ New Meeting" to add your first one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
