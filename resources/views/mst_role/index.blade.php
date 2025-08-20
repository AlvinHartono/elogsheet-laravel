@extends('layouts.app')

@section('title', 'Master Roles')

@section('content')
    <div x-data="{ open: false, deleteUrl: '' }" class="bg-white p-4 md:p-6 rounded shadow-md">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                <h3 class="text-xl md:text-2xl font-bold text-gray-700">Roles</h3>
            </div>
            <a href="{{ route('master-role.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow self-start md:self-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah
            </a>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter --}}
        <form method="GET" action="{{ route('master-role.index') }}" class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-end sm:gap-2 gap-4">
                {{-- Input Kode --}}
                <div class="flex-shrink-0">
                    <label for="kode" class="block text-sm font-medium text-gray-700">Filter Kode</label>
                    <input type="text" id="kode" name="kode" value="{{ request('kode') }}"
                        class="mt-1 block w-48 max-w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                        placeholder="Contoh: EO">
                </div>

                {{-- Tombol Aksi (Cari + Reset) --}}
                <div class="flex items-end space-x-2 mt-1 sm:mt-6">
                    {{-- Tombol Cari --}}
                    <button type="submit"
                        class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 text-sm shadow">
                        Cari
                    </button>

                    {{-- Tombol Reset (jika filter aktif) --}}
                    @if (request('kode'))
                        <a href="{{ route('master-role.index') }}"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 text-sm shadow">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto rounded shadow">
            <table class="min-w-max w-full bg-white border border-gray-200 text-sm text-gray-700">
                <thead class="bg-gray-100 text-center uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border text-left">Kode</th>
                        <th class="px-4 py-2 border text-left">Role</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roleUsers as $index => $role)
                        <tr class="hover:bg-gray-50 text-center">
                            <td class="px-4 py-2 border">{{ $index + $roleUsers->firstItem() }}</td>
                            <td class="px-4 py-2 border text-left">{{ $role->role_code }}</td>
                            <td class="px-4 py-2 border text-left">{{ $role->role_name }}</td>
                            <td class="px-4 py-2 border text-center">
                                <div class="flex items-center justify-center gap-x-3">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('master-role.edit', $role->role_code) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 transition"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 3.487a2.062 2.062 0 112.915 2.914L7.5 18.678 3 21l2.322-4.572 11.54-12.94z" />
                                        </svg>
                                    </a>
                                    <!-- Tombol Delete -->
                                    <button type="button"
                                        @click="open = true; deleteUrl = '{{ route('master-role.destroy', $role->role_code) }}'"
                                        class="w-8 h-8 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 transition"
                                        title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 7h12M9 7V4h6v3M10 11v6m4-6v6m1 6H9a2 2 0 01-2-2V7h10v14a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Custom Pagination --}}
        @if ($roleUsers->count())
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm text-gray-600">
                <div class="mb-2 sm:mb-0">
                    Showing {{ $roleUsers->firstItem() }} to {{ $roleUsers->lastItem() }} of
                    {{ $roleUsers->total() }} entries
                </div>

                <div class="flex items-center space-x-2">
                    {{-- Previous --}}
                    @if ($roleUsers->onFirstPage())
                        <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $roleUsers->previousPageUrl() }}"
                            class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100">Previous</a>
                    @endif

                    {{-- Page Info --}}
                    <span class="px-3 py-1">
                        Page {{ $roleUsers->currentPage() }} of {{ $roleUsers->lastPage() }}
                    </span>

                    {{-- Next --}}
                    @if ($roleUsers->hasMorePages())
                        <a href="{{ $roleUsers->nextPageUrl() }}"
                            class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100">Next</a>
                    @else
                        <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
        @endif

        <!-- Modal Konfirmasi -->
        <div x-show="open" x-transition x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg p-6 shadow-lg w-full max-w-sm">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Hapus</h2>
                <p class="text-gray-600 mb-6">Yakin ingin menghapus data ini?</p>
                <div class="flex justify-end gap-3">
                    <button @click="open = false"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Batal</button>

                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
