@props(['disabled' => false, 'withIcon' => false])

<div class="relative">
    @if ($withIcon)
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            {{ $icon }}
        </div>
    @endif

    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
        'class' => 'block w-full rounded-lg border-gray-300 bg-gray-50 py-3 shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 ' . ($withIcon ? 'pl-10' : 'px-4')
    ]) !!}>
</div>