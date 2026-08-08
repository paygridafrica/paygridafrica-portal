<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} — PayGrid Africa HQ Portal</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <style>[x-cloak] { display: none !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-pg-bg text-pg-text font-sans antialiased" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white border-r border-pg-border flex flex-col transition-all duration-200 shrink-0">
            <div class="h-16 flex items-center px-4 border-b border-pg-border">
                <img x-show="sidebarOpen" src="/images/logo.png" alt="PayGrid Africa" class="h-8">
                <img x-show="!sidebarOpen" src="/images/logo.png" alt="PayGrid Africa" class="h-8 w-8 object-cover object-left">
            </div>
            <div x-show="sidebarOpen" class="px-4 py-2 border-b border-pg-border">
                <p class="text-[11px] uppercase tracking-wide text-pg-muted font-medium">PaygridAfrica Portal</p>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 space-y-5 text-sm">
                @php
                    $navGroups = [
                        'Overview' => [
    ['label' => 'Executive Dashboard', 'route' => '/dashboard'],
    ['label' => 'Calendar', 'route' => '/calendar'],
    ['label' => 'Reports', 'route' => '/reports'],
],
                        'Company' => [
                            ['label' => 'Company Management', 'route' => '/company'],
                            ['label' => 'Product Management', 'route' => '/products'],
                            ['label' => 'Project Roadmap', 'route' => '/roadmap'],
                        ],
                        'Relationships' => [
                            ['label' => 'Meetings CRM', 'route' => '/meetings'],
                            ['label' => 'Partnership Pipeline', 'route' => '/partnerships'],
                            ['label' => 'Investor CRM', 'route' => '/investors'],
                        ],
                        'Operations' => [
                            ['label' => 'Finance', 'route' => '/finance'],
                            ['label' => 'Legal Center', 'route' => '/legal'],
                            ['label' => 'Research Center', 'route' => '/research'],
                        ],
                        'Resources' => [
                            ['label' => 'Document Center', 'route' => '/documents'],
                            ['label' => 'Media Library', 'route' => '/media'],
                            ['label' => 'Founder Workspace', 'route' => '/workspace'],
                        ],
                    ];
                @endphp

                @foreach ($navGroups as $groupLabel => $items)
                    <div>
                        <p x-show="sidebarOpen" class="px-6 mb-1 text-[11px] uppercase tracking-wide text-pg-muted font-medium">{{ $groupLabel }}</p>
                        <div class="space-y-0.5">
                            @foreach ($items as $item)
                                @php $active = request()->is(ltrim($item['route'], '/').'*'); @endphp
                                <a href="{{ $item['route'] }}"
                                   class="flex items-center gap-3 pl-5 pr-4 py-2.5 mx-2 rounded-lg border-l-[3px] transition
                                   {{ $active ? 'bg-pg-blue-light text-pg-blue font-medium border-pg-blue' : 'text-pg-muted border-transparent hover:bg-pg-bg hover:text-pg-blue' }}">
                                    <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $active ? 'bg-pg-orange' : 'bg-pg-border' }}"></span>
                                    <span x-show="sidebarOpen">{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>
        </aside>

        {{-- MAIN COLUMN --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- TOP BAR --}}
            <header class="h-16 bg-white border-b border-pg-border flex items-center justify-between px-6 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-pg-muted hover:text-pg-blue">
                        ☰
                    </button>
                    <input type="text" placeholder="Search..."
                           class="hidden md:block w-72 px-3 py-1.5 text-sm border border-pg-border rounded-lg focus:outline-none focus:ring-2 focus:ring-pg-blue-light">
                </div>

                <div class="flex items-center gap-4">
                    <button class="w-9 h-9 rounded-full bg-pg-bg flex items-center justify-center text-pg-muted hover:text-pg-blue">🔔</button>
                    <button class="px-3 py-1.5 text-sm rounded-lg bg-pg-orange text-white font-medium hover:opacity-90">+ Quick Action</button>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="w-9 h-9 rounded-full bg-pg-blue text-white flex items-center justify-center text-sm font-medium">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg border border-pg-border shadow-lg py-2 text-sm z-50">
                            <div class="px-4 py-2 border-b border-pg-border">
                                <p class="font-medium text-pg-text">{{ auth()->user()->name }}</p>
                                <p class="text-pg-muted text-xs">{{ auth()->user()->email }}</p>
                            </div>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-500 hover:bg-pg-bg">Log out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- PAGE CONTENT --}}
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
    {{-- GLOBAL LOADING OVERLAY --}}
    <div id="global-loading-overlay" class="hidden fixed inset-0 bg-black/30 z-[9999] flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg px-6 py-5 flex items-center gap-3">
            <svg class="animate-spin h-5 w-5 text-pg-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span class="text-sm font-medium text-pg-text">Saving...</span>
        </div>
    </div>
</body>
</html>
