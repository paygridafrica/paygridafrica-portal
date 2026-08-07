<x-layouts.app title="Edit Investor">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Edit Investor</h1>
        <p class="text-pg-muted text-sm mt-1">Update this investor relationship.</p>
    </div>

    <form action="/investors/{{ $investor->id }}" method="POST" class="bg-white rounded-xl border border-pg-border shadow-sm p-6 max-w-3xl space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium text-pg-text">Name *</label>
                <input type="text" name="name" required value="{{ old('name', $investor->name) }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pg-blue-light">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Firm</label>
                <input type="text" name="firm" value="{{ old('firm', $investor->firm) }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pg-blue-light">
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Funding Stage *</label>
                <select name="funding_stage" required class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach (['Pre-Seed', 'Seed', 'Series A', 'Series B+'] as $stage)
                        <option value="{{ $stage }}" {{ old('funding_stage', $investor->funding_stage) === $stage ? 'selected' : '' }}>{{ $stage }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Investment Probability *</label>
                <select name="investment_probability" required class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
                    @foreach (['Low', 'Medium', 'High'] as $p)
                        <option value="{{ $p }}" {{ old('investment_probability', $investor->investment_probability) === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Email</label>
                <input type="email" name="email" value="{{ old('email', $investor->email) }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $investor->phone) }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>

            <div>
                <label class="text-sm font-medium text-pg-text">Follow-up Date</label>
                <input type="date" name="follow_up_date" value="{{ old('follow_up_date', $investor->follow_up_date?->format('Y-m-d')) }}"
                       class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">
            </div>

            <div class="flex items-center gap-2 pt-6">
                <input type="checkbox" name="pitch_deck_sent" value="1" id="pitch_deck_sent"
                       {{ old('pitch_deck_sent', $investor->pitch_deck_sent) ? 'checked' : '' }} class="w-4 h-4">
                <label for="pitch_deck_sent" class="text-sm font-medium text-pg-text">Pitch deck already sent</label>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium text-pg-text">Required Documents</label>
            <div class="mt-2 flex flex-wrap gap-4">
                @foreach (['Pitch Deck', 'Financial Model', 'Cap Table', 'Business Plan', 'Term Sheet'] as $doc)
                    <label class="flex items-center gap-2 text-sm text-pg-muted">
                        <input type="checkbox" name="required_documents[]" value="{{ $doc }}"
                               {{ in_array($doc, old('required_documents', $investor->required_documents ?? [])) ? 'checked' : '' }} class="w-4 h-4">
                        {{ $doc }}
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="text-sm font-medium text-pg-text">Notes</label>
            <textarea name="notes" rows="4" class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm">{{ old('notes', $investor->notes) }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-5 py-2 text-sm rounded-lg bg-pg-blue text-white font-medium hover:opacity-90">Update Investor</button>
            <a href="/investors" class="px-5 py-2 text-sm rounded-lg border border-pg-border text-pg-muted hover:bg-pg-bg">Cancel</a>
        </div>
    </form>
</x-layouts.app>
