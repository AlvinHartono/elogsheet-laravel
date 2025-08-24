@extends('layouts.app')

@section('page_title', 'Laporan Quality Refinery')

@section('content')
    <div class="bg-white p-6 rounded shadow-md text-sm relative">
        {{-- Header --}}
        <div class="flex justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold uppercase">PT. Priscollin</h2>
                <p class="text-xs">Date Of Production :{{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}</p>
                <p class="text-xs">Date Of Report : {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}</p>
            </div>
            <div class="text-xs leading-tight text-right">
                <div><strong>No. Form</strong> : FQCO-002</div>
                <div><strong>Rev</strong> : 0</div>
                <div><strong>Eff. Date</strong> : 191001</div>
            </div>
        </div>

        {{-- Judul Tengah --}}
        <div class="text-center mb-6">
            <h3 class="text-lg font-bold uppercase">Daily Quality Refinery 500 MT Production Report</h3>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-400 text-center text-xs">
                <thead class="bg-gray-100">
                    <tr>
                        <th rowspan="2" class="border px-2 py-1">Time</th>
                        <th rowspan="2" class="border px-2 py-1 bg-yellow-300">From Tank</th>
                        <th rowspan="2" class="border px-2 py-1 bg-orange-300">Flow Rate T/H</th>

                        <th colspan="10" class="border px-2 py-1 bg-green-100">CPO</th>
                        <th colspan="4" class="border px-2 py-1 bg-teal-100">BPO</th>
                        <th colspan="10" class="border px-2 py-1 bg-purple-100">RBDPO</th>
                        <th colspan="3" class="border px-2 py-1 bg-yellow-100">PFAD</th>
                        <th colspan="2" class="border px-2 py-1 bg-orange-100">Spent Earth</th>
                        <th rowspan="2" class="border px-2 py-1">Remarks</th>
                    </tr>
                    <tr>
                        {{-- CPO --}}
                        <th class="border px-1 py-1">FFA %</th>
                        <th class="border px-1 py-1">M&I %</th>
                        <th class="border px-1 py-1">DOBI</th>
                        <th class="border px-1 py-1">IV</th>
                        <th class="border px-1 py-1">PV</th>
                        <th class="border px-1 py-1">AV</th>
                        <th class="border px-1 py-1">Totox</th>
                        <th class="border px-1 py-1">R</th>
                        <th class="border px-1 py-1">Y</th>
                        <th class="border px-1 py-1">B</th>

                        {{-- BPO --}}
                        <th class="border px-1 py-1">R</th>
                        <th class="border px-1 py-1">Y</th>
                        <th class="border px-1 py-1">W/B</th>
                        <th class="border px-1 py-1">Break Test</th>

                        {{-- RBDPO --}}
                        <th class="border px-1 py-1">FFA</th>
                        <th class="border px-1 py-1">Moist</th>
                        <th class="border px-1 py-1">IMP</th>
                        <th class="border px-1 py-1">IV</th>
                        <th class="border px-1 py-1">PV</th>
                        <th class="border px-1 py-1">R</th>
                        <th class="border px-1 py-1">Y</th>
                        <th class="border px-1 py-1">W/B</th>
                        <th class="border px-1 py-1">To Tank</th>
                        <th class="border px-1 py-1">Remarks</th>

                        {{-- PFAD --}}
                        <th class="border px-1 py-1">FFA %</th>
                        <th class="border px-1 py-1">M&I %</th>
                        <th class="border px-1 py-1">To Tank</th>

                        {{-- SBE --}}
                        <th class="border px-1 py-1">M&I %</th>
                        <th class="border px-1 py-1">SPTH</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td class="border px-1 py-1">{{ optional($row->time)->format('H:i') }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_tank_source }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_flowrate }}</td>

                            {{-- CPO --}}
                            <td class="border px-1 py-1">{{ $row->rm_ffa }}</td>
                            <td class="border px-1 py-1">{{ $row->{'rm_m&i'} }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_dobi }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_iv }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_pv }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_av }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_totox }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_color_r }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_color_y }}</td>
                            <td class="border px-1 py-1">{{ $row->rm_color_b }}</td>

                            {{-- BPO --}}
                            <td class="border px-1 py-1">{{ $row->bo_color_r }}</td>
                            <td class="border px-1 py-1">{{ $row->bo_color_y }}</td>
                            <td class="border px-1 py-1">{{ $row->bo_color_b }}</td>
                            <td class="border px-1 py-1">{{ $row->bo_break_test }}</td>

                            {{-- RBDPO --}}
                            <td class="border px-1 py-1">{{ $row->fg_ffa }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_moist }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_impurities }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_iv }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_pv }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_color_r }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_color_y }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_color_b }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_tank_to }}</td>
                            <td class="border px-1 py-1">{{ $row->fg_tank_to_others_remarks }}</td>


                            {{-- PFAD --}}
                            <td class="border px-1 py-1">{{ $row->bp_ffa }}</td>
                            <td class="border px-1 py-1">{{ $row->{'bp_m&i'} }}</td>
                            <td class="border px-1 py-1">{{ $row->bp_to_tank }}</td>

                            {{-- SBE --}}
                            <td class="border px-1 py-1">{{ $row->w_sbe_qc }}</td>
                            <td class="border px-1 py-1">{{ $row->{'w_sbe_m&i'} }}</td>

                            <td class="border px-1 py-1">{{ $row->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer Box --}}
        <div class="grid grid-cols-3 gap-6 mt-6 text-xs">
            <div class="border p-2">
                <strong>Daily Chemical Usage</strong>
                <table class="w-full text-left mt-1">
                    <tr>
                        <td>Bleaching Earth</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Phosphoric Acid</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>RPO Usage</td>
                        <td>-</td>
                    </tr>
                </table>
            </div>
            <div class="border p-2">
                <strong>Theoretical Yield</strong>
                <table class="w-full text-left mt-1">
                    <tr>
                        <td>RPO</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>PFAD</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Losses</td>
                        <td>-</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Signature --}}
        <div class="grid grid-cols-2 mt-10 text-center text-xs">
            <div>
                Prepared by,<br><br><br>
                <strong>(Andi Pujihirjawan)</strong><br>
                QC Ass Supervisor
            </div>
            <div>
                Acknowledged by,<br><br><br>
                <strong>(Bambang, S.)</strong><br>
                QC Section Head
            </div>
        </div>
    </div>
@endsection
