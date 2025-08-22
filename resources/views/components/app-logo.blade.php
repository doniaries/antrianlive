@php
    $profil = \App\Models\Profil::first();
    $appName = $profil->nama_instansi ?? config('app.name', 'Laravel');
    $logoUrl = $profil ? $profil->logo_url ?? null : null;
@endphp

@if ($logoUrl)
    <div class="flex-shrink-0">

        <img src="{{ $logoUrl }}" alt="{{ $appName }}" class="h-8 w-auto">
    </div>
@else
    <div
        class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
        <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
    </div>
@endif
<div class="ms-1 grid flex-1 text-start text-sm">
    <span class="mb-0.5 truncate leading-tight font-semibold">{{ config('app.name', 'Antrian') }}</span>
    <span class="mb-0.5 truncate leading-tight font-semibold">{{ $profil->nama_instansi ?? '' }}</span>
</div>
