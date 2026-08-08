<x-layouts.app title="Finance">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Finance</h1>
        <p class="text-pg-muted text-sm mt-1">Budget, cash flow, and runway at a glance.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">{{ session('success') }}</div>
    @endif

    {{-- KPI STRIP --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-4">
            <p class="text-xs text-pg-muted font-medium">CASH BALANCE</p>
            <p class="text-xl font-bold text-pg-text mt-1">TZS {{ number_format($currentBalance, 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-4">
            <p class="text-xs text-pg-muted font-medium">TOTAL INCOME</p>
            <p class="text-xl font-bold text-pg-green mt-1">TZS {{ number_format($totalIncome, 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-4">
            <p class="text-xs text-pg-muted font-medium">TOTAL EXPENSES</p>
            <p class="text-xl font-bold text-pg-orange mt-1">TZS {{ number_format($totalExpenses, 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-4">
            <p class="text-xs text-pg-muted font-medium">RUNWAY</p>
            <p class="text-xl font-bold text-pg-blue mt-1">{{ $runwayMonths ?? '—' }} {{ $runwayMonths ? 'months' : '' }}</p>
        </div>
    </div>

    {{-- CASH FLOW CHART + FUNDING GOAL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <p class="font-semibold text-pg-text mb-4">Cash Flow — Last 6 Months</p>
            <canvas id="cashFlowChart" height="220"></canvas>
        </div>
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <p class="font-semibold text-pg-text mb-3">Funding Goal</p>
            @php
                $fundingProgress = $settings->funding_goal > 0 ? min(100, round(($totalIncome / $settings->funding_goal) * 100)) : 0;
            @endphp
            <div class="w-full bg-pg-bg rounded-full h-3 mb-2">
                <div class="bg-pg-orange h-3 rounded-full" style="width: {{ $fundingProgress }}%"></div>
            </div>
            <p class="text-xs text-pg-muted mb-4">{{ $fundingProgress }}% of TZS {{ number_format($settings->funding_goal, 0) }} goal</p>

            <form action="/finance/settings" method="POST" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-xs font-medium text-pg-text">Starting Cash Balance (TZS)</label>
                    <input type="number" step="0.01" name="starting_cash_balance" value="{{ $settings->starting_cash_balance }}" class="mt-1 w-full px-3 py-1.5 border border-pg-border rounded-lg text-sm">
                </div>
                <div>
                    <label class="text-xs font-medium text-pg-text">Annual Budget (TZS)</label>
                    <input type="number" step="0.01" name="annual_budget" value="{{ $settings->annual_budget }}" class="mt-1 w-full px-3 py-1.5 border border-pg-border rounded-lg text-sm">
                </div>
                <div>
                    <label class="text-xs font-medium text-pg-text">Funding Goal (TZS)</label>
                    <input type="number" step="0.01" name="funding_goal" value="{{ $settings->funding_goal }}" class="mt-1 w-full px-3 py-1.5 border border-pg-border rounded-lg text-sm">
                </div>
                <button type="submit" class="w-full px-3 py-1.5 text-xs rounded-lg bg-pg-blue text-white font-medium hover:opacity-90">Save Settings</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

        {{-- TRANSACTIONS --}}
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h3 class="font-semibold text-pg-text mb-3">Record Transaction</h3>
            <form action="/finance/transactions" method="POST" class="space-y-2 mb-4">
                @csrf
                <div class="flex gap-2">
                    <select name="type" class="flex-1 px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                        <option>Income</option>
                        <option>Expense</option>
                    </select>
                    <input type="text" name="category" placeholder="Category (e.g. Salaries)" required class="flex-1 px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                </div>
                <div class="flex gap-2">
                    <input type="number" step="0.01" name="amount" placeholder="Amount (TZS)" required class="flex-1 px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                    <input type="date" name="transaction_date" required class="flex-1 px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                </div>
                <input type="text" name="description" placeholder="Description (optional)" class="w-full px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                <button type="submit" class="w-full px-3 py-1.5 text-xs rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Add Transaction</button>
            </form>

            <div class="space-y-2 max-h-80 overflow-y-auto">
                @forelse ($transactions as $t)
                    <div class="flex items-center justify-between text-sm border-b border-pg-border pb-2 last:border-0">
                        <div>
                            <p class="text-pg-text">{{ $t->category }} <span class="text-pg-muted text-xs">— {{ $t->description }}</span></p>
                            <p class="text-xs text-pg-muted">{{ $t->transaction_date->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="{{ $t->type === 'Income' ? 'text-pg-green' : 'text-pg-orange' }} font-medium text-sm">
                                {{ $t->type === 'Income' ? '+' : '-' }}TZS {{ number_format($t->amount, 0) }}
                            </span>
                            <form action="/finance/transactions/{{ $t->id }}" method="POST" onsubmit="return confirm('Delete this transaction?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:underline">✕</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-pg-muted">No transactions recorded yet.</p>
                @endforelse
            </div>
        </div>

        {{-- FUNDING REQUESTS --}}
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h3 class="font-semibold text-pg-text mb-3">Funding Requests</h3>
            <form action="/finance/funding-requests" method="POST" class="space-y-2 mb-4">
                @csrf
                <input type="text" name="investor_name" placeholder="Investor / Firm name" required class="w-full px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                <div class="flex gap-2">
                    <input type="number" step="0.01" name="amount_requested" placeholder="Amount requested (TZS)" required class="flex-1 px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                    <input type="date" name="request_date" required class="flex-1 px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                </div>
                <select name="status" class="w-full px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                    @foreach (['Draft', 'Sent', 'Under Review', 'Accepted', 'Declined'] as $s)
                        <option>{{ $s }}</option>
                    @endforeach
                </select>
                <input type="text" name="notes" placeholder="Notes (optional)" class="w-full px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                <button type="submit" class="w-full px-3 py-1.5 text-xs rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Add Funding Request</button>
            </form>

            <div class="space-y-2 max-h-80 overflow-y-auto">
                @forelse ($fundingRequests as $fr)
                    <div class="flex items-center justify-between text-sm border-b border-pg-border pb-2 last:border-0">
                        <div>
                            <p class="text-pg-text">{{ $fr->investor_name }}</p>
                            <p class="text-xs text-pg-muted">TZS {{ number_format($fr->amount_requested, 0) }} — {{ $fr->request_date->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-2 py-0.5 rounded-md bg-pg-blue-light text-pg-blue">{{ $fr->status }}</span>
                            <form action="/finance/funding-requests/{{ $fr->id }}" method="POST" onsubmit="return confirm('Delete this request?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:underline">✕</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-pg-muted">No funding requests yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.financeData = @json($cashFlow);
    </script>
    @endpush

</x-layouts.app>
