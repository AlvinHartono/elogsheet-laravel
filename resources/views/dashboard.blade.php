@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-5 right-5 bg-green-100 text-green-800 px-4 py-2 rounded shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-700 uppercase">Dashboard</h2>
        <p class="text-sm text-gray-500">Ringkasan laporan dan status terkini</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Total Produksi --}}
        <div class="bg-white rounded-2xl shadow p-6 flex items-center space-x-4 hover:shadow-md transition">
            <div class="p-4 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7m-9 9v-6h4v6" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Produksi</p>
                <p class="text-xl font-semibold text-gray-800">1.250 Ton</p>
            </div>
        </div>

        {{-- Approved Reports --}}
        <div class="bg-white rounded-2xl shadow p-6 flex items-center space-x-4 hover:shadow-md transition">
            <div class="p-4 rounded-full bg-green-100 text-green-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Approved Reports</p>
                <p class="text-xl font-semibold text-gray-800">87%</p>
            </div>
        </div>

        {{-- Issues Found --}}
        <div class="bg-white rounded-2xl shadow p-6 flex items-center space-x-4 hover:shadow-md transition">
            <div class="p-4 rounded-full bg-red-100 text-red-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18.364 5.636L5.636 18.364M5.636 5.636l12.728 12.728" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Issues Found</p>
                <p class="text-xl font-semibold text-gray-800">3 Alerts</p>
            </div>
        </div>
    </div>
@endsection
