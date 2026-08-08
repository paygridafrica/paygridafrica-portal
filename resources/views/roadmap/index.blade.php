<x-layouts.app title="Project Roadmap">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Project Roadmap</h1>
        <p class="text-pg-muted text-sm mt-1">Quarterly objectives, product stages, and milestones — the big picture.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">{{ session('success') }}</div>
    @endif

    {{-- CURRENT PRODUCT STAGES --}}
    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5 mb-6">
        <h2 class="font-semibold text-pg-text mb-4">Product Stages</h2>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            @php
                $stageOrder = ['Concept', 'Design', 'Development', 'Testing', 'Launched'];
            @endphp
            @foreach ($products as $product)
                @php $stageIndex = array_search($product->development_stage, $stageOrder); @endphp
                <a href="/products/{{ $product->id }}" class="border border-pg-border rounded-lg p-3 hover:shadow-sm transition">
                    <p class="text-sm font-medium text-pg-text truncate">{{ $product->name }}</p>
                    <div class="flex gap-1 mt-2">
                        @foreach ($stageOrder as $i => $stage)
                            <div class="h-1.5 flex-1 rounded-full {{ $i <= $stageIndex ? 'bg-pg-green' : 'bg-pg-bg' }}"></div>
                        @endforeach
                    </div>
                    <p class="text-xs text-pg-muted mt-1">{{ $product->development_stage }}</p>
                </a>
            @endforeach
        </div>
    </div>

    {{-- QUARTERLY OBJECTIVES --}}
    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5 mb-6">
        <h2 class="font-semibold text-pg-text mb-4">Quarterly Objectives</h2>

        <form action="/roadmap/objectives" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6 items-end">
            @csrf
            <div class="md:col-span-2">
                <label class="text-xs font-medium text-pg-text">Objective</label>
                <input type="text" name="title" required class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-pg-text">Quarter</label>
                <input type="text" name="quarter" placeholder="e.g. Q3 2026" required class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-pg-text">Status</label>
                <select name="status" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach (['Planned', 'In Progress', 'Achieved'] as $s)
                        <option>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4">
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Add Objective</button>
            </div>
        </form>

        <div class="space-y-6">
            @forelse ($objectives as $quarter => $items)
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-pg-blue mb-2">{{ $quarter }}</p>
                    <div class="space-y-2">
                        @foreach ($items as $objective)
                            <div class="flex items-center justify-between border-b border-pg-border pb-2 last:border-0">
                                <div>
                                    <p class="text-sm text-pg-text">{{ $objective->title }}</p>
                                    @if ($objective->description)
                                        <p class="text-xs text-pg-muted">{{ $objective->description }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs px-2 py-0.5 rounded-md font-medium
                                        {{ $objective->status === 'Achieved' ? 'bg-pg-green-light text-pg-green' : ($objective->status === 'In Progress' ? 'bg-pg-orange-light text-pg-orange' : 'bg-pg-blue-light text-pg-blue') }}">
                                        {{ $objective->status }}
                                    </span>
                                    <form action="/roadmap/objectives/{{ $objective->id }}" method="POST" onsubmit="return confirm('Remove this objective?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-400 hover:underline">✕</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-sm text-pg-muted">No objectives set yet.</p>
            @endforelse
        </div>
    </div>

    {{-- MILESTONE TIMELINE --}}
    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
        <h2 class="font-semibold text-pg-text mb-4">Milestone Timeline</h2>
        @if ($milestones->isEmpty())
            <p class="text-sm text-pg-muted">No milestones yet — add them from Company Management.</p>
        @else
            <div class="relative pl-6 border-l-2 border-pg-border space-y-6">
                @foreach ($milestones as $milestone)
                    <div class="relative">
                        <span class="absolute -left-[29px] top-0.5 w-3 h-3 rounded-full border-2 border-white
                            {{ $milestone->status === 'Achieved' ? 'bg-pg-green' : ($milestone->status === 'In Progress' ? 'bg-pg-orange' : 'bg-pg-blue') }}"></span>
                        <p class="text-xs text-pg-muted">{{ $milestone->milestone_date?->format('d M Y') }}</p>
                        <p class="text-sm font-medium text-pg-text">{{ $milestone->title }}</p>
                        @if ($milestone->description)
                            <p class="text-xs text-pg-muted mt-0.5">{{ $milestone->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</x-layouts.app>
