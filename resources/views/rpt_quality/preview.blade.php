@extends('layouts.app')

@section('title', 'Laporan Quality Refinery')

@section('content')
    <div class="bg-white p-6 rounded shadow-md text-sm relative">
        {{-- Form Info --}}
        {{-- <div class="absolute top-4 left-6 text-xs leading-tight text-left"> --}}
        <div class="absolute top-4 right-6 text-xs leading-tight text-left">
            <div><strong>Form No.</strong> : F/RFA-001</div>
            <div><strong>Date Issued</strong> : 191101</div>
            <div><strong>Revision</strong> : 01</div>
            <div><strong>Rev. Date</strong> : 210901</div>
        </div>

        {{-- Judul --}}
        <div class="text-center mb-4">
            <h2 class="text-lg font-bold uppercase">PT.PRISCOLIN</h2>
            <h3 class="text-xl font-bold uppercase">QUALITY REPORT</h3>
            <div class="mt-1">Refinery: 500 | Oil Type: RBD PO</div>
            <div class="mt-1">Date: {{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }}</div>
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
        <div class="grid grid-cols-4 text-center mt-10 text-xs">
            {{-- Shift 1 --}}
            <div>
                <strong>1st SHIFT</strong><br>
                <br>
                <br>
                {{ $sign->prepared_by_shift1 ?? '________________' }}<br>
                (Shift Leader)<br>
                <small>
                    {{ $sign->prepared_date_shift1 ? \Carbon\Carbon::parse($sign->prepared_date_shift1)->format('d M Y H:i') : '' }}
                </small>
            </div>

            {{-- Shift 2 --}}
            <div>
                <strong>2nd SHIFT</strong><br>
                <br>
                <br>
                {{ $sign->prepared_by_shift2 ?? '________________' }}
                (Shift Leader)<br>
                <small>
                    {{ $sign->prepared_date_shift2 ? \Carbon\Carbon::parse($sign->prepared_date_shift2)->format('d M Y H:i') : '' }}
                </small>
            </div>

            {{-- Shift 3 --}}
            <div>
                <strong>3rd SHIFT</strong><br>
                <br>
                <br>
                {{ $sign->prepared_by_shift3 ?? '________________' }}
                (Shift Leader)<br>
                <small>
                    {{ $sign->prepared_date_shift3 ? \Carbon\Carbon::parse($sign->prepared_date_shift3)->format('d M Y H:i') : '' }}
                </small>
            </div>

            {{-- Checked --}}
            <div>
                <strong>Checked by:</strong><br>
                <br>
                <br>
                {{ $sign->checked_by ?? '________________' }}<br>
                (Department Head)<br>
                <small>
                    {{ $sign->checked_date ? \Carbon\Carbon::parse($sign->checked_date)->format('d M Y H:i') : '' }}
                </small>
            </div>
        </div>
    </div>
@endsection
