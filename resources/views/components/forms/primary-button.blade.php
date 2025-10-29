<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'inline-flex items-center justify-center gap-2 rounded-lg border border-transparent bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-150 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:scale-95 disabled:pointer-events-none disabled:opacity-50'
]) }}>
    {{ $slot }}
</button>