@extends('layouts.app')

@section('page_title', 'Laporan Quality Refinery')

@section('content')
    <div class="bg-white p-6 rounded shadow-md text-sm relative">
        {{-- Form Info --}}
        <div class="absolute top-4 right-6 text-xs leading-tight text-left">
            <div><strong>Form No.</strong> : {{ $formInfoFirst->form_no ?? '' }}</div>
            <div><strong>Date Issued</strong> :
                {{ $formInfoFirst && $formInfoFirst->date_issued ? \Carbon\Carbon::parse($formInfoFirst->date_issued)->format('ymd') : '' }}
            </div>
            <div><strong>Revision</strong> : {{ $formInfoLast ? sprintf('%02d', $formInfoLast->revision_no) : '' }}</div>
            <div><strong>Rev. Date</strong> :
                {{ $formInfoLast && $formInfoLast->revision_date ? \Carbon\Carbon::parse($formInfoLast->revision_date)->format('ymd') : '' }}
            </div>
        </div>

        {{-- Judul --}}
        <div class="text-center mb-4">
            <h2 class="text-lg font-bold uppercase">PT.PRISCOLIN</h2>
            <h3 class="text-xl font-bold uppercase">QUALITY REPORT</h3>

            @if (!empty($workCenter))
                {{-- Mode 1: Jika user pilih 1 work center --}}
                <div class="mt-1">
                    {{ $refinery->name ?? '-' }} ({{ $refinery->work_center ?? request('filter_work_center') }}) |
                    Oil Type: {{ $oilType->oil_type ?? '-' }}
                </div>
            @else
                {{-- Mode 2: Jika semua work center --}}
                <div class="mt-2 font-semibold text-gray-700">
                    Work Centers:
                    <ul class="list-none mt-1">
                        @foreach ($groupedData as $wc => $rows)
                            @php
                                $firstRow = $rows->first();
                                $oilTypeName = $firstRow->oil_type ?? '-';
                                $wcName = $firstRow->refinery_name ?? $wc; // refinery_name hasil join m_mastervalue.name
                            @endphp
                            <li>{{ $wcName }} ({{ $wc }}) | Oil Type: {{ $oilTypeName }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-1">
                Date: {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}
            </div>
        </div>


        {{-- Table Section --}}
        @if (!empty($workCenter))
            {{-- Jika pilih work center (1 tabel saja) --}}
            @include('rpt_quality._table', ['rows' => $data])
        @else
            {{-- Jika tidak pilih work center (per grup work center) --}}
            @foreach ($groupedData as $wc => $rows)
                <h4 class="text-md font-bold mt-6 mb-2">
                    Work Center: {{ $wc }}
                </h4>
                @include('rpt_quality._table', ['rows' => $rows])
            @endforeach
        @endif

        {{-- Signature --}}
        @php
            $shiftLabels = [
                'shift1' => '1st SHIFT',
                'shift2' => '2nd SHIFT',
                'shift3' => '3rd SHIFT',
            ];
        @endphp

        <div class="grid grid-cols-4 text-center mt-10 text-xs">
            @foreach ($shiftLabels as $key => $label)
                <div>
                    <strong>{{ $label }}</strong><br><br><br>
                    @if (!empty($signatures[$key]))
                        {{ $signatures[$key]['name'] ?? '---' }}<br>
                        (Shift Leader)
                        <br>
                        <small>
                            {{ !empty($signatures[$key]['date'])
                                ? \Carbon\Carbon::parse($signatures[$key]['date'])->format('d M Y H:i')
                                : '' }}
                        </small>
                    @else
                        ______________<br>
                        (Shift Leader)<br>
                    @endif
                </div>
            @endforeach

            {{-- Checked --}}
            <div>
                <strong>Checked by:</strong><br><br><br>
                {{ $sign->checked_by ?? '________________' }}<br>
                (Department Head)<br>
                <small>
                    {{ !empty($sign->checked_date) ? \Carbon\Carbon::parse($sign->checked_date)->format('d M Y H:i') : '' }}
                </small>
            </div>
        </div>

        {{-- Informasi persetujuan elektronik --}}
        <div class="mt-6 text-center text-xs text-gray-600 italic">
            Dokumen ini telah disetujui secara elektronik melalui sistem [E-Logsheet],
            sehingga tidak memerlukan tanda tangan asli.
        </div>
    </div>
@endsection
