@extends('layouts.app')

@section('page_title', 'Detail Report: ' . $header->id)

@section('content')
    <div class="bg-white p-6 rounded shadow-md max-w-4xl mx-auto">

        <div class="mb-4">
            <a href="{{ route('change-product-checklist.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-semibold rounded-lg shadow transition">
                &larr; Back to List
            </a>
        </div>

        <h2 class="text-2xl font-semibold text-gray-800 mb-4">
            Report ID: <span class="text-blue-600">{{ $header->id }}</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <strong class="text-gray-600 block">Transaction Date:</strong>
                <span>{{ $header->transaction_date->format('Y-m-d H:i') }}</span>
            </div>
            <div>
                <strong class="text-gray-600 block">Work Center:</strong>
                <span>{{ $header->work_center }}</span>
            </div>
            <div>
                <strong class="text-gray-600 block">First Product:</strong>
                <span>{{ $header->firstProduct?->raw_material ?? 'N/A' }}</span>
            </div>
            <div>
                <strong class="text-gray-600 block">Next Product:</strong>
                <span>{{ $header->nextProduct?->raw_material ?? 'N/A' }}</span>
            </div>
            <div>
                <strong class="text-gray-600 block">Prepared By:</strong>
                <span>{{ $header->prepared_by ?? 'N/A' }}</span>
            </div>
            <div>
                <strong class="text-gray-600 block">Checked By:</strong>
                <span>{{ $header->checked_by ?? 'N/A' }}</span>
            </div>
        </div>

        <h3 class="text-xl font-semibold text-gray-800 mb-3">Checklist Details</h3>
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100 text-gray-700 text-sm">
                    <tr>
                        <th class="px-4 py-2 border-b text-left">No.</th>
                        <th class="px-4 py-2 border-b text-left">Check Item (Langkah Kerja)</th>
                        <th class="px-4 py-2 border-b text-center">Checklist (✓)</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse ($header->details as $index => $detail)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">
                            <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>

                            {{-- This is where we use the new relationship --}}
                            <td class="px-4 py-2 border-b">
                                {{ $detail->langkahKerja?->name ?? 'Unknown Item' }}
                            </td>

                            <td class="px-4 py-2 border-b text-center">
                                {{-- You can customize this checkmark display --}}
                                @if ($detail->status_item == 'T' || strtolower($detail->status_item) == 'ok')
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">✓</span>
                                @elseif ($detail->status_item == 'F' || strtolower($detail->status_item) == 'ng')
                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-red-700"> </span>
                                    {{-- <span class=""></span> --}}
                                @else
                                    <span
                                        class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600">{{ $detail->status_item }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                No details found for this report.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
