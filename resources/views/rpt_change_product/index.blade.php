@extends('layouts.app')

@section('page_title', 'Change Product Checklist')

@section('content')

    @php
        use Carbon\Carbon;
        $selectedDate = request('tanggal', Carbon::today()->format('Y-m-d'));
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
                        <h2 class="text-lg font-semibold text-gray-800">Change Product Checklist</h2>
                        <div class="text-sm text-gray-600 mt-1">
                            <span class="font-medium text-gray-700">Logsheet Code:</span>
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">
                                F-RFA-015
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-gray-50 p-4 rounded-md shadow-sm mb-6">
            <form method="GET" action="{{ route('change-product-checklist.index') }}"
                class="flex flex-wrap items-end gap-4">
                <div class="w-full sm:w-44">
                    <label for="filter_tanggal_awal" class="block text-sm font-medium text-gray-700">Tanggal
                        Awal</label>
                    <input type="date" id="filter_tanggal" name="filter_tanggal" value="{{ $selectedDate }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                </div>

                {{-- Tombol Filter --}}
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-lg shadow transition">
                        Filter
                    </button>

                    @if (request()->has('tanggal'))
                        <a href="{{ route('change-product-checklist.index') }}"
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
                        <th class="px-4 py-2 border-b text-left">Report ID</th>
                        <th class="px-4 py-2 border-b text-left">Plant</th>
                        <th class="px-4 py-2 border-b text-left">Work Center</th>
                        <th class="px-4 py-2 border-b text-left">Tanggal</th>
                        <th class="px-4 py-2 border-b text-left">First Product</th>
                        <th class="px-4 py-2 border-b text-left">Next Product</th>
                        {{-- === MODIFIED COLUMNS START === --}}
                        <th class="px-4 py-2 border-b text-center">Verified Status</th>
                        <th class="px-4 py-2 border-b text-center">Checked Status</th>
                        <th class="px-4 py-2 border-b text-center">Action</th>
                        <th class="px-4 py-2 border-b text-center">Report</th> {{-- <-- NEW COLUMN --}}
                        {{-- === MODIFIED COLUMNS END === --}}
                        <th class="px-4 py-2 border-b text-center">Detail</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse ($headers as $index => $doc)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">
                            <td class="px-4 py-2 border-b">
                                {{ ($headers->currentPage() - 1) * $headers->perPage() + $index + 1 }}</td>
                            <td class="px-4 py-2 border-b">{{ $doc->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $doc->plant }}</td>
                            <td class="px-4 py-2 border-b">{{ $doc->work_center }}</td>
                            <td class="px-4 py-2 border-b">
                                {{ Carbon::parse($doc->transaction_date)->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2 border-b">{{ $doc->firstProduct?->raw_material }}</td>
                            <td class="px-4 py-2 border-b">{{ $doc->nextProduct?->raw_material }}</td>

                            {{-- === NEW "VERIFIED STATUS" COLUMN === --}}
                            <td class="px-4 py-2 border-b text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if ($doc->prepared_status == 'Approved')
                                        <span class="px-2 py-0.5 text-xs rounded bg-blue-100 text-blue-700">Approved</span>
                                    @elseif ($doc->prepared_status == 'Rejected')
                                        <span class="px-2 py-0.5 text-xs rounded bg-red-100 text-red-700">Rejected</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs rounded bg-gray-100 text-gray-600">Pending</span>
                                    @endif
                                </div>
                            </td>

                            {{-- === NEW "CHECKED STATUS" COLUMN === --}}
                            <td class="px-4 py-2 border-b text-center">
                                <div class="flex items-center justify-center gap-1 mt-1">
                                    @if ($doc->checked_status == 'Approved')
                                        <span
                                            class="px-2 py-0.5 text-xs rounded bg-green-100 text-green-700">Approved</span>
                                    @elseif ($doc->checked_status == 'Rejected')
                                        <span class="px-2 py-0.5 text-xs rounded bg-red-100 text-red-700">Rejected</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs rounded bg-gray-100 text-gray-600">Pending</span>
                                    @endif
                                </div>
                            </td>

                            {{-- === "ACTION" COLUMN (Route names corrected) === --}}
                            <td class="px-4 py-2 border-b text-center">
                                <div class="flex justify-center gap-2">

                                    {{-- === SHIFT LEADER (Verify) ACTIONS === --}}
                                    @if (!$doc->prepared_status)
                                        {{-- FIXED Route Name --}}
                                        @if (auth()->user()->roles === 'LEAD' || auth()->user()->roles === 'LEAD_PROD')
                                            <form action="{{ route('change-product-checklist.verify.approve', $doc->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-blue-700 shadow"
                                                    title="Shift Leader Approve">
                                                    Approve
                                                </button>
                                            </form>
                                            {{-- FIXED Route Name --}}
                                            <form action="{{ route('change-product-checklist.verify.reject', $doc->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 shadow"
                                                    title="Shift Leader Reject">
                                                    Reject
                                                </button>
                                            </form>
                                        @else
                                            {{-- Disabled for other roles (e.g., Manager) --}}
                                            <button type="button"
                                                class="px-3 py-1 bg-gray-400 text-white text-xs rounded shadow opacity-50 cursor-not-allowed"
                                                disabled>
                                                Approve
                                            </button>
                                            <button type="button"
                                                class="px-3 py-1 bg-gray-400 text-white text-xs rounded shadow opacity-50 cursor-not-allowed"
                                                disabled>
                                                Reject
                                            </button>
                                        @endif

                                        {{-- === MANAGER (CHECK) ACTIONS === --}}
                                    @elseif ($doc->prepared_status == 'Approved' && !$doc->checked_status)
                                        @if (auth()->user()->roles == 'MGR' || auth()->user()->roles == 'MGR_PROD')
                                            <form action="{{ route('change-product-checklist.check.approve', $doc->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 shadow"
                                                    title="Manager Approve">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('change-product-checklist.check.reject', $doc->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 shadow"
                                                    title="Manager Reject">
                                                    Reject
                                                </button>
                                            </form>
                                        @else
                                            {{-- Disabled for other roles (e.g., Shift Leader) --}}
                                            <button type="button"
                                                class="px-3 py-1 bg-gray-400 text-white text-xs rounded shadow opacity-50 cursor-not-allowed"
                                                disabled>
                                                Approve
                                            </button>
                                            <button type="button"
                                                class="px-3 py-1 bg-gray-400 text-white text-xs rounded shadow opacity-50 cursor-not-allowed"
                                                disabled>
                                                Reject
                                            </button>
                                        @endif

                                        {{-- === FINAL STATUS (NO ACTIONS) === --}}
                                    @else
                                        <span class="text-xs text-gray-500">
                                            @if ($doc->prepared_status == 'Rejected')
                                                Rejected
                                            @elseif ($doc->checked_status == 'Approved')
                                                Approved
                                            @elseif ($doc->checked_status == 'Rejected')
                                                Rejected
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- === NEW "REPORT" COLUMN === --}}
                            <td class="px-4 py-2 border-b text-center">
                                <div class="flex justify-center gap-2">
                                    <!-- Preview Button -->
                                    <a href="{{ route('change-product-checklist.export.view', ['id' => $doc->id, 'mode' => 'preview']) }}"
                                        target="_blank"
                                        class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-gray-700 shadow"
                                        title="Preview PDF">
                                        Preview
                                    </a>
                                    <!-- Download Button -->
                                    <a href="{{ route('change-product-checklist.export.pdf', ['id' => $doc->id]) }}"
                                        class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 shadow"
                                        title="Download PDF">
                                        Download
                                    </a>
                                </div>
                            </td>

                            {{-- === "DETAIL" COLUMN (Unchanged) === --}}
                            <td class="px-4 py-2 border-b text-center">
                                <a href="{{ route('change-product-checklist.show', $doc->id) }}"
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
                            <td colspan="12" class="px-4 py-6 border-b text-center text-gray-500">
                                No data available for this date.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    @endsection
