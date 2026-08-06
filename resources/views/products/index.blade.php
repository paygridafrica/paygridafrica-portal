<x-layouts.app title="Product Management">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pg-text">Product Management</h1>
        <p class="text-pg-muted text-sm mt-1">All PayGrid Africa products, in one place.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($products as $product)
            <a href="/products/{{ $product->id }}" class="bg-white rounded-xl border border-pg-border shadow-sm p-5 hover:shadow-md transition block">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="font-semibold text-pg-text">{{ $product->name }}</h3>
                    <span class="px-2 py-1 rounded-md text-xs font-medium
                        {{ $product->priority === 'Critical' ? 'bg-red-50 text-red-600' : ($product->priority === 'High' ? 'bg-pg-orange-light text-pg-orange' : 'bg-pg-blue-light text-pg-blue') }}">
                        {{ $product->priority }}
                    </span>
                </div>
                <p class="text-xs text-pg-muted mb-3">Stage: <span class="font-medium text-pg-text">{{ $product->development_stage }}</span></p>

                @if ($product->screens_total > 0)
                    <div class="w-full bg-pg-bg rounded-full h-2 mb-1">
                        <div class="bg-pg-green h-2 rounded-full" style="width: {{ min(100, round(($product->screens_completed / $product->screens_total) * 100)) }}%"></div>
                    </div>
                    <p class="text-xs text-pg-muted">{{ $product->screens_completed }} / {{ $product->screens_total }} screens</p>
                @else
                    <p class="text-xs text-pg-muted">Screens not yet scoped</p>
                @endif

                <p class="text-xs text-pg-muted mt-3">Version {{ $product->version ?: '—' }}</p>
            </a>
        @endforeach
    </div>
</x-layouts.app>
