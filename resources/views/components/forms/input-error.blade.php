@props(['messages'])

@if ($messages)
    <div x-data="{ show: true }" x-show="show" x-transition>
        <ul {{ $attributes->merge(['class' => 'flex items-start gap-1 text-sm text-red-600 space-y-1 mt-2']) }}>
            <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
            </svg>
            <div>
                @foreach ((array) $messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </div>
        </ul>
    </div>
@endif