@php
    $reports = collect([
        (object) [
            'id' => 1,
            'report_date' => now()->toDateString(),
            'time' => '08:00',
            'p_cat' => 'Kategori A',
            'p_flowrate' => '120',
            'c_pa' => '95.5',
            'p_tank_source' => 'Tank 01',
            'entry_by' => 'Admin',
            'plant' => 'Plant A',
        ],
        (object) [
            'id' => 2,
            'report_date' => now()->toDateString(),
            'time' => '12:00',
            'p_cat' => 'Kategori B',
            'p_flowrate' => '98',
            'c_pa' => '89.7',
            'p_tank_source' => 'Tank 02',
            'entry_by' => 'Operator',
            'plant' => 'Plant B',
        ],
        (object) [
            'id' => 3,
            'report_date' => now()->toDateString(),
            'time' => '16:00',
            'p_cat' => 'Kategori C',
            'p_flowrate' => '105',
            'c_pa' => '91.2',
            'p_tank_source' => 'Tank 03',
            'entry_by' => 'User QA',
            'plant' => 'Plant C',
        ],
        (object) [
            'id' => 4,
            'report_date' => now()->toDateString(),
            'time' => '08:00',
            'p_cat' => 'Kategori A',
            'p_flowrate' => '120',
            'c_pa' => '95.5',
            'p_tank_source' => 'Tank 01',
            'entry_by' => 'Admin',
            'plant' => 'Plant A',
        ],
    ]);
@endphp

@extends('layouts.app')

@section('title', 'Laporan Daily Production Refinery')

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-6h13M5 11V9a4 4 0 014-4h2a4 4 0 014 4v2a4 4 0 01-4 4H9m-4 0v2a4 4 0 004 4h2a4 4 0 004-4v-2" />
                </svg>
                <h2 class="text-lg font-semibold text-gray-800">Laporan Daily Production</h2>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href=""
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('report-logsheetdfs.export.view') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />
                    </svg>
                    View Layout
                </a>
                <a href=""
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
        <form method="GET" action="" class="flex flex-wrap items-end gap-4 mb-6">
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
            <div class="w-full sm:w-40">
                <label for="filter_plant" class="block text-sm font-medium text-gray-700">Plant</label>
                <select id="filter_plant" name="filter_plant"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Pilih Plant</option>
                    <option value="Plant A" {{ request('filter_plant') == 'Plant A' ? 'selected' : '' }}>Plant A</option>
                    <option value="Plant B" {{ request('filter_plant') == 'Plant B' ? 'selected' : '' }}>Plant B</option>
                    <option value="Plant C" {{ request('filter_plant') == 'Plant C' ? 'selected' : '' }}>Plant C</option>
                </select>
            </div>

            <div>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-lg shadow transition">
                    Filter
                </button>
            </div>
        </form>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            @php
                $reports = $reports->filter(function ($item) {
                    return (!request('filter_plant') || $item->plant === request('filter_plant')) &&
                        (!request('filter_jam') || $item->time === request('filter_jam')) &&
                        (!request('filter_tanggal') || $item->report_date === request('filter_tanggal'));
                });
            @endphp

            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700 text-sm">
                    <tr>
                        <th class="px-4 py-2 border-b text-left">No</th>
                        <th class="px-4 py-2 border-b text-left">Tanggal</th>
                        <th class="px-4 py-2 border-b text-left">Jam</th>
                        <th class="px-4 py-2 border-b text-left">Kategori</th>
                        <th class="px-4 py-2 border-b text-left">Flowrate</th>
                        <th class="px-4 py-2 border-b text-left">Persen Air</th>
                        <th class="px-4 py-2 border-b text-left">Tank Source</th>
                        <th class="px-4 py-2 border-b text-left">Plant</th>
                        <th class="px-4 py-2 border-b text-left">Dientri Oleh</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @foreach ($reports as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border-b">{{ $item->report_date }}</td>
                            <td class="px-4 py-2 border-b">{{ $item->time }}</td>
                            <td class="px-4 py-2 border-b">{{ $item->p_cat }}</td>
                            <td class="px-4 py-2 border-b">{{ $item->p_flowrate }}</td>
                            <td class="px-4 py-2 border-b">{{ $item->c_pa }}</td>
                            <td class="px-4 py-2 border-b">{{ $item->p_tank_source }}</td>
                            <td class="px-4 py-2 border-b">{{ $item->plant }}</td>
                            <td class="px-4 py-2 border-b">{{ $item->entry_by }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-sm text-gray-500 italic">
            Menampilkan {{ $reports->count() }} data dummy.
        </div>
    </div>
@endsection
