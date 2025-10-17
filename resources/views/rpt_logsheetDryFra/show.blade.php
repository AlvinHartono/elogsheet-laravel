@extends('layouts.app')

@section('page_title', 'Logsheet Dry Fractionation Report')

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

        <x-section title="Dry Fractionation">
            <x-info label="Oil Type" :value="$report->oil_type" />
            <x-info label="Crystalizier (Batch #)" :value="$report->crystalizier" />
            <x-info label="Filling Start Time" :value="$report->filling_start_time" />
            <x-info label="Filling End Time" :value="$report->filling_end_time" />
            <x-info label="Colling Start Time" :value="$report->colling_start_time" />
            <x-info label="Initial Oil Level (%)" :value="$report->initial_oil_level" />
            <x-info label="Initial Tank" :value="$report->initial_tank" />
            <x-info label="Feed IV" :value="$report->feed_iv" />
            <x-info label="Agitator Speed (Hz)" :value="$report->agitator_speed" />
            <x-info label="Water Pump Press (Bar)" :value="$report->water_pump_press" />
            <x-info label="Crystal Start Time" :value="$report->crystal_start_time" />
            <x-info label="Crystal Temp" :value="$report->crystal_temp" />
            <x-info label="Filtration Start Time" :value="$report->filtration_start_time" />
            <x-info label="Filtration Temp" :value="$report->filtration_temp" />
            <x-info label="Filtration Cycle No" :value="$report->filtration_cycle_no" />
            <x-info label="Filtration Oil Level (%)" :value="$report->filtration_oil_level" />
            <x-info label="Olein IV RED" :value="$report->olein_iv_red" />
            <x-info label="Olein Cloud Point" :value="$report->olein_cloud_point" />
            <x-info label="Stearin IV" :value="$report->stearin_iv" />
            <x-info label="Stearin Slep Point Red" :value="$report->stearin_slep_point_red" />
            <x-info label="Olein Yield (%)" :value="$report->olein_yield" />

        </x-section>

        <x-section title="Lainnya">
            <x-info label="Remarks" :value="$report->remarks" />
        </x-section>

        <x-section title="Validasi & Approval">
            <x-info label="Prepared By" :value="$report->prepared_by" />
            <x-info label="Prepared Date" :value="optional($report->prepared_date)->format('d M Y H:i')" />
            <x-info label="Prepared Status" :value="$report->prepared_status" />
            <x-info label="Prepared Status Remarks" :value="$report->prepared_status_remarks" />
            <x-info label="Checked By" :value="$report->checked_by" />
            <x-info label="Checked Date" :value="optional($report->checked_date)->format('d M Y H:i')" />
            <x-info label="Checked Status" :value="$report->checked_status" />
            <x-info label="Checked Status Remarks" :value="$report->checked_status_remarks" />
            <x-info label="Updated Date" :value="optional($report->updated_date)->format('d M Y H:i')" />
            <x-info label="Updated Status" :value="$report->updated_status" />
        </x-section>

        <div class="mt-6 text-right">
            <a href="{{ route('report-monitoring-dry-fractionation.index') }}"
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
