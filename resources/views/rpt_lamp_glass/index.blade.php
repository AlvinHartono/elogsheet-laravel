@extends('layouts.app')

@section('title', 'Checklist Lamps and Glass Control')

@section('content')

    @php
        use Carbon\Carbon;
        $selectedDate = request('filter_tanggal', Carbon::today()->format('Y-m-d'));
    @endphp



    <div class="bg-white p-6 rounded shadow-md">
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center space-x-3 mb-1">
                    <!-- Lampu -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 2a7 7 0 0 0-7 7c0 2.5 1.5 4.7 3.5 6a3 3 0 0 1 1.5 2.6V20h4v-2.4a3 3 0 0 1 1.5-2.6c2-1.3 3.5-3.5 3.5-6a7 7 0 0 0-7-7z" />
                    </svg>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Lamps and Glass Control</h2>
                        <div class="text-sm text-gray-600 mt-1">
                            <span class="font-medium text-gray-700">Logsheet Code:</span>
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">
                                F-RFA-013
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('report-lampnglass.export', ['filter_tanggal' => $selectedDate]) }}" target="_blank"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('report-lampnglass.export.view') }}"
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
                    <a href="{{ route('report-lampnglass.export.pdf', ['filter_tanggal' => $selectedDate, 'mode' => 'preview']) }}"
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
            <form method="GET" action="{{ route('report-lampnglass.index') }}" class="flex flex-wrap items-end gap-4">
                <div class="w-full sm:w-44">
                    <label for="filter_tanggal_awal" class="block text-sm font-medium text-gray-700">Tanggal Awal</label>
                    <input type="date" id="filter_tanggal_awal" name="filter_tanggal_awal" value="{{ $tanggalAwal }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                </div>
                <div class="w-full sm:w-44">
                    <label for="filter_tanggal_akhir" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                    <input type="date" id="filter_tanggal_akhir" name="filter_tanggal_akhir" value="{{ $tanggalAkhir }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                </div>

                {{-- Tombol Filter --}}
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-lg shadow transition">
                        Filter
                    </button>

                    @if (request()->has('filter_tanggal_awal') || request()->has('filter_tanggal_akhir'))
                        <a href="{{ route('report-lampnglass.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-semibold rounded-lg shadow transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700 text-sm sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 border-b text-left">No</th>
                        <th class="px-4 py-2 border-b text-left">Ticket No</th>
                        <th class="px-4 py-2 border-b text-left">Plant</th>
                        <th class="px-4 py-2 border-b text-left">Unit</th>
                        <th class="px-4 py-2 border-b text-left">Ref</th>
                        <th class="px-4 py-2 border-b text-left">Tanggal</th>
                        <th class="px-4 py-2 border-b text-left">Detail</th>
                        <th class="px-4 py-2 border-b text-center">Status</th>
                        <th class="px-4 py-2 border-b text-center">Action</th> {{-- Tambahan --}}
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse ($documents as $index => $doc)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">
                            <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border-b">{{ $doc->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $doc->plant }}</td>
                            <td class="px-4 py-2 border-b">{{ $doc->work_center }}</td>
                            <td class="px-4 py-2 border-b">{{ $doc->remarks }}</td>
                            <td class="px-4 py-2 border-b">
                                {{ \Carbon\Carbon::parse($doc->check_date)->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2 border-b">
                                <button onclick="toggleDetail({{ $index }})"
                                    class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    <span id="icon-{{ $index }}">▼</span>
                                    {{ $doc->details->count() }} {{ $doc->details->count() > 1 ? 'Items' : 'Item' }}
                                </button>
                            </td>
                            <td class="px-4 py-2 border-b text-center">
                                @if ($doc->checked_status == 'approved')
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Approved</span>
                                @elseif ($doc->checked_status == 'rejected')
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Rejected</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border-b text-center">
                                <div class="flex justify-center gap-2">
                                    <form action="{{ route('lamp_glass.approve', $doc->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 shadow
                       {{ $doc->checked_status ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $doc->checked_status ? 'disabled' : '' }}>
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('lamp_glass.reject', $doc->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 shadow
                       {{ $doc->checked_status ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $doc->checked_status ? 'disabled' : '' }}>
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Detail --}}
                        <tr id="detail-{{ $index }}" class="hidden">
                            <td colspan="8" class="px-6 py-4 bg-gray-50">
                                <div class="border border-gray-200 rounded-lg shadow-sm overflow-hidden w-full max-w-md">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-3 py-2 border-b text-center w-12">No</th>
                                                <th class="px-3 py-2 border-b text-center">Item</th>
                                                <th class="px-3 py-2 border-b text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach ($doc->details as $i => $detail)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-3 py-2 text-center">{{ $i + 1 }}</td>
                                                    <td class="px-3 py-2 text-center">{{ $detail->check_item }}</td>
                                                    <td class="px-3 py-2 text-center">
                                                        @if ($detail->status_item == 'T')
                                                            <span
                                                                class="inline-block px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                                                OK
                                                            </span>
                                                        @elseif ($detail->status_item == 'F')
                                                            <span
                                                                class="inline-block px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                                                                Not OK
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-block px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">
                                                                -
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data untuk tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Custom Pagination --}}
        @if ($documents->count())
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm text-gray-600">
                <div class="mb-2 sm:mb-0">
                    Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }}
                    entries
                </div>

                <div class="flex items-center space-x-2">
                    {{-- Previous --}}
                    @if ($documents->onFirstPage())
                        <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $documents->previousPageUrl() }}"
                            class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100">Previous</a>
                    @endif

                    {{-- Page Info --}}
                    <span class="px-3 py-1">
                        Page {{ $documents->currentPage() }} of {{ $documents->lastPage() }}
                    </span>

                    {{-- Next --}}
                    @if ($documents->hasMorePages())
                        <a href="{{ $documents->nextPageUrl() }}"
                            class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100">Next</a>
                    @else
                        <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <script>
        function toggleDetail(index) {
            const row = document.getElementById(`detail-${index}`);
            const icon = document.getElementById(`icon-${index}`);

            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
                icon.textContent = '▲';
            } else {
                row.classList.add('hidden');
                icon.textContent = '▼';
            }
        }
    </script>

@endsection
