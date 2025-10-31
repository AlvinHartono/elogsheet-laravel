@extends('layouts.app')

@section('page_title', 'Detail Daily Production Refinery Report')

@section('content')
    <div class="bg-white p-6 rounded-2xl shadow-md max-w-6xl mx-auto text-sm text-gray-700">
        <div class="flex items-center space-x-3 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-6h13M9 7v.01M3 17h.01M3 7h.01M3 12h.01M9 12h13" />
            </svg>
            <h3 class="text-2xl font-bold text-gray-700">Detail Data #{{ $report->id }}</h3>
        </div>

        <x-section title="Informasi Umum">
            <x-info label="Tanggal Transaksi" :value="optional($report->transaction_date)->format('d M Y')" />
            <x-info label="Tanggal Posting" :value="optional($report->posting_date)->format('d M Y')" />
            <x-info label="Shift" :value="$report->shift" />
            <x-info label="Work Center" :value="$report->work_center" />
        </x-section>

        {{-- RAW MATERIAL (RM) / CPO --}}
        <x-section title="Raw Material">
            <x-info label="Oil Type RM" :value="$report->oil_type_rm" />
            <x-info label="CPO Tank" :value="$report->cpo_tank" />
            <x-info label="Awal Jam" :value="optional($report->oil_type_rm_awal_jam)->format('H:i')" />
            <x-info label="Awal Flowmeter" :value="number_format($report->oil_type_rm_awal_flowmeter)" />
            <x-info label="Akhir Jam" :value="optional($report->oil_type_rm_akhir_jam)->format('H:i')" />
            <x-info label="Akhir Flowmeter" :value="number_format($report->oil_type_rm_akhir_flowmeter)" />
            <x-info label="Total Pemakaian (KG)" :value="number_format($report->oil_type_rm_total)" class="font-bold text-lg" />
        </x-section>

        {{-- FINISHED GOODS (FG) --}}
        <x-section title="Finished Goods">
            <x-info label="Oil Type FG" :value="$report->oil_type_fg" />
            <x-info label="Tank Tujuan" :value="$report->oil_type_fg_to_tank" />
            <x-info label="Awal Jam" :value="optional($report->oil_type_fg_awal_jam)->format('H:i')" />
            <x-info label="Awal Flowmeter" :value="number_format($report->oil_type_fg_awal_flowmeter)" />
            <x-info label="Akhir Jam" :value="optional($report->oil_type_fg_akhir_jam)->format('H:i')" />
            <x-info label="Akhir Flowmeter" :value="number_format($report->oil_type_fg_akhir_flowmeter)" />
            <x-info label="Total Produksi (KG)" :value="number_format($report->oil_type_fg_total)" class="font-bold text-lg" />
        </x-section>

        {{-- BY PRODUCT (BP) --}}
        <x-section title="By Product">
            <x-info label="Tank Tujuan" :value="$report->bp_to_tank" />
            <x-info label="By Product" :value="$report->bp_oil_type" />
            <x-info label="Awal Jam" :value="optional($report->bp_awal_jam)->format('H:i')" />
            <x-info label="Awal Flowmeter" :value="number_format($report->bp_awal_flowmeter)" />
            <x-info label="Akhir Jam" :value="optional($report->bp_akhir_jam)->format('H:i')" />
            <x-info label="Akhir Flowmeter" :value="number_format($report->bp_akhir_flowmeter)" />
            <x-info label="Total By Product (KG)" :value="number_format($report->bp_total)" class="font-bold text-lg" />
        </x-section>

        {{-- CHEMICALS USAGE --}}
        <x-section title="Pemakaian Bahan Penolong">
            <h4 class="text-sm font-semibold text-gray-600 mb-2 mt-4 col-span-2">Bleaching Earth (BE)</h4>
            <x-info label="Refinery Tank" :value="$report->be_ref_tank" />
            <x-info label="Refinery Quantity" :value="$report->be_ref_qty" />
            <x-info label="Total Pemakaian (Bag)" :value="$report->be_total_bag" />
            <x-info label="Total Pemakaian (Jenis)" :value="$report->be_total_jenis" />
            <x-info label="Lot/Batch Number" :value="$report->be_lot_batch_number" />
            <x-info label="Yield (%)" :value="$report->be_yield_percent, 2 . '%'" />

            <h4 class="text-sm font-semibold text-gray-600 mb-2 mt-4 col-span-2">Phosphoric Acid (PA)</h4>
            <x-info label="Tank Referensi" :value="$report->pa_ref_tank" />
            <x-info label="Kuantiti Referensi" :value="$report->pa_ref_qty" />
            <x-info label="Total Pemakaian (KG)" :value="$report->pa_total" />
            <x-info label="Lot/Batch Number" :value="$report->pa_lot_batch_number" />
            <x-info label="Yield (%)" :value="$report->pa_yield_percent, 2 . '%'" />
        </x-section>

        {{-- UTILITIES --}}
        <x-section title="Utilities Usage (UU)">
            <x-info label="Item Utility" :value="$report->uu_item" />
            <x-info label="Tank Budget Ref" :value="$report->uu_budget_ref_tank" />
            <x-info label="Kuantiti Budget" :value="$report->uu_budget_qty" />
            <x-info label="Total CPO" :value="$report->uu_total_cpo" />
            <x-info label="Total Steam" :value="$report->uu_total_steam" />
            <x-info label="Steam/CPO" :value="$report->uu_steam_cpo, 2" />
            <x-info label="Yield (%)" :value="$report->uu_yield_percent, 2 . '%'" />
        </x-section>

        {{-- REMARKS & FORM INFO --}}
        <x-section title="Lainnya">
            <x-info label="Remarks" :value="$report->remarks" />
            <x-info label="Form No" :value="$report->form_no" />
            <x-info label="Date Issued" :value="optional($report->date_issued)->format('d M Y')" />
            <x-info label="Revision No" :value="$report->revision_no" />
            <x-info label="Revision Date" :value="optional($report->revision_date)->format('d M Y H:i')" />
        </x-section>

        {{-- VALIDATION & APPROVAL --}}
        <x-section title="Validasi & Approval">
            <x-info label="Prepared By" :value="$report->prepared_by" />
            <x-info label="Prepared Date" :value="optional($report->prepared_date)->format('d M Y H:i')" />
            <x-info label="Prepared Status" :value="$report->prepared_status" />
            <x-info label="Prepared Status Remarks" :value="$report->prepared_status_remarks" />
            <x-info label="Checked By" :value="$report->checked_by" />
            <x-info label="Checked Date" :value="optional($report->checked_date)->format('d M Y H:i')" />
            <x-info label="Checked Status" :value="$report->checked_status" />
            <x-info label="Checked Status Remarks" :value="$report->checked_status_remarks" />
        </x-section>

        <div class="mt-6 text-right">
            <a href="{{ url()->previous() }}"
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
