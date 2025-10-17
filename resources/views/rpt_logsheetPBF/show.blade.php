@extends('layouts.app')

@section('page_title', 'Detail Pretreatment Report')

@section('content')
    <div class="bg-white p-6 rounded-2xl shadow-md max-w-6xl mx-auto text-sm text-gray-700">
        {{-- Header --}}
        <div class="flex items-center space-x-3 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-6h13M9 7v.01M3 17h.01M3 7h.01M3 12h.01M9 12h13" />
            </svg>
            <h3 class="text-2xl font-bold text-gray-700">Detail Data #{{ $report->id }}</h3>
        </div>

        {{-- Section: Informasi Umum --}}
        <x-section title="Informasi Umum">
            <x-info label="Tanggal" :value="optional($report->transaction_date)->format('d M Y')" />
            <x-info label="Jam" :value="optional($report->time)->format('H:i')" />
            <x-info label="Shift" :value="$report->shift" />
            <x-info label="Remarks" :value="$report->remarks" />
        </x-section>

        {{-- Section: Pretreatment --}}
        <x-section title="Pretreatment">
            <x-info label="Oil Type" :value="$report->oil_type" />
            <x-info label="Flow (FIT-001)" :value="$report->pt_fit001" />
            <x-info label="T Inlet E-001A" :value="$report->pt_e001a_inlet" />
            <x-info label="Flow BE (F-0012)" :value="$report->pt_f0012" />
            <x-info label="Flow H3PO4" :value="$report->pt_h3po4" />
            <x-info label="Flow BE" :value="$report->pt_be" />
        </x-section>

        {{-- Section: Bleaching --}}
        <x-section title="Bleaching">
            <x-info label="Vacum" :value="$report->bl_vacum" />
            <x-info label="T Inlet" :value="$report->bl_t_inlet" />
            <x-info label="T B-602" :value="$report->bl_t_b602" />
            <x-info label="Spurge Steam" :value="$report->bl_spurge" />
        </x-section>

        {{-- Section: Pressure --}}
        <x-section title="Pressure">
            <x-info label="P(A)" :value="$report->p_a" />
            <x-info label="P(B)" :value="$report->p_b" />
            <x-info label="P(C)" :value="$report->p_c" />
        </x-section>

        {{-- Section: Filter Niagra --}}
        <x-section title="Filter Niagra">
            <x-info label="FN F-601" :value="$report->fn_f601" />
            <x-info label="FN F-602" :value="$report->fn_f602" />
            <x-info label="FN F-603" :value="$report->fn_f603" />
        </x-section>

        {{-- Section: Filter Bag --}}
        <x-section title="Filter Bag">
            <x-info label="FB 604A" :value="$report->fb_604a" />
            <x-info label="FB 604B" :value="$report->fb_604b" />
            <x-info label="FB 604C" :value="$report->fb_604c" />
        </x-section>

        {{-- Section: Filter Catridge --}}
        <x-section title="Filter Catridge">
            <x-info label="FC 605A" :value="$report->fc_605a" />
            <x-info label="FC 605B" :value="$report->fc_605b" />
        </x-section>

        <x-section title="Lainnya">
            <x-info label="Clarity" :value="$report->clarity" />
        </x-section>

        {{-- Section: Validasi & Approval --}}
        <x-section title="Validasi & Approval">
            <x-info label="Prepared By" :value="$report->prepared_by" />
            <x-info label="Prepared Date" :value="optional($report->prepared_date)->format('d M Y H:i')" />
            <x-info label="Prepared Status" :value="$report->prepared_status" />
            <x-info label="Prepared Remarks" :value="$report->prepared_status_remarks" />
            <x-info label="Checked By" :value="$report->checked_by" />
            <x-info label="Checked Date" :value="optional($report->checked_date)->format('d M Y H:i')" />
            <x-info label="Checked Status" :value="$report->checked_status" />
            <x-info label="Checked Remarks" :value="$report->checked_status_remarks" />
        </x-section>

        <div class="mt-6 text-right">
            <a href="{{ route('report-pretreatment.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.707-9.707a1 1 0 011.414 0L13.414 13H7a1 1 0 110-2h6.414l-3.707-3.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
                Kembali
            </a>
        </div>
    </div>
@endsection
