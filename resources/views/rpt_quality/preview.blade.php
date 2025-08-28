@extends('layouts.app')

@section('page_title', 'Laporan Quality Refinery')

@section('content')
    <div class="bg-white p-6 rounded shadow-md text-sm relative">
        {{-- Form Info --}}
        {{-- <div class="absolute top-4 left-6 text-xs leading-tight text-left"> --}}
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
            <div class="mt-1">
                {{ $refinery->name ?? '-' }} ({{ $refinery->work_center ?? '' }}) | Oil Type:
                {{ $oilType->oil_type ?? '-' }}
            </div>
            <div class="mt-1">
                Date: {{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }}
            </div>
        </div>



        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-400 text-center text-xs">
                <thead class="bg-gray-100">
                    <tr>
                        <th rowspan="2" class="border px-2 py-1">Time</th>
                        <th rowspan="2" class="border px-2 py-1">Source (Tank)</th>

                        {{-- Raw Material --}}
                        <th colspan="7" class="border px-2 py-1">Raw Material</th>

                        {{-- Bleach Oil --}}
                        <th colspan="2" class="border px-2 py-1">Bleach Oil</th>

                        {{-- RBD Oil --}}
                        <th colspan="7" class="border px-2 py-1">RBD Oil</th>

                        {{-- Fatty Acid + SBE --}}
                        <th colspan="2" class="border px-2 py-1">Fatty Acid</th>
                        <th colspan="1" class="border px-2 py-1">SBE</th>
                    </tr>
                    <tr>
                        {{-- Raw Material sub-columns --}}
                        <th class="border px-1 py-1">Temp (°C)</th>
                        <th class="border px-1 py-1">FFA (%)</th>
                        <th class="border px-1 py-1">IV</th>
                        <th class="border px-1 py-1">DOBI</th>
                        <th class="border px-1 py-1">AV</th>
                        <th class="border px-1 py-1">M&I (%)</th>
                        <th class="border px-1 py-1">PV (%)</th>

                        {{-- Bleach Oil sub-columns --}}
                        <th class="border px-1 py-1">Color R</th>
                        <th class="border px-1 py-1">BREAK TEST</th>

                        {{-- RBD Oil sub-columns --}}
                        <th class="border px-1 py-1">FFA (%)</th>
                        <th class="border px-1 py-1">IV</th>
                        <th class="border px-1 py-1">PV</th>
                        <th class="border px-1 py-1">M&I</th>
                        <th class="border px-1 py-1">Color R</th>
                        <th class="border px-1 py-1">Color Y</th>
                        <th class="border px-1 py-1">To Tank</th>

                        {{-- Fatty Acid sub-columns --}}
                        <th class="border px-1 py-1">FFA (%)</th>
                        <th class="border px-1 py-1">M&I</th>

                        {{-- SBE sub-columns --}}
                        <th class="border px-1 py-1">QC (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td class="border px-1 py-1">{{ optional($row->time)->format('H:i') }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_tank_source }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_temp }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_ffa }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_iv }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_dobi }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_av }}</td>
                            <td class="border px-1 py-1">{{ $row->{'rm_m&i'} }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_pv }}</td>
                            <td class="border px-1 py-1">{{ $row->bo_color }}</td>
                            <td class="border px-1 py-1">{{ $row->bo_break_test }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_ffa }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_iv }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_pv }}</td>
                            <td class="border px-1 py-1">{{ $row->{'fg_m&i'} }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_color_r }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_color_y }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_tank_to }}</td>
                            <td class="border px-1 py-1">{{ $row->bp_ffa }}</td>
                            <td class="border px-1 py-1">{{ $row->{'bp_m&i'} }}</td>
                            <td class="border px-1 py-1">{{ $row->w_sbe_qc }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

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

            {{-- Checked (opsional, kalau field ada di LSQualityReport) --}}
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
