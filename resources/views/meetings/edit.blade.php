    <x-layouts.app title="Edit Meeting">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Log a New Meeting</h1>
        <p class="text-pg-muted text-sm mt-1">Add a contact and meeting details.</p>
    </div>

   <form action="/meetings/{{ $meeting->id }}" method="POST" class="bg-white rounded-xl border border-pg-border shadow-sm p-6 max-w-3xl space-y-5">
    @csrf
    @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium text-pg-text">Contact Name *</label>
                <input type="text" name="contact_name" required value="{{ old('contact_name') }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pg-blue-light">
                @error('contact_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Organization</label>
                <input type="text" name="organization" value="{{ old('organization') }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pg-blue-light">
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Position</label>
                <input type="text" name="position" value="{{ old('position') }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pg-blue-light">
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Category *</label>
                <select name="category" required class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pg-blue-light">
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pg-blue-light">
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Meeting Date *</label>
                <input type="date" name="meeting_date" required value="{{ old('meeting_date', $meeting->meeting_date?->format('Y-m-d')) }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Follow-up Date</label>
                <input type="date" name="follow_up_date" value="{{ old('follow_up_date', $meeting->follow_up_date?->format('Y-m-d')) }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Status *</label>
                <select name="status" required class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach (['Scheduled', 'Completed', 'Cancelled', 'Rescheduled'] as $s)
                       <option value="{{ $s }}" {{ old('status', $meeting->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Probability *</label>
                <select name="probability" required class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach (['Low', 'Medium', 'High'] as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Relationship Strength *</label>
                <select name="relationship_strength" required class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach (['New', 'Warm', 'Strong'] as $r)
                        <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium text-pg-text">Meeting Notes</label>
            <textarea name="notes" rows="4" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-5 py-2 text-sm rounded-lg bg-pg-blue text-white font-medium hover:opacity-90">Save Meeting</button>
            <a href="/meetings" class="px-5 py-2 text-sm rounded-lg border border-pg-border text-pg-muted hover:bg-pg-bg">Cancel</a>
        </div>
    </form>
</x-layouts.app>
