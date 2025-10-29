<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-sm shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex-shrink-0">
                <a href="/" wire:navigate class="text-xl font-bold text-indigo-600 hover:text-indigo-800 transition-colors duration-300">
                    {{ config('app.name', 'Evaluasi Dokumen') }}
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-2">
                <a href="{{ route('home') }}" wire:navigate class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-300 {{ request()->routeIs('home') ? 'text-indigo-600' : 'text-gray-600 hover:text-indigo-600' }}">Home</a>
                <a href="{{ route('tutorial') }}" wire:navigate class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-300 {{ request()->routeIs('tutorial') ? 'text-indigo-600' : 'text-gray-600 hover:text-indigo-600' }}">Tutorial</a>
                <a href="{{ route('guest.form') }}" wire:navigate class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-300 {{ request()->routeIs('guest.track') ? 'text-indigo-600' : 'text-gray-600 hover:text-indigo-600' }}">Cek Cepat</a>
            </div>

            <div class="hidden md:flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors duration-300">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-indigo-700 transition-transform duration-300 transform hover:scale-105">
                    Daftar
                </a>
            </div>

            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-transition class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('home') }}" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">Home</a>
            <a href="{{ route('tutorial') }}" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('tutorial') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">Tutorial</a>
            <a href="{{ route('guest.form') }}" wire:navigate class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('guest.track') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">Cek Cepat</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-2 space-y-1">
                 <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900">Masuk</a>
                 <a href="{{ route('register') }}" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100">Daftar</a>
            </div>
        </div>
    </div>
</nav>