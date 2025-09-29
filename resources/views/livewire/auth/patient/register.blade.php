<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <!-- Logo -->
        <div class="flex justify-center">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="mt-6 text-center">
            <h2 class="text-2xl font-semibold text-gray-900">Daftar Akun Pasien</h2>
            <p class="mt-2 text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('patient.login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Masuk disini
                </a>
            </p>
        </div>

        <form wire:submit.prevent="register" class="mt-8 space-y-6">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" />
                <x-text-input 
                    id="name" 
                    type="text" 
                    class="mt-1 block w-full" 
                    wire:model="name" 
                    required 
                    autofocus 
                    autocomplete="name" 
                />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input 
                    id="email" 
                    type="email" 
                    class="mt-1 block w-full" 
                    wire:model="email" 
                    required 
                    autocomplete="username" 
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Phone -->
            <div class="mt-4">
                <x-input-label for="phone" :value="__('Nomor Telepon')" />
                <x-text-input 
                    id="phone" 
                    type="tel" 
                    class="mt-1 block w-full" 
                    wire:model="phone" 
                    required 
                />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- BPJS Number -->
            <div class="mt-4">
                <x-input-label for="bpjs_number" :value="__('Nomor BPJS')" />
                <x-text-input 
                    id="bpjs_number" 
                    type="text" 
                    class="mt-1 block w-full" 
                    wire:model="bpjs_number" 
                    required 
                />
                <x-input-error :messages="$errors->get('bpjs_number')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative">
                    <x-text-input 
                        id="password" 
                        :type="$showPassword ? 'text' : 'password'" 
                        class="mt-1 block w-full pr-10" 
                        wire:model="password" 
                        required 
                        autocomplete="new-password" 
                    />
                    <button 
                        type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                        x-on:click="$wire.togglePasswordVisibility()"
                    >
                        <i class="fas" :class="$wire.showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                <x-text-input 
                    id="password_confirmation" 
                    :type="$showPassword ? 'text' : 'password'" 
                    class="mt-1 block w-full" 
                    wire:model="password_confirmation" 
                    required 
                    autocomplete="new-password" 
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('patient.login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Sudah punya akun?') }}
                </a>

                <x-primary-button class="ml-4">
                    {{ __('Daftar') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
