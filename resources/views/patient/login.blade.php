@php
use Illuminate\Support\Facades\Cookie;
@endphp

<x-layouts.patient-auth :title="'Masuk - Pasien'">
<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Masuk ke Akun Pasien')" :description="__('Masukkan email dan password Anda untuk masuk')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" action="{{ route('patient.login.submit') }}" class="flex flex-col gap-6">
        @csrf
        
        <!-- Email or BPJS Number -->
        <div class="space-y-2">
            <div>
                <flux:input
                    id="login"
                    name="login"
                    :label="__('Email atau Nomor BPJS')"
                    type="text"
                    required
                    autofocus
                    :value="old('login', Cookie::get('patient_login_remember'))"
                    placeholder="Email atau Nomor BPJS"
                />
                @error('login')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <p class="text-xs text-gray-500">
                {{ __('Gunakan email atau nomor BPJS yang terdaftar') }}
            </p>
        </div>

        <!-- Password -->
        <div class="relative">
            <flux:input
                id="password"
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />
            @if (Route::has('patient.password.request'))
                <flux:link class="absolute end-0 top-0 text-sm" :href="route('patient.password.request')">
                    {{ __('Lupa password?') }}
                </flux:link>
            @endif
        </div>
        @error('password')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="inline-flex items-center">
                <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" {{ old('remember') ? 'checked' : '' }}>
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-300">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">{{ __('Masuk') }}</flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Belum punya akun?') }}</span>
        <flux:link :href="route('patient.register')">{{ __('Daftar disini') }}</flux:link>
    </div>
</div>
</x-layouts.patient-auth>
