@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => '
            w-full
            px-3 py-2
            rounded-xl
            border border-gray-300
            bg-white
            text-gray-800
            placeholder-gray-400

            focus:outline-none
            focus:border-indigo-500
            focus:ring-2
            focus:ring-indigo-500/20

            transition
            duration-200
            ease-in-out

            hover:border-indigo-400

            disabled:opacity-60
            disabled:cursor-not-allowed
        '
    ]) !!}
>
