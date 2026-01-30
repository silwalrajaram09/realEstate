@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full px-4 py-2 text-left
       bg-indigo-50 dark:bg-indigo-900
       text-indigo-700 dark:text-indigo-300
       font-semibold'
    : 'block w-full px-4 py-2 text-left
       text-gray-600 dark:text-gray-400
       hover:bg-gray-100 dark:hover:bg-gray-700
       hover:text-gray-900 dark:hover:text-gray-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
