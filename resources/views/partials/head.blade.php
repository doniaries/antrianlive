<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

@php
    $profil = \App\Models\Profil::first();
@endphp
@if($profil && $profil->favicon)
    <link rel="icon" href="{{ asset('storage/' . $profil->favicon) }}" sizes="any">
    <link rel="shortcut icon" href="{{ asset('storage/' . $profil->favicon) }}" type="image/x-icon">
@else
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
@endif
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="{{ asset('js/preline-fix.js') }}"></script>
@fluxAppearance
