<x-layouts.app :title="$product->name">

    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="/products" class="text-sm text-pg-blue hover:underline">← All Products</a>
            <h1 class="text-2xl font-bold text-pg-text mt-1">{{ $product->name }}</h1>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">{{ session('success') }}</div>
    @endif

    {{-- PRODUCT DETAILS FORM --}}
    <form action="/products/{{ $product->id }}" method="POST" class="bg-white rounded-xl border border-pg-border shadow-sm p-6 mb-6 space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium text-pg-text">Development Stage</label>
                <select name="development_stage" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach (['Concept', 'Design', 'Development', 'Testing', 'Launched'] as $stage)
                        <option value="{{ $stage }}" {{ $product->development_stage === $stage ? 'selected' : '' }}>{{ $stage }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-pg-text">Priority</label>
                <select name="priority" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach (['Low', 'Medium', 'High', 'Critical'] as $p)
                        <option value="{{ $p }}" {{ $product->priority === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-pg-text">Version</label>
                <input type="text" name="version" value="{{ $product->version }}" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium text-pg-text">Screens Done</label>
                    <input type="number" name="screens_completed" value="{{ $product->screens_completed }}" min="0" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium text-pg-text">Screens Total</label>
                    <input type="number" name="screens_total" value="{{ $product->screens_total }}" min="0" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                </div>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium text-pg-text">Description</label>
            <textarea name="description" rows="2" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">{{ $product->description }}</textarea>
        </div>

        <button type="submit" class="px-5 py-2 text-sm rounded-lg bg-pg-blue text-white font-medium hover:opacity-90">Save Changes</button>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- TASKS --}}
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h3 class="font-semibold text-pg-text mb-3">Assigned Tasks</h3>
            <form action="/products/{{ $product->id }}/tasks" method="POST" class="space-y-2 mb-4">
                @csrf
                <input type="text" name="title" placeholder="Task title" required class="w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                <div class="flex gap-2">
                    <select name="status" class="flex-1 px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                        @foreach (['To Do', 'In Progress', 'Done'] as $s)
                            <option>{{ $s }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="due_date" class="flex-1 px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                </div>
                <button type="submit" class="w-full px-3 py-1.5 text-xs rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Add Task</button>
            </form>
            <div class="space-y-2">
                @forelse ($tasks as $task)
                    <div class="text-sm border-b border-pg-border pb-2 last:border-0">
                        <p class="text-pg-text">{{ $task->title }}</p>
                        <div class="flex justify-between mt-1">
                            <span class="text-xs px-2 py-0.5 rounded-md bg-pg-bg text-pg-muted">{{ $task->status }}</span>
                            @if ($task->due_date)
                                <span class="text-xs text-pg-muted">{{ $task->due_date->format('d M') }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-pg-muted">No tasks yet.</p>
                @endforelse
            </div>
        </div>

        {{-- FEATURE REQUESTS --}}
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h3 class="font-semibold text-pg-text mb-3">Feature Requests</h3>
            <form action="/products/{{ $product->id }}/features" method="POST" class="space-y-2 mb-4">
                @csrf
                <input type="text" name="title" placeholder="Feature title" required class="w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                <select name="status" class="w-full px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                    @foreach (['Requested', 'Planned', 'In Progress', 'Shipped', 'Declined'] as $s)
                        <option>{{ $s }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full px-3 py-1.5 text-xs rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Add Feature</button>
            </form>
            <div class="space-y-2">
                @forelse ($features as $feature)
                    <div class="text-sm border-b border-pg-border pb-2 last:border-0">
                        <p class="text-pg-text">{{ $feature->title }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-md bg-pg-blue-light text-pg-blue mt-1 inline-block">{{ $feature->status }}</span>
                    </div>
                @empty
                    <p class="text-xs text-pg-muted">No feature requests yet.</p>
                @endforelse
            </div>
        </div>

        {{-- BUGS --}}
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h3 class="font-semibold text-pg-text mb-3">Bug Tracker</h3>
            <form action="/products/{{ $product->id }}/bugs" method="POST" class="space-y-2 mb-4">
                @csrf
                <input type="text" name="title" placeholder="Bug title" required class="w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                <div class="flex gap-2">
                    <select name="severity" class="flex-1 px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                        @foreach (['Low', 'Medium', 'High', 'Critical'] as $s)
                            <option>{{ $s }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="flex-1 px-2 py-1.5 border border-pg-border rounded-lg text-xs">
                        @foreach (['Open', 'In Progress', 'Fixed'] as $s)
                            <option>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full px-3 py-1.5 text-xs rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Log Bug</button>
            </form>
            <div class="space-y-2">
                @forelse ($bugs as $bug)
                    <div class="text-sm border-b border-pg-border pb-2 last:border-0">
                        <p class="text-pg-text">{{ $bug->title }}</p>
                        <div class="flex gap-2 mt-1">
                            <span class="text-xs px-2 py-0.5 rounded-md {{ $bug->severity === 'Critical' ? 'bg-red-50 text-red-600' : 'bg-pg-orange-light text-pg-orange' }}">{{ $bug->severity }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-md bg-pg-bg text-pg-muted">{{ $bug->status }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-pg-muted">No bugs logged yet.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-layouts.app>
