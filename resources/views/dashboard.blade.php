@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')

    {{-- Notifikasi --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-5 right-5 bg-green-100 text-green-800 px-4 py-2 rounded shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-700 uppercase">Dashboard</h2>
        <p class="text-sm text-gray-500">Ringkasan laporan dan status terkini</p>
    </div>

    {{-- Ringkasan KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        {{-- Total Produksi --}}
        <div class="bg-white rounded-2xl p-6 flex items-center space-x-4">
            <div class="p-4 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7m-9 9v-6h4v6" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Produksi</p>
                <p class="text-xl font-semibold text-gray-800">{{ number_format($totalProduksi ?? 0) }} Ton</p>
            </div>
        </div>

        {{-- Approved Reports --}}
        <div class="bg-white rounded-2xl p-6 flex items-center space-x-4">
            <div class="p-4 rounded-full bg-green-100 text-green-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Approved Reports</p>
                <p class="text-xl font-semibold text-gray-800">{{ $percentApproved ?? 0 }}%</p>
            </div>
        </div>

        {{-- Issues Found --}}
        <div class="bg-white rounded-2xl p-6 flex items-center space-x-4">
            <div class="p-4 rounded-full bg-red-100 text-red-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18.364 5.636L5.636 18.364M5.636 5.636l12.728 12.728" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Issues Found</p>
                <p class="text-xl font-semibold text-gray-800">{{ $issuesCount ?? 0 }}</p>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white rounded-2xl p-6 mb-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Recent Activity</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-3 py-2 border">Date</th>
                        <th class="px-3 py-2 border">Checker</th>
                        <th class="px-3 py-2 border">Status</th>
                        <th class="px-3 py-2 border">Remark</th>
                        <th class="px-3 py-2 border">Source</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentReports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border">{{ $report['checked_date'] }}</td>
                            <td class="px-3 py-2 border">{{ $report['checked_by'] }}</td>
                            <td class="px-3 py-2 border">
                                @if ($report['checked_status'] === 'Approved')
                                    <span class="text-green-600 font-semibold">{{ $report['checked_status'] }}</span>
                                @elseif ($report['checked_status'] === 'Rejected')
                                    <span class="text-red-600 font-semibold">{{ $report['checked_status'] }}</span>
                                @else
                                    <span class="text-gray-500">Pending</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 border">{{ $report['checked_status_remarks'] }}</td>
                            <td class="px-3 py-2 border">
                                @if ($report['source'] === 'QualityReport')
                                    <span class="text-blue-600 font-semibold">Quality</span>
                                @else
                                    <span class="text-purple-600 font-semibold">LampGlass</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- Summary Reports by Source --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-4 flex flex-col">
            <div class="text-gray-500 text-sm">Quality Reports</div>
            <div class="text-xl font-bold text-gray-800 mt-2">
                {{ $summaryQuality['approved'] ?? 0 }}/{{ $summaryQuality['total'] ?? 0 }} Approved</div>
            <div class="w-full bg-gray-200 h-2 rounded mt-2">
                <div class="bg-blue-500 h-2 rounded"
                    style="width: {{ ($summaryQuality['total'] ?? 0) > 0 ? ($summaryQuality['approved'] / $summaryQuality['total']) * 100 : 0 }}%">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 flex flex-col">
            <div class="text-gray-500 text-sm">LampGlass Reports</div>
            <div class="text-xl font-bold text-gray-800 mt-2">
                {{ $summaryLampGlass['approved'] ?? 0 }}/{{ $summaryLampGlass['total'] ?? 0 }} Approved</div>
            <div class="w-full bg-gray-200 h-2 rounded mt-2">
                <div class="bg-purple-500 h-2 rounded"
                    style="width: {{ ($summaryLampGlass['total'] ?? 0) > 0 ? ($summaryLampGlass['approved'] / $summaryLampGlass['total']) * 100 : 0 }}%">
                </div>
            </div>
        </div>
    </div>

@endsection
