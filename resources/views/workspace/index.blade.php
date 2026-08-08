<x-layouts.app title="Founder Workspace">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Founder Workspace</h1>
        <p class="text-pg-muted text-sm mt-1">Notes, goals, decisions, risks, and SWOT — your personal command center.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">{{ session('success') }}</div>
    @endif

    <div x-data="{ tab: 'notes' }">

        {{-- TABS --}}
        <div class="flex flex-wrap gap-2 mb-6 border-b border-pg-border pb-3">
            <button @click="tab = 'notes'" :class="tab === 'notes' ? 'bg-pg-blue text-white' : 'text-pg-muted hover:bg-pg-bg'" class="px-4 py-1.5 text-sm rounded-lg font-medium">Notes & Journal</button>
            <button @click="tab = 'goals'" :class="tab === 'goals' ? 'bg-pg-blue text-white' : 'text-pg-muted hover:bg-pg-bg'" class="px-4 py-1.5 text-sm rounded-lg font-medium">Goals</button>
            <button @click="tab = 'decisions'" :class="tab === 'decisions' ? 'bg-pg-blue text-white' : 'text-pg-muted hover:bg-pg-bg'" class="px-4 py-1.5 text-sm rounded-lg font-medium">Decision Log</button>
            <button @click="tab = 'risks'" :class="tab === 'risks' ? 'bg-pg-blue text-white' : 'text-pg-muted hover:bg-pg-bg'" class="px-4 py-1.5 text-sm rounded-lg font-medium">Risk Register</button>
            <button @click="tab = 'swot'" :class="tab === 'swot' ? 'bg-pg-blue text-white' : 'text-pg-muted hover:bg-pg-bg'" class="px-4 py-1.5 text-sm rounded-lg font-medium">SWOT Analysis</button>
        </div>

        {{-- NOTES & JOURNAL --}}
        <div x-show="tab === 'notes'">
            <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5 mb-4">
                <form action="/workspace/notes" method="POST" class="space-y-3">
                    @csrf
                    <div class="flex gap-3">
                        <select name="type" class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                            @foreach (['Personal Note', 'Daily Planner', 'Vision Board', 'Journal', 'Motivation'] as $type)
                                <option>{{ $type }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="entry_date" value="{{ now()->format('Y-m-d') }}" required class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                    </div>
                    <textarea name="content" placeholder="Write your note..." rows="3" required class="w-full px-3 py-2 border border-pg-border rounded-lg text-sm"></textarea>
                    <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Save Entry</button>
                </form>
            </div>
            <div class="space-y-3">
                @forelse ($notes as $note)
                    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs px-2 py-0.5 rounded-md bg-pg-blue-light text-pg-blue font-medium">{{ $note->type }}</span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-pg-muted">{{ $note->entry_date->format('d M Y') }}</span>
                                <form action="/workspace/notes/{{ $note->id }}" method="POST" onsubmit="return confirm('Delete this entry?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:underline">✕</button>
                                </form>
                            </div>
                        </div>
                        <p class="text-sm text-pg-text whitespace-pre-line">{{ $note->content }}</p>
                    </div>
                @empty
                    <p class="text-sm text-pg-muted">No entries yet.</p>
                @endforelse
            </div>
        </div>

        {{-- GOALS --}}
        <div x-show="tab === 'goals'" x-cloak>
            <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5 mb-4">
                <form action="/workspace/goals" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    @csrf
                    <input type="text" name="title" placeholder="Goal" required class="md:col-span-2 px-3 py-2 border border-pg-border rounded-lg text-sm">
                    <select name="timeframe" class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                        <option>Weekly</option>
                        <option>Monthly</option>
                    </select>
                    <input type="text" name="period_label" placeholder="e.g. Week of Aug 4" required class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                    <button type="submit" class="md:col-span-4 px-4 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Add Goal</button>
                </form>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @forelse ($goals as $goal)
                    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-4 flex items-start gap-3">
                        <form action="/workspace/goals/{{ $goal->id }}/toggle" method="POST" class="mt-0.5">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-4 h-4 rounded border {{ $goal->is_complete ? 'bg-pg-green border-pg-green' : 'border-pg-border' }} flex items-center justify-center">
                                @if ($goal->is_complete)<span class="text-white text-[10px]">✓</span>@endif
                            </button>
                        </form>
                        <div class="flex-1">
                            <p class="text-sm {{ $goal->is_complete ? 'text-pg-muted line-through' : 'text-pg-text' }}">{{ $goal->title }}</p>
                            <span class="text-xs text-pg-muted">{{ $goal->timeframe }} — {{ $goal->period_label }}</span>
                        </div>
                        <form action="/workspace/goals/{{ $goal->id }}" method="POST" onsubmit="return confirm('Remove this goal?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:underline">✕</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-pg-muted">No goals yet.</p>
                @endforelse
            </div>
        </div>

        {{-- DECISION LOG --}}
        <div x-show="tab === 'decisions'" x-cloak>
            <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5 mb-4">
                <form action="/workspace/decisions" method="POST" class="space-y-3">
                    @csrf
                    <div class="flex gap-3">
                        <input type="text" name="decision" placeholder="What did you decide?" required class="flex-1 px-3 py-2 border border-pg-border rounded-lg text-sm">
                        <input type="date" name="decision_date" value="{{ now()->format('Y-m-d') }}" required class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                    </div>
                    <textarea name="reasoning" placeholder="Why? (reasoning, context)" rows="2" class="w-full px-3 py-2 border border-pg-border rounded-lg text-sm"></textarea>
                    <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Log Decision</button>
                </form>
            </div>
            <div class="space-y-3">
                @forelse ($decisions as $decision)
                    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-pg-text">{{ $decision->decision }}</p>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-pg-muted">{{ $decision->decision_date->format('d M Y') }}</span>
                                <form action="/workspace/decisions/{{ $decision->id }}" method="POST" onsubmit="return confirm('Remove this entry?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:underline">✕</button>
                                </form>
                            </div>
                        </div>
                        @if ($decision->reasoning)
                            <p class="text-xs text-pg-muted mt-2">{{ $decision->reasoning }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-pg-muted">No decisions logged yet.</p>
                @endforelse
            </div>
        </div>

        {{-- RISK REGISTER --}}
        <div x-show="tab === 'risks'" x-cloak>
            <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5 mb-4">
                <form action="/workspace/risks" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @csrf
                    <input type="text" name="title" placeholder="Risk description" required class="md:col-span-3 px-3 py-2 border border-pg-border rounded-lg text-sm">
                    <select name="category" class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                        @foreach (['Financial', 'Legal', 'Product', 'Market', 'Team', 'Other'] as $cat)
                            <option>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <select name="likelihood" class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                        @foreach (['Low', 'Medium', 'High'] as $l)
                            <option>{{ $l }}</option>
                        @endforeach
                    </select>
                    <select name="impact" class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                        @foreach (['Low', 'Medium', 'High'] as $i)
                            <option>{{ $i }}</option>
                        @endforeach
                    </select>
                    <textarea name="mitigation_plan" placeholder="Mitigation plan (optional)" rows="2" class="md:col-span-2 px-3 py-2 border border-pg-border rounded-lg text-sm"></textarea>
                    <select name="status" class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                        @foreach (['Open', 'Monitoring', 'Resolved'] as $s)
                            <option>{{ $s }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="md:col-span-3 px-4 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Log Risk</button>
                </form>
            </div>
            <div class="space-y-3">
                @forelse ($risks as $risk)
                    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-pg-text">{{ $risk->title }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-md bg-pg-blue-light text-pg-blue">{{ $risk->category }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded-md font-medium
                                    {{ $risk->impact === 'High' && $risk->likelihood === 'High' ? 'bg-red-50 text-red-600' : 'bg-pg-orange-light text-pg-orange' }}">
                                    {{ $risk->likelihood }} / {{ $risk->impact }}
                                </span>
                                <form action="/workspace/risks/{{ $risk->id }}" method="POST" onsubmit="return confirm('Remove this risk?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:underline">✕</button>
                                </form>
                            </div>
                        </div>
                        @if ($risk->mitigation_plan)
                            <p class="text-xs text-pg-muted mt-2"><span class="font-medium text-pg-text">Mitigation:</span> {{ $risk->mitigation_plan }}</p>
                        @endif
                        <span class="text-xs text-pg-muted mt-1 inline-block">Status: {{ $risk->status }}</span>
                    </div>
                @empty
                    <p class="text-sm text-pg-muted">No risks logged yet.</p>
                @endforelse
            </div>
        </div>

        {{-- SWOT ANALYSIS --}}
        <div x-show="tab === 'swot'" x-cloak>
            <form action="/workspace/swot" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                @method('PUT')
                <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
                    <label class="text-sm font-semibold text-pg-green">Strengths</label>
                    <textarea name="swot_strengths" rows="5" class="mt-2 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">{{ $companyProfile->swot_strengths }}</textarea>
                </div>
                <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
                    <label class="text-sm font-semibold text-pg-orange">Weaknesses</label>
                    <textarea name="swot_weaknesses" rows="5" class="mt-2 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">{{ $companyProfile->swot_weaknesses }}</textarea>
                </div>
                <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
                    <label class="text-sm font-semibold text-pg-blue">Opportunities</label>
                    <textarea name="swot_opportunities" rows="5" class="mt-2 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">{{ $companyProfile->swot_opportunities }}</textarea>
                </div>
                <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
                    <label class="text-sm font-semibold text-red-500">Threats</label>
                    <textarea name="swot_threats" rows="5" class="mt-2 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">{{ $companyProfile->swot_threats }}</textarea>
                </div>
                <button type="submit" class="md:col-span-2 px-5 py-2 text-sm rounded-lg bg-pg-blue text-white font-medium hover:opacity-90">Save SWOT Analysis</button>
            </form>
        </div>

    </div>
</x-layouts.app>s
