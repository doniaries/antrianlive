<x-layouts.patient-auth :title="'Daftar - Pasien'">
<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Daftar Akun Pasien')" :description="__('Isi data diri Anda untuk membuat akun')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" action="{{ route('patient.register') }}" class="flex flex-col gap-6">
        @csrf

        <!-- Name -->
        <flux:input
            id="name"
            name="name"
            :label="__('Nama Lengkap')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Nama lengkap sesuai KTP')"
            :value="old('name')"
        />
        @error('name')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- Email -->
        <flux:input
            id="email"
            name="email"
            :label="__('Alamat Email')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
            :value="old('email')"
        />
        @error('email')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- Phone -->
        <flux:input
            id="phone"
            name="phone"
            :label="__('Nomor Telepon')"
            type="tel"
            required
            autocomplete="tel"
            placeholder="0812-3456-7890"
            :value="old('phone')"
        />
        @error('phone')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- BPJS Number -->
        <flux:input
            id="bpjs_number"
            name="bpjs_number"
            :label="__('Nomor BPJS')"
            type="text"
            required
            placeholder="0000-0000-0000-0000"
            :value="old('bpjs_number')"
        />
        @error('bpjs_number')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- Password -->
        <flux:input
            id="password"
            name="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
            viewable
        />
        @error('password')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- Confirm Password -->
        <flux:input
            id="password_confirmation"
            name="password_confirmation"
            :label="__('Konfirmasi Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Konfirmasi password')"
            viewable
        />
        @error('password_confirmation')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="flex items-center justify-between">
            <flux:link :href="route('patient.login')" class="text-sm">
                {{ __('Sudah punya akun?') }}
            </flux:link>
            <flux:button variant="primary" type="submit">
                {{ __('Daftar') }}
            </flux:button>
        </div>
    </form>
</div>
</x-layouts.patient-auth>
