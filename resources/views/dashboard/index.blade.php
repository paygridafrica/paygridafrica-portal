<x-layouts.app title="Executive Dashboard">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Welcome back, David</h1>
        <p class="text-pg-muted text-sm mt-1">Here's where PayGrid Africa stands today.</p>
    </div>

    {{-- STATUS STRIP --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <p class="text-xs text-pg-muted font-medium">COMPANY STATUS</p>
            <p class="text-lg font-semibold text-pg-green mt-1">Active — Building</p>
        </div>
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <p class="text-xs text-pg-muted font-medium">STRATEGIC PHASE</p>
            <p class="text-lg font-semibold text-pg-blue mt-1">Phase 2: Pilot Preparation</p>
        </div>
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <p class="text-xs text-pg-muted font-medium">WEEKLY PROGRESS</p>
            <p class="text-lg font-semibold text-pg-orange mt-1">72% of weekly goals</p>
        </div>
    </div>

    {{-- KPI GRID --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        @foreach ($kpis as $kpi)
            <div class="bg-white rounded-xl border border-pg-border shadow-sm p-4">
                <p class="text-xs text-pg-muted font-medium">{{ strtoupper($kpi['label']) }}</p>
                <p class="text-2xl font-bold text-pg-text mt-1">{{ $kpi['value'] }}</p>
                <p class="text-xs text-pg-green mt-1">{{ $kpi['change'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <p class="font-semibold text-pg-text mb-4">Monthly Progress</p>
            <canvas id="monthlyChart" height="220"></canvas>
        </div>
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <p class="font-semibold text-pg-text mb-4">Partnership Pipeline</p>
            <canvas id="pipelineChart" height="220"></canvas>
        </div>
    </div>

    {{-- FUNDING + PRODUCT PROGRESS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <p class="font-semibold text-pg-text mb-2">Funding Goal Progress</p>
            <div class="w-full bg-pg-bg rounded-full h-3">
                <div class="bg-pg-orange h-3 rounded-full" style="width: 45%"></div>
            </div>
            <p class="text-xs text-pg-muted mt-2">45% of target raised</p>
        </div>
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <p class="font-semibold text-pg-text mb-2">Product Development Progress</p>
            <div class="w-full bg-pg-bg rounded-full h-3">
                <div class="bg-pg-green h-3 rounded-full" style="width: 60%"></div>
            </div>
            <p class="text-xs text-pg-muted mt-2">60% across all products</p>
        </div>
    </div>

    {{-- TASKS + ACTIVITY --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <p class="font-semibold text-pg-text mb-3">Upcoming Tasks</p>
            <ul class="space-y-2">
                @foreach ($tasks as $task)
                    <li class="flex justify-between text-sm border-b border-pg-border pb-2 last:border-0">
                        <span class="text-pg-text">{{ $task['title'] }}</span>
                        <span class="text-pg-muted">{{ $task['due'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <p class="font-semibold text-pg-text mb-3">Latest Activity</p>
            <ul class="space-y-3">
                @foreach ($activity as $item)
                    <li class="flex gap-3 text-sm">
                        <span class="w-2 h-2 rounded-full bg-pg-blue mt-1.5 shrink-0"></span>
                        <div>
                            <p class="text-pg-text">{{ $item['text'] }}</p>
                            <p class="text-pg-muted text-xs">{{ $item['time'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    @push('scripts')
    <script>
        window.dashboardData = {
            monthly: @json($monthlyProgress),
            pipeline: @json($pipeline),
        };
    </script>
    @endpush

</x-layouts.app>
