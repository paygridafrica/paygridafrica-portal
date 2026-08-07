<x-layouts.app title="Investor CRM">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-pg-text">Investor CRM</h1>
            <p class="text-pg-muted text-sm mt-1">Track every investor conversation and next step.</p>
        </div>
        <a href="/investors/create" class="px-4 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ New Investor</a>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-pg-border shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-pg-bg text-pg-muted text-left">
                <tr>
                    <th class="px-6 py-3 font-medium">Name</th>
                    <th class="px-6 py-3 font-medium">Firm</th>
                    <th class="px-6 py-3 font-medium">Stage</th>
                    <th class="px-6 py-3 font-medium">Pitch Deck Sent</th>
                    <th class="px-6 py-3 font-medium">Probability</th>
                    <th class="px-6 py-3 font-medium">Follow-up</th>
                    <th class="px-6 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-pg-border">
                @forelse ($investors as $investor)
                    <tr class="hover:bg-pg-bg/50">
                        <td class="px-6 py-4 font-medium text-pg-text">{{ $investor->name }}</td>
                        <td class="px-6 py-4 text-pg-muted">{{ $investor->firm }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-md bg-pg-blue-light text-pg-blue text-xs font-medium">{{ $investor->funding_stage }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if ($investor->pitch_deck_sent)
                                <span class="text-pg-green text-xs font-medium">Yes</span>
                            @else
                                <span class="text-pg-muted text-xs">No</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-pg-muted">{{ $investor->investment_probability }}</td>
                        <td class="px-6 py-4 text-pg-muted">{{ $investor->follow_up_date?->format('d M Y') ?? '—' }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="/investors/{{ $investor->id }}/edit" class="text-pg-blue hover:underline">Edit</a>
                            <form action="/investors/{{ $investor->id }}" method="POST" class="inline" onsubmit="return confirm('Delete this investor?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-pg-muted">No investors added yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
