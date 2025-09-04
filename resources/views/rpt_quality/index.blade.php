@extends('layouts.app')

@section('page_title', 'Report Quality')

@section('content')

    <div class="bg-white p-6 rounded shadow-md">
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center space-x-3 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2l4 -4M12 20h8a2 2 0 0 0 2-2V8l-6-6H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h4z" />
                    </svg>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Quality</h2>
                        <div class="text-sm text-gray-600 mt-1">
                            <span class="font-medium text-gray-700">Logsheet Code:</span>
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">
                                F-RFA-001
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('report-quality.export.excel', ['filter_tanggal' => $tanggal]) }}" target="_blank"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('report-quality.export.view', [
                    'filter_tanggal' => $tanggal,
                    'filter_work_center' => request('filter_work_center'),
                ]) }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />
                    </svg>
                    View Layout
                </a>


                <div class="flex flex-col sm:flex-row gap-2">
                    {{-- Tombol Download --}}
                    <a href="{{ route('report-quality.export.pdf', [
                        'filter_tanggal' => $tanggal,
                        'filter_work_center' => request('filter_work_center'),
                        'mode' => 'preview',
                    ]) }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow transition"
                        target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 10l5 5 5-5M12 4v12" />
                        </svg>
                        Download PDF
                    </a>
                </div>

            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-gray-50 p-4 rounded-md shadow-sm mb-6">
            <form method="GET" action="{{ route('report-quality.index') }}" class="flex flex-wrap items-end gap-4">
                <div class="w-full sm:w-44">
                    <label for="filter_tanggal" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" id="filter_tanggal" name="filter_tanggal" value="{{ $tanggal }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                </div>

                <div class="w-full sm:w-32">
                    <label for="filter_jam" class="block text-sm font-medium text-gray-700">Time</label>
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

                <div class="w-full sm:w-48">
                    <label for="filter_work_center" class="block text-sm font-medium text-gray-700">Work Center</label>
                    <select id="filter_work_center" name="filter_work_center"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="">Pilih Work Center</option>
                        @foreach ($workCenters as $wc)
                            <option value="{{ $wc->work_center }}"
                                {{ request('filter_work_center') == $wc->work_center ? 'selected' : '' }}>
                                {{ $wc->work_center }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol Filter --}}
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-lg shadow transition">
                        Filter
                    </button>

                    @if (request()->has('filter_tanggal') || request()->has('filter_jam') || request()->has('filter_work_center'))
                        <a href="{{ route('report-quality.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-semibold rounded-lg shadow transition">
                            Reset
                        </a>
                    @endif
                </div>
                @if (empty(request('filter_work_center')))
                    <div class="mb-4 p-3 text-sm text-blue-800 bg-blue-100 border border-blue-200 rounded-lg">
                        <strong>Info:</strong> Jika tidak memilih <em>Work Center</em>, sistem akan menampilkan
                        <span class="font-semibold">semua work center</span> dalam laporan.
                    </div>
                @endif
                {{-- @if (empty(request('filter_work_center')))
                    <p class="text-xs text-gray-500 mb-3">
                        *Jika tidak memilih work center, laporan akan menampilkan semua work center
                    </p>
                @endif --}}

            </form>
        </div>

        <div x-data="{ openRejectModal: false }">
            <div class="flex gap-2 mb-4">
                <form action="{{ route('report-quality.approve-date') }}" method="POST">
                    @csrf
                    <input type="hidden" name="posting_date" value="{{ $tanggal }}">
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg">
                        Approve Hari Ini
                    </button>
                </form>

                <!-- Tombol Reject -->
                <button type="button" @click="openRejectModal = true"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg">
                    Reject Hari Ini
                </button>
            </div>

            <!-- Modal Reject -->
            <div x-show="openRejectModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                x-cloak>
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Reject Laporan</h2>

                    <form action="{{ route('report-quality.reject-date') }}" method="POST">
                        @csrf
                        <input type="hidden" name="posting_date" value="{{ $tanggal }}">

                        <div class="mb-4">
                            <label for="remark" class="block text-sm font-medium text-gray-700">Alasan Reject</label>
                            <textarea id="remark" name="remark" rows="3" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"></textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" @click="openRejectModal = false"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-gray-700">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- Status Shift --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div
                class="p-4 rounded-lg shadow text-center
        {{ $shiftStatuses['shift1'] == 'Approved Semua' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                <h3 class="font-semibold">Shift 1 (08:00 - 15:59)</h3>
                <p class="text-sm">{{ $shiftStatuses['shift1'] }}</p>
            </div>

            <div
                class="p-4 rounded-lg shadow text-center
        {{ $shiftStatuses['shift2'] == 'Approved Semua' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                <h3 class="font-semibold">Shift 2 (16:00 - 23:59)</h3>
                <p class="text-sm">{{ $shiftStatuses['shift2'] }}</p>
            </div>

            <div
                class="p-4 rounded-lg shadow text-center
        {{ $shiftStatuses['shift3'] == 'Approved Semua' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                <h3 class="font-semibold">Shift 3 (00:00 - 07:59)</h3>
                <p class="text-sm">{{ $shiftStatuses['shift3'] }}</p>
            </div>
        </div>


        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700 text-sm sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 border-b text-left">No</th>
                        <th class="px-4 py-2 border-b text-left">Ticket No</th>
                        <th class="px-4 py-2 border-b text-left">Date</th>
                        <th class="px-4 py-2 border-b text-left">Time</th>
                        <th class="px-4 py-2 border-b text-left">Shift</th>
                        <th class="px-4 py-2 border-b text-left">Refinery</th>
                        <th class="px-4 py-2 border-b text-left">Oil Type</th>
                        <th class="px-4 py-2 border-b text-left">Tank Source</th>
                        <th class="px-4 py-2 border-b text-left">Entry By</th>
                        <th class="px-4 py-2 border-b text-center">Status</th>
                        <th class="px-4 py-2 border-b text-left">Detail</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse ($reports as $index => $report)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">
                            <td class="px-4 py-2 border-b">{{ $reports->firstItem() + $index }}</td>
                            <td class="px-4 py-2 border-b">{{ $report->id }}</td>
                            <td class="px-4 py-2 border-b">
                                {{ \Carbon\Carbon::parse($report->transaction_date)->format('y-m-d') }}</td>
                            <td class="px-4 py-2 border-b">
                                {{ \Carbon\Carbon::parse($report->time)->format('H.i') }}
                            </td>
                            <td class="px-4 py-2 border-b"> {{ $report->shift }}</td>
                            <td class="px-4 py-2 border-b">
                                <span
                                    class="inline-block px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                    {{ $report->work_center }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border-b">{{ $report->oil_type }}</td>
                            <td class="px-4 py-2 border-b">{{ $report->rm_tank_source }}</td>
                            <td class="px-4 py-2 border-b">{{ $report->entry_by }} </td>
                            <td class="px-4 py-2 border-b text-center">
                                @if ($report->checked_status == 'Approved')
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Approved</span>
                                @elseif ($report->checked_status == 'Rejected')
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Rejected</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border-b text-center">
                                <a href="{{ route('report-quality.show', $report->id) }}"
                                    class="text-blue-600 hover:text-blue-800 inline-flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                        class="w-5 h-5 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                        <path fill="currentColor"
                                            d="M256 512a256 256 0 1 0 0-512 256 256 0 1 0 0 512zM224 160a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm-8 64l48 0c13.3 0 24 10.7 24 24l0 88 8 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-80 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l24 0 0-64-24 0c-13.3 0-24-10.7-24-24s10.7-24 24-24z" />
                                    </svg>
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-4 text-center text-gray-500 text-sm">
                                Data tidak tersedia untuk tanggal dan jam yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Custom Pagination --}}
        @if ($reports->count())
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm text-gray-600">
                <div class="mb-2 sm:mb-0">
                    Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} entries
                </div>

                <div class="flex items-center space-x-2">
                    {{-- Previous --}}
                    @if ($reports->onFirstPage())
                        <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $reports->previousPageUrl() }}"
                            class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100">Previous</a>
                    @endif

                    {{-- Page Info --}}
                    <span class="px-3 py-1">
                        Page {{ $reports->currentPage() }} of {{ $reports->lastPage() }}
                    </span>

                    {{-- Next --}}
                    @if ($reports->hasMorePages())
                        <a href="{{ $reports->nextPageUrl() }}"
                            class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100">Next</a>
                    @else
                        <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
        @endif
    </div>



@endsection
