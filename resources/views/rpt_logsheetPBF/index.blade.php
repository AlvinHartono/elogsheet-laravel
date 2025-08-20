@php
    $logs = collect([
        (object) [
            'time' => '08:00',
            'tph' => 5,
            'inlet_temp' => 46,
            'h3po4' => '0.06',
            'be' => '0.6',
            'vacuum' => '-680',
            'bleacher_temp1' => 110,
            'bleacher_temp2' => 105,
            'pump_a' => 2,
            'pump_b' => 4.5,
            'pump_c' => 2,
            'f601' => 1.8,
            'f603' => 0.1,
            'filter_bag' => '0.1',
            'clarity' => 'clear',
            'remarks' => 'Cek comic bawah',
        ],
        (object) [
            'time' => '16:00',
            'tph' => 5,
            'inlet_temp' => 46,
            'h3po4' => '0.06',
            'be' => '0.6',
            'vacuum' => '-680',
            'bleacher_temp1' => 110,
            'bleacher_temp2' => 105,
            'pump_a' => 2,
            'pump_b' => 4.5,
            'pump_c' => 2,
            'f601' => 1.8,
            'f603' => 0.1,
            'filter_bag' => '0.1',
            'clarity' => 'clear',
            'remarks' => '',
        ],
        (object) [
            'time' => '18:00',
            'tph' => 3,
            'inlet_temp' => 46,
            'h3po4' => '0.06',
            'be' => '0.6',
            'vacuum' => '-680',
            'bleacher_temp1' => 110,
            'bleacher_temp2' => 105,
            'pump_a' => 2,
            'pump_b' => 4.5,
            'pump_c' => 2,
            'f601' => 2.0,
            'f603' => 0.8,
            'filter_bag' => '0.8',
            'clarity' => 'mt/soal',
            'remarks' => 'Ganti 5: IRS EXHAUSTER',
        ],
    ]);
@endphp

@extends('layouts.app')

@section('title', 'Laporan Pretreatment, Bleaching and Filtration')

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center space-x-3 mb-1">
                    <!-- Ikon Filter -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16l-6 8v5l-4 3v-8z" />
                    </svg>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Pretreatment, Bleaching and Filtration</h2>
                        <div class="text-sm text-gray-600 mt-1">
                            <span class="font-medium text-gray-700">Kode Logsheet:</span>
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">
                                F/RFA-002
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="#" target="_blank"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('report-logsheetpbf.export.view') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />
                    </svg>
                    View Layout
                </a>
                <a href="#"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 10l5 5 5-5M12 4v12" />
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-gray-50 p-4 rounded-md shadow-sm mb-6">
            <form method="GET" action="" class="flex flex-wrap items-end gap-4">
                <div class="w-full sm:w-44">
                    <label for="filter_tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" id="filter_tanggal" name="filter_tanggal" value="{{ request('filter_tanggal') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                </div>
                <div class="w-full sm:w-32">
                    <label for="filter_jam" class="block text-sm font-medium text-gray-700">Jam</label>
                    <select id="filter_jam" name="filter_jam"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="">Pilih Jam</option>
                        @for ($i = 0; $i < 24; $i++)
                            @php $jam = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                            <option value="{{ $jam }}" {{ request('filter_jam') == $jam ? 'selected' : '' }}>
                                {{ $jam }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-lg shadow transition">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100 text-gray-700 sticky top-0 z-10">
                    <tr>
                        <th class="px-3 py-2 border">Time</th>
                        <th class="px-3 py-2 border">TPH</th>
                        <th class="px-3 py-2 border">Inlet Temp</th>
                        <th class="px-3 py-2 border">H₃PO₄</th>
                        <th class="px-3 py-2 border">BE</th>
                        <th class="px-3 py-2 border">Vacuum</th>
                        <th class="px-3 py-2 border">Blech T₁</th>
                        <th class="px-3 py-2 border">Blech T₂</th>
                        <th class="px-3 py-2 border">Pump A</th>
                        <th class="px-3 py-2 border">Pump B</th>
                        <th class="px-3 py-2 border">Pump C</th>
                        <th class="px-3 py-2 border">F601</th>
                        <th class="px-3 py-2 border">F603</th>
                        <th class="px-3 py-2 border">Filter Bag</th>
                        <th class="px-3 py-2 border">Clarity</th>
                        <th class="px-3 py-2 border">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">
                            <td class="px-3 py-2 border">{{ $log->time }}</td>
                            <td class="px-3 py-2 border">{{ $log->tph }}</td>
                            <td class="px-3 py-2 border">{{ $log->inlet_temp }}</td>
                            <td class="px-3 py-2 border">{{ $log->h3po4 }}</td>
                            <td class="px-3 py-2 border">{{ $log->be }}</td>
                            <td class="px-3 py-2 border">{{ $log->vacuum }}</td>
                            <td class="px-3 py-2 border">{{ $log->bleacher_temp1 }}</td>
                            <td class="px-3 py-2 border">{{ $log->bleacher_temp2 }}</td>
                            <td class="px-3 py-2 border">{{ $log->pump_a }}</td>
                            <td class="px-3 py-2 border">{{ $log->pump_b }}</td>
                            <td class="px-3 py-2 border">{{ $log->pump_c }}</td>
                            <td class="px-3 py-2 border">{{ $log->f601 }}</td>
                            <td class="px-3 py-2 border">{{ $log->f603 }}</td>
                            <td class="px-3 py-2 border">{{ $log->filter_bag }}</td>
                            <td class="px-3 py-2 border">{{ $log->clarity }}</td>
                            <td class="px-3 py-2 border">{{ $log->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer info --}}
        <div class="mt-6 text-sm text-gray-500 italic">
            Menampilkan {{ $logs->count() }} data dummy.
        </div>
    </div>
@endsection
