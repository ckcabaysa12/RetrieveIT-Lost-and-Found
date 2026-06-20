<x-guest-layout>
    <h2 class="h5 fw-bold mb-1">Create account</h2>
    <p class="text-muted small mb-4">Sign up with your valid ID for verification.</p>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <x-input-label for="name" :value="__('Full name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <x-input-label for="phone" value="Contact number" />
            <x-text-input id="phone" type="tel" name="phone" :value="old('phone')" placeholder="09XX XXX XXXX" required />
            <div class="form-text">Shared with the other party after a claim is approved, for pickup coordination.</div>
            <x-input-error :messages="$errors->get('phone')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required minlength="8" />
            <div class="form-text">At least 8 characters.</div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password_confirmation" :value="__('Confirm password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
            @if($errors->has('password') && str_contains($errors->first('password'), 'match'))
                <div class="text-danger small mt-1">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <div class="mb-3">
            <x-input-label for="id_image" value="Valid ID photo" />
            <input id="id_image" type="file" name="id_image" class="form-control" accept="image/*" required>
            <div class="form-text">Clear photo of a valid ID. Used for your blue check badge.</div>
            <x-input-error :messages="$errors->get('id_image')" />
        </div>

        <x-primary-button class="w-100 btn-lf">{{ __('Register') }}</x-primary-button>

        <p class="text-center small mt-3 mb-0">
            Already have an account? <a href="{{ route('login') }}">Log in</a>
        </p>
    </form>
</x-guest-layout>
