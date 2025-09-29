@props(['title' => null])

<x-layouts.app.sidebar :title="$title">
    <flux:main>
        @if(isset($slot))
            {{ $slot }}
        @else
            {{ $content ?? '' }}
        @endif
    </flux:main>
</x-layouts.app.sidebar>
