<x-layouts.app title="Partnership Pipeline">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-pg-text">Partnership Pipeline</h1>
            <p class="text-pg-muted text-sm mt-1">Drag cards between stages as relationships progress.</p>
        </div>
        <button onclick="document.getElementById('newPartnerModal').classList.remove('hidden')"
                class="px-4 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ New Partner</button>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">{{ session('success') }}</div>
    @endif

    {{-- KANBAN BOARD --}}
    <div class="flex gap-4 overflow-x-auto pb-4" x-data="kanbanBoard()">
        @foreach ($stages as $stage)
            <div class="bg-pg-bg rounded-xl border border-pg-border w-72 shrink-0 flex flex-col"
                 @dragover.prevent
                 @drop="onDrop($event, '{{ $stage }}')">
                <div class="px-4 py-3 border-b border-pg-border flex items-center justify-between">
                    <p class="font-semibold text-sm text-pg-text">{{ $stage }}</p>
                    <span class="text-xs text-pg-muted">{{ $partnerships->where('stage', $stage)->count() }}</span>
                </div>
                <div class="p-3 space-y-3 min-h-[120px]">
                    @foreach ($partnerships->where('stage', $stage) as $p)
                        <div draggable="true"
                             @dragstart="onDragStart($event, '{{ $p->id }}')"
                             class="bg-white rounded-lg border border-pg-border shadow-sm p-3 cursor-move hover:shadow-md transition">
                            <p class="font-medium text-sm text-pg-text">{{ $p->organization }}</p>
                            @if ($p->contact_name)
                                <p class="text-xs text-pg-muted mt-1">{{ $p->contact_name }}</p>
                            @endif
                            @if ($p->category)
                                <span class="inline-block mt-2 px-2 py-0.5 rounded-md bg-pg-blue-light text-pg-blue text-xs font-medium">{{ $p->category }}</span>
                            @endif
                            <form action="/partnerships/{{ $p->id }}" method="POST" class="mt-2" onsubmit="return confirm('Remove this partnership?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:underline">Remove</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- NEW PARTNER MODAL --}}
    <div id="newPartnerModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-semibold text-pg-text mb-4">New Partnership</h2>
            <form action="/partnerships" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-medium text-pg-text">Organization *</label>
                    <input type="text" name="organization" required class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium text-pg-text">Contact Name</label>
                    <input type="text" name="contact_name" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium text-pg-text">Category</label>
                    <input type="text" name="category" placeholder="e.g. Football, Telecom" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium text-pg-text">Notes</label>
                    <textarea name="notes" rows="3" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-pg-blue text-white font-medium hover:opacity-90">Add</button>
                    <button type="button" onclick="document.getElementById('newPartnerModal').classList.add('hidden')"
                            class="px-4 py-2 text-sm rounded-lg border border-pg-border text-pg-muted hover:bg-pg-bg">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function kanbanBoard() {
            return {
                draggedId: null,
                onDragStart(event, id) {
                    this.draggedId = id;
                },
                onDrop(event, newStage) {
                    const id = this.draggedId;
                    if (!id) return;

                    fetch(`/partnerships/${id}/stage`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ stage: newStage }),
                    }).then(() => window.location.reload());
                },
            };
        }
    </script>
    @endpush
</x-layouts.app>
