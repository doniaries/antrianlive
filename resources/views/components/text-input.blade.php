@props(['id' => null, 'name' => null, 'type' => 'text', 'value' => '', 'error' => null, 'required' => false, 'autofocus' => false])

<input
    @if($id) id="{{ $id }}" @endif
    @if($name) name="{{ $name }}" @endif
    type="{{ $type }}"
    value="{{ $value }}"
    {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full']) }}
    @if($required) required @endif
    @if($autofocus) autofocus @endif
>

@if($error)
    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
@endif
