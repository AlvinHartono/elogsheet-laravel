@extends('layouts.app')

@section('title', 'Mastervalue')

@section('content')
    <div class="bg-white p-6 rounded-2xl shadow max-w-xl mx-auto">
        <div class="flex items-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-800">Edit Mastervalue</h3>
        </div>

        <form method="POST" action="{{ route('master-value.update', $value->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Code --}}
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M2 5a2 2 0 012-2h4.586a1 1 0 01.707.293l1.414 1.414A1 1 0 0111.414 5H16a2 2 0 012 2v1H2V5z" />
                            <path fill-rule="evenodd"
                                d="M2 9h16v6a2 2 0 01-2 2H4a2 2 0 01-2-2V9zm4 3a1 1 0 112 0 1 1 0 01-2 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="bu_code" id="bu_code"
                        class="form-input w-full pl-10 border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ $value->code }}">
                </div>
            </div>

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path d="M10 2a2 2 0 00-2 2v1H6a2 2 0 00-2 2v1h12V7a2 2 0 00-2-2h-2V4a2 2 0 00-2-2z" />
                            <path d="M4 10h12v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6z" />
                        </svg>
                    </div>
                    <input type="text" name="name" id="name"
                        class="form-input w-full pl-10 border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('name', $value->name) }}" required>
                </div>
            </div>

            {{-- Group --}}
            <div>
                <label for="group" class="block text-sm font-medium text-gray-700">Group</label>
                <select name="group" id="group"
                    class="form-select w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                    onchange="toggleNewGroupInput()">
                    <option value="">-- Pilih Group --</option>

                    @foreach ($groups as $group)
                        <option value="{{ $group }}" {{ old('group', $value->group) == $group ? 'selected' : '' }}>
                            {{ $group }}
                        </option>
                    @endforeach

                    {{-- Tambah opsi khusus jika group lama tidak ada di daftar --}}
                    @if (!in_array($value->group, $groups->toArray()))
                        <option value="{{ $value->group }}" selected>{{ $value->group }}</option>
                    @endif

                    <option value="__new__" {{ old('group') == '__new__' ? 'selected' : '' }}>+ Tambah Group Baru...
                    </option>
                </select>
            </div>

            {{-- Input Group Baru --}}
            <div id="newGroupContainer" style="display: none;" class="mt-2">
                <input type="text" name="new_group" id="new_group"
                    class="form-input w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Masukkan nama group baru" value="{{ old('new_group') }}">
            </div>

            {{-- Status Aktif --}}
            <div>
                <label for="isactive" class="block text-sm font-medium text-gray-700">Status Aktif</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3-11a1 1 0 00-1-1H8a1 1 0 000 2h4a1 1 0 001-1z" />
                        </svg>
                    </div>
                    <select name="isactive" id="isactive"
                        class="form-select w-full pl-10 border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="T" {{ old('isactive', $value->isactive) == 'T' ? 'selected' : '' }}>Aktif
                        </option>
                        <option value="F" {{ old('isactive', $value->isactive) == 'F' ? 'selected' : '' }}>Tidak Aktif
                        </option>
                    </select>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('master-value.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleNewGroupInput() {
            const groupSelect = document.getElementById('group');
            const newGroupContainer = document.getElementById('newGroupContainer');
            if (groupSelect.value === '__new__') {
                newGroupContainer.style.display = 'block';
            } else {
                newGroupContainer.style.display = 'none';
            }
        }

        // Jalankan saat halaman load, supaya kalau sudah pilih "__new__", inputnya muncul
        document.addEventListener('DOMContentLoaded', toggleNewGroupInput);
    </script>
@endsection
