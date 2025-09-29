<div class="flex-shrink-0 flex items-center">
    @php
        $profil = \App\Models\Profil::first();
        $logoUrl = $profil && $profil->logo ? asset('storage/' . $profil->logo) : asset('images/logo-placeholder.png');
    @endphp
    
    <a href="{{ url('/') }}">
        <img class="h-8 w-auto" src="{{ $logoUrl }}" alt="{{ config('app.name', 'Laravel') }}">
    </a>
</div>
