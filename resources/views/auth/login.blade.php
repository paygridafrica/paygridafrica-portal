<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-pg-bg px-4">
        <div class="w-full max-w-sm">
<div class="text-center mb-8">
    <img src="/images/logo.png" alt="PayGrid Africa" class="h-14 mx-auto mb-2">
    <p class="text-pg-muted text-sm mt-1">Paygrid-Africa Portal</p>
</div>

            <div class="bg-white rounded-xl border border-pg-border shadow-sm p-8">
                <h2 class="text-lg font-semibold text-pg-text mb-1">Welcome back</h2>
                <p class="text-pg-muted text-sm mb-6">Log in to access the portal.</p>

                @if (session('status'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-pg-green-light text-pg-green text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="text-sm font-medium text-pg-text">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pg-blue-light">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="text-sm font-medium text-pg-text">Password</label>
                        <input id="password" type="password" name="password" required
                               class="mt-1 w-full px-3 py-2 border border-pg-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pg-blue-light">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-pg-muted">
                            <input type="checkbox" name="remember" class="w-4 h-4">
                            Remember me
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-pg-blue hover:underline">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-lg bg-pg-blue text-white text-sm font-medium hover:opacity-90">
                        Log In
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-pg-muted mt-6">PayGrid Africa — Internal Systems</p>
        </div>
    </div>
</x-guest-layout>
