<x-guest-layout>
    <h2 class="h5 fw-bold mb-4">Welcome back</h2>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="form-check mb-3">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label small">Remember me</label>
        </div>

        <x-primary-button class="w-100 btn-lf">{{ __('Log in') }}</x-primary-button>

        @if (Route::has('register'))
            <p class="text-center small mt-3 mb-0">
                New here? <a href="{{ route('register') }}">Sign up with ID</a>
            </p>
        @endif
    </form>
</x-guest-layout>
