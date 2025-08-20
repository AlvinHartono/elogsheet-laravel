<aside
    class="z-30 w-64 bg-white border-r shadow-md h-screen fixed md:relative transition-transform duration-300 ease-in-out"
    :class="{ '-translate-x-full': !$store.sidebar.open, 'translate-x-0': $store.sidebar.open }" x-cloak
    x-data="{
        openMaster: {{ request()->routeIs('master-*') || request()->routeIs('business-unit.*') || request()->routeIs('master-plant.*') ? 'true' : 'false' }},
        openReport: {{ request()->routeIs('report-quality.*') || request()->routeIs('report-lampnglass.*') ? 'true' : 'false' }},
    }">
    <div class="h-full flex flex-col">
        <!-- Header -->
        <div class="p-5 border-b flex justify-between items-center">
            <h1 class="text-lg font-bold text-gray-800">{{ config('app.name') }}</h1>
            <button @click="$store.sidebar.toggle()" class="md:hidden text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Menu -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2 text-sm text-gray-700">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-3 py-2 rounded transition
               {{ request()->routeIs('dashboard') ? 'bg-gray-200 font-semibold text-blue-600' : 'hover:bg-gray-100' }}">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7m-9 9v-6h4v6m5-6h3m-3 0V6a2 2 0 00-2-2h-2.5" />
                </svg>
                Dashboard
            </a>

            <!-- Master -->
            <div>
                <button @click="openMaster = !openMaster"
                    class="flex items-center justify-between w-full px-3 py-2 rounded transition
                        {{ request()->routeIs('business-unit.*') ||
                        request()->routeIs('master-plant.*') ||
                        request()->routeIs('master-value.*')
                            ? 'bg-gray-100 text-blue-600 font-semibold'
                            : 'hover:bg-gray-100' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h4l2 3h10a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                        </svg>
                        Master
                    </div>
                    <svg :class="{ 'rotate-90': openMaster }"
                        class="w-4 h-4 text-gray-500 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div x-show="openMaster" x-collapse x-cloak class="mt-1 ml-6 space-y-1 border-l pl-4">
                    <a href="{{ route('business-unit.index') }}"
                        class="block px-2 py-1 rounded transition
                       {{ request()->routeIs('business-unit.*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-gray-100' }}">
                        • Business Unit
                    </a>
                    <a href="{{ route('master-plant.index') }}"
                        class="block px-2 py-1 rounded transition
                       {{ request()->routeIs('master-plant.*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-gray-100' }}">
                        • Plant
                    </a>
                    <a href="{{ route('master-value.index') }}"
                        class="block px-2 py-1 rounded transition
                       {{ request()->routeIs('master-value.*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-gray-100' }}">
                        • Value
                    </a>
                    <a href="{{ route('master-role.index') }}"
                        class="block px-2 py-1 rounded transition
                        {{ request()->routeIs('master-role.*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-gray-100' }}">
                        • Role
                    </a>
                </div>
            </div>

            <!-- Users -->
            <a href="{{ route('user.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded transition
               {{ request()->routeIs('user.*') ? 'bg-gray-200 font-semibold text-blue-600' : 'hover:bg-gray-100' }}">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m3-4a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
                Users
            </a>

            <!-- Report (with submenu) -->
            <div>
                <button @click="openReport = !openReport"
                    class="flex items-center justify-between w-full px-3 py-2 rounded transition
            {{ request()->routeIs('report-quality.*') || request()->routeIs('report-lampnglass.*')
                ? 'bg-gray-100 text-blue-600 font-semibold'
                : 'hover:bg-gray-100' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 17l-3-3m0 0l-3 3m3-3v6m6-10l3-3m0 0l3 3m-3-3v12" />
                        </svg>
                        Report
                    </div>
                    <svg :class="{ 'rotate-90': openReport }"
                        class="w-4 h-4 text-gray-500 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Submenu Report (gabung jadi satu) -->
                <div x-show="openReport" x-collapse x-cloak class="mt-1 ml-6 space-y-1 border-l pl-4">
                    <a href="{{ route('report-quality.index') }}" title="Quality Report"
                        class="block px-2 py-1 rounded transition text-sm
            {{ request()->routeIs('report-quality.*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-gray-100' }}">
                        • [F/RFA-001] Quality
                    </a>
                    <a href="{{ route('report-lampnglass.index') }}" title="Checklist Lamp & Glass"
                        class="block px-2 py-1 rounded transition text-sm
            {{ request()->routeIs('report-lampnglass.*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-gray-100' }}">
                        • [F/RFA-013] Checklist Lamp & Glass
                    </a>
                </div>
            </div>

        </nav>
    </div>
</aside>
