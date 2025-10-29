@extends('layouts.app')

@section('page_title', 'Start Up Produksi Checklist - Layout Preview')

@section('content')
    <div class="bg-white p-6 rounded shadow-md text-sm relative max-w-4xl mx-auto">
        {{-- Form Info (Unchanged) --}}
        <table class="w-full mb-4">
            <tbody>
                <tr class="align-top">
                    {{-- Column 1: Logo and Bekasi --}}
                    <td class="w-1/5 text-center">
                        {{-- 
                          TODO: Replace this path with your actual logo.
                          You can use {{ asset('images/logo.png') }} if it's in the public/images folder.
                        --}}
                        <img src="{{ asset('images/KPN Corp.jpg') }}" alt="Logo" class="h-12 mx-auto mb-1">
                        <span class="font-bold">Bekasi</span>
                    </td>

                    {{-- Column 2: Titles --}}
                    <td class="w-3/5 text-center pt-2">
                        <h3 class="text-xl font-bold uppercase">START UP PRODUKSI <br>CHECKLIST</h3>
                    </td>

                    {{-- Column 3: Form Info --}}
                    <td class="w-1/5">
                        {{-- This block was moved from its 'absolute' position --}}
                        <div class="text-xs leading-tight text-left border border-gray-400 p-2 rounded-md">
                            <div><strong>Form No.</strong> : {{ $header->form_no ?? 'F-RFA-015' }}</div>
                            <div><strong>Date Issued</strong> :
                                {{ $header->form_date_issued ? \Carbon\Carbon::parse($header->form_date_issued)->format('ymd') : '241019' }}
                            </div>
                            <div><strong>Revision</strong> : {{ $header->form_revision ?? '00' }}</div>
                            <div><strong>Rev. Date</strong> :
                                {{ $header->form_rev_date ? \Carbon\Carbon::parse($header->form_rev_date)->format('ymd') : '00' }}
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Header Info Section (Unchanged) --}}
        <div class="border border-gray-400 p-2 rounded-md mb-4">
            <table class="w-full">
                <tbody>
                    <tr class="align-top">
                        <td class="w-1/2 pr-2">
                            <div class="flex mb-1">
                                <strong class="w-28">Tanggal</strong>:
                                {{ $header->transaction_date ? \Carbon\Carbon::parse($header->transaction_date)->format('d-m-Y') : '' }}
                            </div>
                            <div class="flex mb-1">
                                <strong class="w-28">Jam</strong>: {{ $header->transaction_time ?? '' }}
                            </div>
                            <div class="flex mb-1">
                                <strong class="w-28">Plant</strong>: {{ $header->plant ?? '' }}
                            </div>
                            <div class="flex mb-1">
                                <strong class="w-28">Work Center</strong>: {{ $header->work_center ?? '' }}
                            </div>
                        </td>
                        <td class="w-1/2 pl-2">
                            <div class="flex mb-1">
                                <strong class="w-28">First Product</strong>:
                                {{ $header->product->raw_material ?? 'N/A' }}
                            </div>
                            <div class="flex mb-1">
                                <strong class="w-28">Remarks</strong>: {{ $header->remarks ?? '-' }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- === MODIFICATION START: Split tables === --}}

        {{-- 1. Define the categories for each table using filter() and reject() --}}
        @php
            // Define the category names for the first table
            $refineryCategoryNames = ['Pre Treatment Section', 'Bleacher Section', 'Deodorization Section'];

            // Create the first group by filtering
            $refineryGroups = $groupedDetails->filter(function ($details, $category) use ($refineryCategoryNames) {
                return in_array($category, $refineryCategoryNames);
            });

            // Create the second group by rejecting the first group's categories
            $fractionationGroups = $groupedDetails->reject(function ($details, $category) use ($refineryCategoryNames) {
                return in_array($category, $refineryCategoryNames);
            });
        @endphp

        {{-- Table 1: Refinery Plant --}}
        <h4 class="text-base font-bold mb-1 mt-4">Refinery Plant</h4>
        <table class="min-w-full bg-white border border-gray-400">
            <thead class="bg-gray-200 text-gray-800">
                <tr>
                    <th class="px-3 py-2 border border-gray-400 text-center">Checklist (&#10003;)</th>
                    <th class="px-3 py-2 border border-gray-400 text-left w-2/3">Check Item / Parameter</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                {{-- Loop over the new $refineryGroups variable --}}
                @forelse ($refineryGroups as $category => $details)
                    {{-- Category Header --}}
                    <tr>
                        <td colspan="2" class="px-3 py-1 border border-gray-400 bg-gray-100 font-bold">
                            {{ $category }}
                        </td>
                    </tr>
                    {{-- Checklist Items (Details) --}}
                    @foreach ($details as $detail)
                        <tr>
                            <td class="px-3 py-1 border border-gray-400 text-center h-6">
                                @if ($detail->status_item == 'T')
                                    <span class="font-bold text-green-700">✓</span>
                                @elseif ($detail->status_item == 'F')
                                    <span class="font-bold text-red-700"></span>
                                @else
                                    <span class="text-xs text-gray-600">{{ $detail->status_item }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-1 border border-gray-400">
                                {{-- Changed to show sort_no and name for clarity --}}
                                {{ $detail->langkahkerjaStartup->sort_no ?? '' }} &nbsp;
                                {{ $detail->langkahkerjaStartup->name ?? 'Unknown Item' }}
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="2" class="px-3 py-2 text-center text-gray-500 italic">
                            No items found for Refinery Plant.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Table 2: Fractionation --}}
        <h4 class="text-base font-bold mb-1 mt-6">Fractionation Plant</h4>
        <table class="min-w-full bg-white border border-gray-400">
            <thead class="bg-gray-200 text-gray-800">
                <tr>
                    <th class="px-3 py-2 border border-gray-400 text-center">Checklist (&#10003;)</th>
                    <th class="px-3 py-2 border border-gray-400 text-left w-2/3">Check Item / Parameter</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                {{-- Loop over the new $fractionationGroups variable --}}
                @forelse ($fractionationGroups as $category => $details)
                    {{-- Category Header --}}
                    <tr>
                        <td colspan="2" class="px-3 py-1 border border-gray-400 bg-gray-100 font-bold">
                            {{ $category }}
                        </td>
                    </tr>
                    {{-- Checklist Items (Details) --}}
                    @foreach ($details as $detail)
                        <tr>
                            <td class="px-3 py-1 border border-gray-400 text-center h-6">
                                @if ($detail->status_item == 'T')
                                    <span class="font-bold text-green-700">✓</span>
                                @elseif ($detail->status_item == 'F')
                                    <span class="font-bold text-red-700"></span>
                                @else
                                    <span class="text-xs text-gray-600">{{ $detail->status_item }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-1 border border-gray-400">
                                {{-- Changed to show sort_no and name for clarity --}}
                                {{ $detail->langkahkerjaStartup->sort_no ?? '' }} &nbsp;
                                {{ $detail->langkahkerjaStartup->name ?? 'Unknown Item' }}
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="2" class="px-3 py-2 text-center text-gray-500 italic">
                            No items found for Fractionation.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- === MODIFICATION END === --}}

        {{-- Signature Section (Unchanged) --}}
        <div class="grid grid-cols-3 text-center mt-10 text-xs gap-4">

            <div>
                <strong> Done by, </strong><br>
                (Operator)<br>
                <br>
                ( {{ $header->entry_by ?? '_______________________' }} )<br>
                <small>Date:
                    {{ $header->entry_date ? \Carbon\Carbon::parse($header->entry_date)->format('d-m-Y H:i') : '' }}</small>

            </div>
            <div>
                <strong>Prepared by:</strong><br>
                (Shift Leader)<br>

                <br>
                ( {{ $header->prepared_by ?? '_______________________' }} )<br>
                <small>Date:
                    {{ $header->prepared_date ? \Carbon\Carbon::parse($header->prepared_date)->format('d-m-Y H:i') : '' }}</small>
            </div>
            <div>
                <strong>Checked by:</strong><br>
                (Section Head)<br>

                <br>
                ( {{ $header->checked_by ?? '_______________________' }} )<br>
                <small>Date:
                    {{ $header->checked_date ? \Carbon\Carbon::parse($header->checked_date)->format('d-m-Y H:i') : '' }}</small>
            </div>
        </div>

        {{-- Electronic Approval Footer (Unchanged) --}}
        <div class="mt-6 text-center text-xs text-gray-600 italic">
            Dokumen ini telah disetujui secara elektronik melalui sistem [E-Logsheet],
            sehingga tidak memerlukan tanda tangan asli.
        </div>
    </div>
@endsection
