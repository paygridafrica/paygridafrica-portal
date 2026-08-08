<x-layouts.app title="Research Center">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Research Center</h1>
        <p class="text-pg-muted text-sm mt-1">Competitor intelligence, market research, and industry notes.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">{{ session('success') }}</div>
    @endif

    {{-- COMPETITOR / MARKET INTELLIGENCE --}}
    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5 mb-6">
        <h2 class="font-semibold text-pg-text mb-4">Competitor & Market Intelligence</h2>

        <form action="/research/competitors" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
            @csrf
            <input type="text" name="name" placeholder="Competitor name" required class="px-3 py-2 border border-pg-border rounded-lg text-sm">
            <select name="category" class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                @foreach (['Ticketing', 'Stadium Tech', 'Payments', 'Sports Business', 'Other'] as $cat)
                    <option>{{ $cat }}</option>
                @endforeach
            </select>
            <input type="text" name="region" placeholder="Region (optional)" class="px-3 py-2 border border-pg-border rounded-lg text-sm">
            <input type="text" name="website" placeholder="Website (optional)" class="px-3 py-2 border border-pg-border rounded-lg text-sm md:col-span-2">
            <select name="threat_level" class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                @foreach (['Low', 'Medium', 'High'] as $level)
                    <option>{{ $level }}</option>
                @endforeach
            </select>
            <textarea name="strengths" placeholder="Strengths" rows="2" class="px-3 py-2 border border-pg-border rounded-lg text-sm"></textarea>
            <textarea name="weaknesses" placeholder="Weaknesses" rows="2" class="px-3 py-2 border border-pg-border rounded-lg text-sm"></textarea>
            <textarea name="notes" placeholder="Other notes" rows="2" class="px-3 py-2 border border-pg-border rounded-lg text-sm"></textarea>
            <button type="submit" class="md:col-span-3 px-4 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Add Competitor</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($competitors as $c)
                <div class="border border-pg-border rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-medium text-pg-text">{{ $c->name }}</p>
                            <p class="text-xs text-pg-muted">{{ $c->category }}{{ $c->region ? ' — ' . $c->region : '' }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-md font-medium
                            {{ $c->threat_level === 'High' ? 'bg-red-50 text-red-600' : ($c->threat_level === 'Medium' ? 'bg-pg-orange-light text-pg-orange' : 'bg-pg-green-light text-pg-green') }}">
                            {{ $c->threat_level }}
                        </span>
                    </div>
                    @if ($c->strengths)
                        <p class="text-xs text-pg-muted mt-2"><span class="font-medium text-pg-text">Strengths:</span> {{ $c->strengths }}</p>
                    @endif
                    @if ($c->weaknesses)
                        <p class="text-xs text-pg-muted mt-1"><span class="font-medium text-pg-text">Weaknesses:</span> {{ $c->weaknesses }}</p>
                    @endif
                    <form action="/research/competitors/{{ $c->id }}" method="POST" class="mt-2" onsubmit="return confirm('Remove this competitor?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:underline">Remove</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-pg-muted">No competitors tracked yet.</p>
            @endforelse
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- RESEARCH NOTES --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h2 class="font-semibold text-pg-text mb-4">Market & Industry Notes</h2>

            <form action="/research/notes" method="POST" class="space-y-2 mb-6">
                @csrf
                <input type="text" name="title" placeholder="Note title" required class="w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                <div class="flex gap-2">
                    <select name="category" class="flex-1 px-3 py-2 border border-pg-border rounded-lg text-sm">
                        @foreach ($categories as $cat)
                            <option>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="source_url" placeholder="Source link (optional)" class="flex-1 px-3 py-2 border border-pg-border rounded-lg text-sm">
                </div>
                <textarea name="content" placeholder="Notes..." rows="3" class="w-full px-3 py-2 border border-pg-border rounded-lg text-sm"></textarea>
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Save Note</button>
            </form>

            {{-- CATEGORY FILTER --}}
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="/research" class="px-3 py-1.5 text-xs rounded-full border {{ !request('category') ? 'bg-pg-blue text-white border-pg-blue' : 'border-pg-border text-pg-muted' }}">All</a>
                @foreach ($categories as $cat)
                    <a href="/research?category={{ $cat }}" class="px-3 py-1.5 text-xs rounded-full border {{ request('category') === $cat ? 'bg-pg-blue text-white border-pg-blue' : 'border-pg-border text-pg-muted' }}">{{ $cat }}</a>
                @endforeach
            </div>

            <div class="space-y-3">
                @forelse ($notes as $note)
                    <div class="border-b border-pg-border pb-3 last:border-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-pg-text">{{ $note->title }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-md bg-pg-blue-light text-pg-blue">{{ $note->category }}</span>
                            </div>
                            <form action="/research/notes/{{ $note->id }}" method="POST" onsubmit="return confirm('Remove this note?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:underline">✕</button>
                            </form>
                        </div>
                        @if ($note->content)
                            <p class="text-sm text-pg-muted mt-2">{{ $note->content }}</p>
                        @endif
                        @if ($note->source_url)
                            <a href="{{ $note->source_url }}" target="_blank" class="text-xs text-pg-blue hover:underline mt-1 inline-block">{{ $note->source_url }}</a>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-pg-muted">No notes yet.</p>
                @endforelse
            </div>
        </div>

        {{-- AI RESEARCH ASSISTANT PANEL --}}
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h2 class="font-semibold text-pg-text mb-2">AI Research Assistant</h2>
            <p class="text-xs text-pg-muted mb-4">Coming soon — ask questions about competitors, market trends, or industry data directly here.</p>
            <div class="bg-pg-bg rounded-lg p-4 text-center">
                <p class="text-xs text-pg-muted">This panel is reserved for a future AI-powered research assistant. We'll wire it up once the core modules are complete.</p>
            </div>
        </div>
    </div>

</x-layouts.app>
