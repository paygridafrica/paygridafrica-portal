<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} — PayGrid Africa HQ Portal</title>
    <style>[x-cloak] { display: none !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-pg-bg text-pg-text font-sans antialiased" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white border-r border-pg-border flex flex-col transition-all duration-200 shrink-0">
            <div class="h-16 flex items-center px-4 border-b border-pg-border">
                <span x-show="sidebarOpen" class="font-bold text-pg-blue text-lg">PayGrid <span class="text-pg-orange">Africa</span></span>
                <span x-show="!sidebarOpen" class="font-bold text-pg-blue text-lg">PG</span>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 space-y-1 text-sm">
                @php
                    $navItems = [
                        ['label' => 'Executive Dashboard', 'route' => '/dashboard'],
                        ['label' => 'Company Management', 'route' => '/company'],
                        ['label' => 'Product Management', 'route' => '/products'],
                        ['label' => 'Meetings CRM', 'route' => '/meetings'],
                        ['label' => 'Partnership Pipeline', 'route' => '/partnerships'],
                        ['label' => 'Investor CRM', 'route' => '/investors'],
                        ['label' => 'Project Roadmap', 'route' => '/roadmap'],
                        ['label' => 'Finance', 'route' => '/finance'],
                        ['label' => 'Legal Center', 'route' => '/legal'],
                        ['label' => 'Research Center', 'route' => '/research'],
                        ['label' => 'Document Center', 'route' => '/documents'],
                        ['label' => 'Media Library', 'route' => '/media'],
                        ['label' => 'Founder Workspace', 'route' => '/workspace'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    <a href="{{ $item['route'] }}"
                       class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-pg-muted hover:bg-pg-blue-light hover:text-pg-blue transition
                       {{ request()->is(ltrim($item['route'], '/').'*') ? 'bg-pg-blue-light text-pg-blue font-medium' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-pg-orange shrink-0"></span>
                        <span x-show="sidebarOpen">{{ $item['label'] }}</span>
                    </a>
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
</body>
</html>
