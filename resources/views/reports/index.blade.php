<x-layouts.app title="Reports">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-pg-text">Reports</h1>
            <p class="text-pg-muted text-sm mt-1">A full snapshot of PayGrid Africa, pulled from every module.</p>
        </div>
        <div class="flex gap-2 print:hidden">
            <a href="/reports?period=week" class="px-3 py-1.5 text-xs rounded-full border {{ $period === 'week' ? 'bg-pg-blue text-white border-pg-blue' : 'border-pg-border text-pg-muted' }}">This Week</a>
            <a href="/reports?period=month" class="px-3 py-1.5 text-xs rounded-full border {{ $period === 'month' ? 'bg-pg-blue text-white border-pg-blue' : 'border-pg-border text-pg-muted' }}">This Month</a>
            <a href="/reports?period=quarter" class="px-3 py-1.5 text-xs rounded-full border {{ $period === 'quarter' ? 'bg-pg-blue text-white border-pg-blue' : 'border-pg-border text-pg-muted' }}">This Quarter</a>
            <a href="/reports?period=year" class="px-3 py-1.5 text-xs rounded-full border {{ $period === 'year' ? 'bg-pg-blue text-white border-pg-blue' : 'border-pg-border text-pg-muted' }}">This Year</a>
            <button onclick="window.print()" class="px-3 py-1.5 text-xs rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">Print / Save PDF</button>
        </div>
    </div>

    {{-- HEADER SUMMARY --}}
    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-6 mb-6">
        <div class="flex items-center gap-3 mb-2">
            <img src="/images/logo.png" alt="PayGrid Africa" class="h-8">
        </div>
        <p class="text-sm text-pg-muted">Status Report — Generated {{ now()->format('d M Y') }}</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
                <p class="text-xs text-pg-muted">Company Status</p>
                <p class="font-medium text-pg-text">{{ $companyProfile->company_status ?: 'Not set' }}</p>
            </div>
            <div>
                <p class="text-xs text-pg-muted">Strategic Phase</p>
                <p class="font-medium text-pg-text">{{ $companyProfile->strategic_phase ?: 'Not set' }}</p>
            </div>
            <div>
                <p class="text-xs text-pg-muted">Weekly Progress</p>
                <p class="font-medium text-pg-text">{{ $companyProfile->weekly_progress_percent ?? '—' }}%</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

        {{-- RELATIONSHIPS --}}
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h2 class="font-semibold text-pg-text mb-3">Relationships</h2>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><p class="text-pg-muted text-xs">New Meetings</p><p class="font-semibold text-pg-text">{{ $newMeetings }}</p></div>
                <div><p class="text-pg-muted text-xs">New Investors</p><p class="font-semibold text-pg-text">{{ $newInvestors }}</p></div>
                <div><p class="text-pg-muted text-xs">Active Partners</p><p class="font-semibold text-pg-text">{{ $activePartners }}</p></div>
                <div><p class="text-pg-muted text-xs">Total in Pipeline</p><p class="font-semibold text-pg-text">{{ $pipelineTotal }}</p></div>
            </div>
        </div>

        {{-- FINANCE --}}
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h2 class="font-semibold text-pg-text mb-3">Finance</h2>
            <div class="grid grid-cols-1 gap-2 text-sm">
                <div class="flex justify-between"><span class="text-pg-muted">Cash Balance</span><span class="font-semibold text-pg-text">TZS {{ number_format($currentBalance, 0) }}</span></div>
                <div class="flex justify-between"><span class="text-pg-muted">Income (period)</span><span class="font-semibold text-pg-green">TZS {{ number_format($periodIncome, 0) }}</span></div>
                <div class="flex justify-between"><span class="text-pg-muted">Expenses (period)</span><span class="font-semibold text-pg-orange">TZS {{ number_format($periodExpenses, 0) }}</span></div>
            </div>
        </div>
    </div>

    {{-- PRODUCTS --}}
    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5 mb-4">
        <h2 class="font-semibold text-pg-text mb-3">Product Development</h2>
        <div class="space-y-3">
            @foreach ($products as $product)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-pg-text font-medium">{{ $product['name'] }}</span>
                        <span class="text-pg-muted">{{ $product['stage'] }}</span>
                    </div>
                    <div class="w-full bg-pg-bg rounded-full h-2">
                        <div class="bg-pg-green h-2 rounded-full" style="width: {{ $product['progress'] }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- MILESTONES --}}
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h2 class="font-semibold text-pg-text mb-3">Milestones This Period</h2>
            @forelse ($recentMilestones as $m)
                <div class="text-sm border-b border-pg-border pb-2 mb-2 last:border-0">
                    <p class="text-pg-text">{{ $m->title }}</p>
                    <p class="text-xs text-pg-muted">{{ $m->milestone_date?->format('d M Y') }} — {{ $m->status }}</p>
                </div>
            @empty
                <p class="text-sm text-pg-muted">No milestones in this period.</p>
            @endforelse
        </div>

        {{-- OPEN RISKS --}}
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h2 class="font-semibold text-pg-text mb-3">Open Risks</h2>
            @forelse ($openRisks as $risk)
                <div class="text-sm border-b border-pg-border pb-2 mb-2 last:border-0">
                    <p class="text-pg-text">{{ $risk->title }}</p>
                    <p class="text-xs text-pg-muted">{{ $risk->category }} — {{ $risk->likelihood }}/{{ $risk->impact }} — {{ $risk->status }}</p>
                </div>
            @empty
                <p class="text-sm text-pg-muted">No open risks logged.</p>
            @endforelse
        </div>
    </div>

</x-layouts.app>
