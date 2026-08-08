<x-layouts.app title="Calendar">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Calendar & Reminders</h1>
        <p class="text-pg-muted text-sm mt-1">Every meeting, follow-up, and task due date in one view.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- CALENDAR GRID --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-pg-border shadow-sm p-5"
             x-data="calendarView({{ $eventsByDate->toJson() }})">

            <div class="flex items-center justify-between mb-4">
                <button @click="prevMonth" class="px-3 py-1 text-sm rounded-lg border border-pg-border text-pg-muted hover:bg-pg-bg">←</button>
                <p class="font-semibold text-pg-text" x-text="monthLabel"></p>
                <button @click="nextMonth" class="px-3 py-1 text-sm rounded-lg border border-pg-border text-pg-muted hover:bg-pg-bg">→</button>
            </div>

           <div class="grid grid-cols-7 gap-1.5 text-center text-xs font-semibold text-pg-muted mb-2">
                <div class="text-pg-orange">Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div class="text-pg-orange">Sat</div>
            </div>

            <div class="grid grid-cols-7 gap-1.5">
                <template x-for="blank in leadingBlanks" :key="'b'+blank">
                    <div></div>
                </template>
                <template x-for="day in daysInMonth" :key="day">
                    <div class="min-h-[76px] rounded-lg p-1.5 border transition"
                         :class="isToday(day) ? 'bg-pg-blue border-pg-blue shadow-sm' : 'bg-pg-bg/40 border-pg-border hover:bg-pg-blue-light hover:border-pg-blue-light'">
                        <p class="text-xs font-semibold w-5 h-5 flex items-center justify-center rounded-full"
                           :class="isToday(day) ? 'bg-white text-pg-blue' : 'text-pg-text'" x-text="day"></p>
                        <template x-for="event in eventsFor(day)" :key="event.title + event.date">
                            <a :href="event.link" class="flex items-center gap-1 text-[10px] font-medium px-1.5 py-0.5 mt-1 rounded-md truncate shadow-sm"
                               :class="eventColor(event.type)"
                               :title="event.title + ' — ' + event.type + ' — ' + event.date">
                                <span class="w-1.5 h-1.5 rounded-full shrink-0" :class="eventDotColor(event.type)"></span>
                                <span x-text="event.title" class="truncate"></span>
                            </a>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        {{-- UPCOMING LIST --}}
        <div class="bg-white rounded-xl border border-pg-border shadow-sm p-5">
            <h3 class="font-semibold text-pg-text mb-3">Upcoming</h3>
            <div class="space-y-3">
                @forelse ($upcoming as $event)
                    <a href="{{ $event['link'] }}" class="block border-b border-pg-border pb-2 last:border-0 hover:bg-pg-bg/50 -mx-1 px-1 rounded">
                        <p class="text-sm text-pg-text">{{ $event['title'] }}</p>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-xs text-pg-muted">{{ \Carbon\Carbon::parse($event['date'])->format('d M Y') }}</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-md bg-pg-bg text-pg-muted">{{ $event['type'] }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-xs text-pg-muted">Nothing scheduled yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function calendarView(eventsByDate) {
            return {
                current: new Date(),
                eventsByDate: eventsByDate,

                get monthLabel() {
                    return this.current.toLocaleString('default', { month: 'long', year: 'numeric' });
                },
                get daysInMonth() {
                    return new Date(this.current.getFullYear(), this.current.getMonth() + 1, 0).getDate();
                },
                get leadingBlanks() {
                    return new Date(this.current.getFullYear(), this.current.getMonth(), 1).getDay();
                },
                isToday(day) {
                    const today = new Date();
                    return day === today.getDate()
                        && this.current.getMonth() === today.getMonth()
                        && this.current.getFullYear() === today.getFullYear();
                },
                dateKey(day) {
                    const m = String(this.current.getMonth() + 1).padStart(2, '0');
                    const d = String(day).padStart(2, '0');
                    return `${this.current.getFullYear()}-${m}-${d}`;
                },
                eventsFor(day) {
                    return this.eventsByDate[this.dateKey(day)] || [];
                },
              eventColor(type) {
                    return {
                        'Meeting': 'bg-pg-blue-light text-pg-blue',
                        'Follow-up': 'bg-pg-orange-light text-pg-orange',
                        'Investor': 'bg-pg-green-light text-pg-green',
                        'Task': 'bg-white text-pg-muted border border-pg-border',
                    }[type] || 'bg-white text-pg-muted';
                },
                eventDotColor(type) {
                    return {
                        'Meeting': 'bg-pg-blue',
                        'Follow-up': 'bg-pg-orange',
                        'Investor': 'bg-pg-green',
                        'Task': 'bg-pg-muted',
                    }[type] || 'bg-pg-muted';
                },
                prevMonth() {
                    this.current = new Date(this.current.getFullYear(), this.current.getMonth() - 1, 1);
                },
                nextMonth() {
                    this.current = new Date(this.current.getFullYear(), this.current.getMonth() + 1, 1);
                },
            };
        }
    </script>
    @endpush
</x-layouts.app>
