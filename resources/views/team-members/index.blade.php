<x-layouts.app title="Team Members">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Team Members</h1>
        <p class="text-pg-muted text-sm mt-1">Everyone currently working on PayGrid Africa.</p>
    </div>

    <div class="bg-white rounded-xl border border-pg-border shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-pg-bg text-pg-muted text-left">
                <tr>
                    <th class="px-6 py-3 font-medium">Name</th>
                    <th class="px-6 py-3 font-medium">Role</th>
                    <th class="px-6 py-3 font-medium">Email</th>
                    <th class="px-6 py-3 font-medium">Department</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-pg-border">
                @foreach ($members as $member)
                    <tr class="hover:bg-pg-bg/50">
                        <td class="px-6 py-4 font-medium text-pg-text">{{ $member->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-md bg-pg-blue-light text-pg-blue text-xs font-medium">{{ $member->role }}</span>
                        </td>
                        <td class="px-6 py-4 text-pg-muted">{{ $member->email }}</td>
                        <td class="px-6 py-4 text-pg-muted">{{ $member->department }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.app>
