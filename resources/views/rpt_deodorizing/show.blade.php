@extends('layouts.app')

@section('page_title', 'Detail Deodorizing Report')

@section('content')
    <div class="bg-white p-6 rounded-2xl shadow-md max-w-6xl mx-auto text-sm text-gray-700">
        <div class="flex items-center space-x-3 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-6h13M9 7v.01M3 17h.01M3 7h.01M3 12h.01M9 12h13" />
            </svg>
            <h3 class="text-2xl font-bold text-gray-700">Detail Data #{{ $report->id }}</h3>
        </div>

        <x-section title="Informasi Umum">
            <x-info label="Tanggal" :value="optional($report->transaction_date)->format('d M Y')" />
            <x-info label="Jam" :value="optional($report->time)->format('H:i')" />
            <x-info label="Shift" :value="$report->shift" />
            <x-info label="Refinery Machine" :value="$report->refinery_machine" />
        </x-section>

        <x-section title="Deodorizing Column">
            <x-info label="Oil Type" :value="$report->oil_type" />
            <x-info label="Flow (FIT-701)" :value="$report->fit701_bpo" />
            <x-info label="D-701 Vacum" :value="$report->d701_vacum" />
            <x-info label="D-701 T" :value="$report->d701_td701" />
            <x-info label="E-702" :value="$report->e702" />
        </x-section>

        <x-section title="Heating & Deaeration">
            <x-info label="Thermopac Inlet" :value="$report->thermopac_inlet" />
            <x-info label="Thermopac Outlet" :value="$report->thermopac_outlet" />
            <x-info label="D-702 Inlet" :value="$report->d702_inlet" />
            <x-info label="D-702 Outlet" :value="$report->d702_outlet" />
            <x-info label="D-702 Vacum" :value="$report->d702_vacum" />
        </x-section>

        <x-section title="Sparging & Steam">
            <x-info label="Sparging A" :value="$report->sparging_a" />
            <x-info label="Sparging B" :value="$report->sparging_b" />
            <x-info label="E-730 Inlet" :value="$report->e730_inlet" />
            <x-info label="Steam Inlet" :value="$report->steam_inlet" />
        </x-section>

        <x-section title="Pressure & Temperature">
            <x-info label="PISH 706" :value="$report->pish_706" />
            <x-info label="TIWH 706" :value="$report->tiwh_706" />
        </x-section>

        <x-section title="Filtration (F-702)">
            <x-info label="Filter A" :value="$report->f702_a" />
            <x-info label="Filter B" :value="$report->f702_b" />
            <x-info label="Filter C" :value="$report->f702_c" />
        </x-section>

        <x-section title="Output Flow">
            <x-info label="Oil Type Finished Goods" :value="$report->oil_type_fg" />
            <x-info label="Flow (FIT-704)" :value="$report->fit704_rpo" />
            <x-info label="E-704" :value="$report->e704" />
            <x-info label="Oil Type By Product" :value="$report->oil_type_bp" />
            <x-info label="Flow (FIT-705)" :value="$report->fit_705_pfad" />
            <x-info label="E-705" :value="$report->e705" />
        </x-section>

        <x-section title="Lainnya">
            <x-info label="Clarity" :value="$report->clarity" />
            <x-info label="Remarks" :value="$report->remarks" />
        </x-section>

        <x-section title="Validasi & Approval">
            <x-info label="Prepared By" :value="$report->prepared_by" />
            <x-info label="Prepared Date" :value="optional($report->prepared_date)->format('d M Y H:i')" />
            <x-info label="Prepared Status" :value="$report->prepared_status" />
            <x-info label="Checked By" :value="$report->checked_by" />
            <x-info label="Checked Date" :value="optional($report->checked_date)->format('d M Y H:i')" />
            <x-info label="Checked Status" :value="$report->checked_status" />
        </x-section>

        <div class="mt-6 text-right">
            <a href="{{ route('report-deodorizing.index') }}"
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
