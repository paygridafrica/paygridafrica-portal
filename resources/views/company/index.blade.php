<x-layouts.app title="Company Management">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Company Management</h1>
        <p class="text-pg-muted text-sm mt-1">Mission, vision, registration details, and milestones.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">{{ session('success') }}</div>
    @endif

    {{-- COMPANY PROFILE FORM --}}
    <form action="/company" method="POST" class="bg-white rounded-xl border border-pg-border shadow-sm p-6 mb-6 space-y-5">
        @csrf
        @method('PUT')
        <h2 class="font-semibold text-pg-text">Status Overview</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <div>
        <label class="text-sm font-medium text-pg-text">Company Status</label>
        <input type="text" name="company_status" value="{{ old('company_status', $profile->company_status) }}" placeholder="e.g. Active — Building"
               class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
    </div>
    <div>
        <label class="text-sm font-medium text-pg-text">Strategic Phase</label>
        <input type="text" name="strategic_phase" value="{{ old('strategic_phase', $profile->strategic_phase) }}" placeholder="e.g. Phase 2: Pilot Preparation"
               class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
    </div>
    <div>
        <label class="text-sm font-medium text-pg-text">Weekly Progress (%)</label>
        <input type="number" name="weekly_progress_percent" min="0" max="100" value="{{ old('weekly_progress_percent', $profile->weekly_progress_percent) }}"
               class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
    </div>
</div>
<hr class="border-pg-border">

        <h2 class="font-semibold text-pg-text">Mission, Vision & Values</h2>

        <div>
            <label class="text-sm font-medium text-pg-text">Mission</label>
            <textarea name="mission" rows="2" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">{{ old('mission', $profile->mission) }}</textarea>
        </div>

        <div>
            <label class="text-sm font-medium text-pg-text">Vision</label>
            <textarea name="vision" rows="2" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">{{ old('vision', $profile->vision) }}</textarea>
        </div>

        <div>
            <label class="text-sm font-medium text-pg-text">Core Values <span class="text-pg-muted font-normal">(one per line)</span></label>
            <textarea name="core_values" rows="4" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">{{ old('core_values', implode("\n", $profile->core_values ?? [])) }}</textarea>
        </div>

        <div>
            <label class="text-sm font-medium text-pg-text">Company Description</label>
            <textarea name="company_description" rows="3" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">{{ old('company_description', $profile->company_description) }}</textarea>
        </div>

        <hr class="border-pg-border">

        <h2 class="font-semibold text-pg-text">Registration & Trademark</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium text-pg-text">Registration Number</label>
                <input type="text" name="registration_number" value="{{ old('registration_number', $profile->registration_number) }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-pg-text">Registration Date</label>
                <input type="date" name="registration_date" value="{{ old('registration_date', $profile->registration_date?->format('Y-m-d')) }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-pg-text">Registered Country</label>
                <input type="text" name="registered_country" value="{{ old('registered_country', $profile->registered_country) }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>
            <div>
                <label class="text-sm font-medium text-pg-text">Trademark Status</label>
                <select name="trademark_status" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach (['Not Filed', 'Filed', 'Registered'] as $status)
                        <option value="{{ $status }}" {{ old('trademark_status', $profile->trademark_status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-pg-text">Trademark Number</label>
                <input type="text" name="trademark_number" value="{{ old('trademark_number', $profile->trademark_number) }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>
        </div>

        <hr class="border-pg-border">

        <h2 class="font-semibold text-pg-text">Brand Guidelines</h2>
        <div>
            <label class="text-sm font-medium text-pg-text">Notes</label>
            <textarea name="brand_guidelines_notes" rows="3" placeholder="e.g. logo usage rules, brand colors, typography, tone of voice"
                      class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">{{ old('brand_guidelines_notes', $profile->brand_guidelines_notes) }}</textarea>
        </div>

        <button type="submit" class="px-5 py-2 text-sm rounded-lg bg-pg-blue text-white font-medium hover:opacity-90">Save Company Profile</button>
    </form>

    {{-- MILESTONES --}}
    <div class="bg-white rounded-xl border border-pg-border shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-pg-text">Company Milestones</h2>
        </div>

        <form action="/company/milestones" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6 items-end">
            @csrf
            <div class="md:col-span-2">
                <label class="text-xs font-medium text-pg-text">Title</label>
                <input type="text" name="title" required class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-pg-text">Date</label>
                <input type="date" name="milestone_date" required class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-pg-text">Status</label>
                <select name="status" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach (['Planned', 'In Progress', 'Achieved'] as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4">
                <label class="text-xs font-medium text-pg-text">Description</label>
                <input type="text" name="description" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>
            <div class="md:col-span-4">
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Add Milestone</button>
            </div>
        </form>

        <div class="space-y-3">
            @forelse ($milestones as $milestone)
                <div class="flex items-start justify-between border-b border-pg-border pb-3 last:border-0">
                    <div>
                        <p class="font-medium text-pg-text text-sm">{{ $milestone->title }}</p>
                        @if ($milestone->description)
                            <p class="text-pg-muted text-sm mt-0.5">{{ $milestone->description }}</p>
                        @endif
                        <p class="text-xs text-pg-muted mt-1">{{ $milestone->milestone_date?->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-1 rounded-md text-xs font-medium
                            {{ $milestone->status === 'Achieved' ? 'bg-pg-green-light text-pg-green' : ($milestone->status === 'In Progress' ? 'bg-pg-orange-light text-pg-orange' : 'bg-pg-blue-light text-pg-blue') }}">
                            {{ $milestone->status }}
                        </span>
                        <form action="/company/milestones/{{ $milestone->id }}" method="POST" onsubmit="return confirm('Remove this milestone?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:underline">Remove</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-pg-muted text-sm">No milestones added yet.</p>
            @endforelse
        </div>
    </div>

</x-layouts.app>
