<header class="bg-white border-b shadow px-6 py-4 flex items-center justify-between" x-data="{ open: false, showConfirm: false }" x-cloak>
    {{-- === Kiri: Sidebar Toggle & Judul === --}}
    <div class="flex items-center space-x-4">
        <!-- Tombol Toggle Sidebar -->
        <button @click="$store.sidebar.toggle()" class="text-gray-600 hover:text-gray-800 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Judul Halaman -->
        <h2 class="text-xl md:text-2xl font-bold text-gray-800 tracking-tight">
            @yield('title', 'Dashboard')
        </h2>
    </div>

    {{-- === Kanan: Notifikasi & User Dropdown === --}}
    <div class="flex items-center space-x-6">
        <!-- Notifikasi Bell -->
        <button class="relative text-gray-500 hover:text-gray-700 transition duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                         a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341
                         C8.67 6.165 8 7.388 8 8.75v5.408
                         c0 .538-.214 1.055-.595 1.437L6 17h9z" />
            </svg>
            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full animate-ping"></span>
            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full"></span>
        </button>

        <!-- User Dropdown -->
        <!-- User Dropdown -->
        <div class="relative">
            <button @click="open = !open"
                class="flex items-center space-x-2 hover:bg-gray-100 px-2 py-1 rounded transition">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->display_name) }}"
                    class="w-8 h-8 rounded-full" alt="User Avatar">
                <div class="flex flex-col text-left">
                    <span class="text-gray-700 font-medium">
                        {{ Auth::user()->display_name }}
                    </span>
                    <span class="text-xs text-gray-500">
                        {{ Auth::user()->business_unit ?? '-' }}
                    </span>
                </div>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95" @click.away="open = false"
                class="absolute right-0 mt-2 w-48 bg-white rounded shadow-md border z-30">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                <button @click="showConfirm = true; open = false"
                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Logout
                </button>
            </div>
        </div>

    </div>

    {{-- === Modal Konfirmasi Logout === --}}
    <div x-show="showConfirm" x-transition
        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50" style="display: none;">
        <div class="bg-white p-6 rounded shadow-lg max-w-sm w-full">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Logout</h2>
            <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin logout?</p>
            <div class="flex justify-end space-x-2">
                <button @click="showConfirm = false"
                    class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
