@extends('layouts.app')

@section('title', 'Master Roles')

@section('content')

    <div class="bg-white p-6 rounded-2xl shadow max-w-xl mx-auto">
        <div class="flex items-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14m-7-7h14" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-800">Edit Role</h3>
        </div>

        <form method="POST" action="{{ route('master-role.update', $role->role_code) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="role_code" class="block text-sm font-medium text-gray-700">Kode</label>
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
                    <input type="text" name="role_code" id="role_code"
                        class="form-input w-full pl-10 border-gray-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-700 shadow-sm"
                        value="{{ $role->role_code }}" readonly>
                </div>
            </div>

            <div>
                <label for="role_name" class="block text-sm font-medium text-gray-700">Role Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path d="M10 2a2 2 0 00-2 2v1H6a2 2 0 00-2 2v1h12V7a2 2 0 00-2-2h-2V4a2 2 0 00-2-2z" />
                            <path d="M4 10h12v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6z" />
                        </svg>
                    </div>
                    <input type="text" name="role_name" id="role_name"
                        class="form-input w-full pl-10 border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                        placeholder="Contoh: Business Unit A" value="{{ $role->role_name }}" required>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('master-role.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Batal
                </a>

                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Update
                </button>
            </div>
    </div>
@endsection
