<x-layouts.app title="Legal Center">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Legal Center</h1>
        <p class="text-pg-muted text-sm mt-1">Contracts, NDAs, trademark status, and compliance.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">{{ session('success') }}</div>
    @endif

    {{-- REGISTRATION & TRADEMARK SNAPSHOT (read-only, from Company Management) --}}
    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5 mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-pg-text">Registration & Trademark</h2>
            <a href="/company" class="text-xs text-pg-blue hover:underline">Edit in Company Management →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-xs text-pg-muted">Registration No.</p>
                <p class="text-pg-text font-medium">{{ $companyProfile->registration_number ?: '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-pg-muted">Registered Country</p>
                <p class="text-pg-text font-medium">{{ $companyProfile->registered_country ?: '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-pg-muted">Trademark Status</p>
                <p class="text-pg-text font-medium">{{ $companyProfile->trademark_status ?: '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-pg-muted">Trademark No.</p>
                <p class="text-pg-text font-medium">{{ $companyProfile->trademark_number ?: '—' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- DOCUMENTS --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h2 class="font-semibold text-pg-text mb-4">Contracts, NDAs & Agreements</h2>

            <form action="/legal/documents" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                @csrf
                <input type="text" name="title" placeholder="Document title" required class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                <select name="category" class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach (['Contract', 'NDA', 'Partnership Agreement', 'License', 'Other'] as $cat)
                        <option>{{ $cat }}</option>
                    @endforeach
                </select>
                <input type="text" name="party_name" placeholder="Party / Counterparty (optional)" class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                <select name="status" class="px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach (['Draft', 'Under Review', 'Signed', 'Expired'] as $s)
                        <option>{{ $s }}</option>
                    @endforeach
                </select>
                <div>
                    <label class="text-xs text-pg-muted">Signed Date</label>
                    <input type="date" name="signed_date" class="w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                </div>
                <div>
                    <label class="text-xs text-pg-muted">Expiry Date</label>
                    <input type="date" name="expiry_date" class="w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                </div>
                <textarea name="notes" placeholder="Notes (optional)" rows="2" class="md:col-span-2 px-3 py-2 border border-pg-border rounded-lg text-sm"></textarea>
                <button type="submit" class="md:col-span-2 px-4 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Log Document</button>
            </form>

            <div class="space-y-3">
                @forelse ($documents as $doc)
                    <div class="border-b border-pg-border pb-3 last:border-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-pg-text">{{ $doc->title }}</p>
                                <p class="text-xs text-pg-muted">{{ $doc->category }}{{ $doc->party_name ? ' — ' . $doc->party_name : '' }}</p>
                                @if ($doc->expiry_date)
                                    <p class="text-xs text-pg-muted mt-1">Expires: {{ $doc->expiry_date->format('d M Y') }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded-md font-medium
                                    {{ $doc->status === 'Signed' ? 'bg-pg-green-light text-pg-green' : ($doc->status === 'Expired' ? 'bg-red-50 text-red-600' : 'bg-pg-blue-light text-pg-blue') }}">
                                    {{ $doc->status }}
                                </span>
                                <form action="/legal/documents/{{ $doc->id }}" method="POST" onsubmit="return confirm('Remove this document?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:underline">✕</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-pg-muted">No documents logged yet.</p>
                @endforelse
            </div>
        </div>

        {{-- COMPLIANCE CHECKLIST --}}
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h2 class="font-semibold text-pg-text mb-4">Compliance Checklist</h2>

            <form action="/legal/compliance" method="POST" class="space-y-2 mb-4">
                @csrf
                <input type="text" name="title" placeholder="Checklist item" required class="w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                <input type="date" name="due_date" class="w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                <button type="submit" class="w-full px-3 py-1.5 text-xs rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Add Item</button>
            </form>

            <div class="space-y-2">
                @forelse ($complianceItems as $item)
                    <div class="flex items-start gap-2 border-b border-pg-border pb-2 last:border-0">
                        <form action="/legal/compliance/{{ $item->id }}/toggle" method="POST" class="mt-0.5">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-4 h-4 rounded border {{ $item->is_complete ? 'bg-pg-green border-pg-green' : 'border-pg-border' }} flex items-center justify-center">
                                @if ($item->is_complete)
                                    <span class="text-white text-[10px]">✓</span>
                                @endif
                            </button>
                        </form>
                        <div class="flex-1">
                            <p class="text-sm {{ $item->is_complete ? 'text-pg-muted line-through' : 'text-pg-text' }}">{{ $item->title }}</p>
                            @if ($item->due_date)
                                <p class="text-xs text-pg-muted">Due {{ $item->due_date->format('d M Y') }}</p>
                            @endif
                        </div>
                        <form action="/legal/compliance/{{ $item->id }}" method="POST" onsubmit="return confirm('Remove this item?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:underline">✕</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-pg-muted">No compliance items yet.</p>
                @endforelse
            </div>
        </div>
    </div>

</x-layouts.app>
