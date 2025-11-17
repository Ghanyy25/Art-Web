@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block px-6 py-3 text-sm font-medium text-gray-900 bg-gray-100 border-l-4 border-indigo-500' // Aktif
            : 'block px-6 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50'; // Normal
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
