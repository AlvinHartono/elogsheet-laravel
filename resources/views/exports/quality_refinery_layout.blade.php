@extends('layouts.app')

@section('title', 'Laporan Quality Refinery')

@section('content')
    <div class="bg-white p-6 rounded shadow-md text-sm">
        {{-- Judul --}}
        <div class="text-center mb-4">
            <h2 class="text-lg font-bold uppercase">PT ENERGI UNGGUL PERSADA - BONTANG</h2>
            <h3 class="text-xl font-bold uppercase">Quality Report</h3>
            <h4 class="text-md uppercase">Refinery Plant</h4>
        </div>

        {{-- Tanggal --}}
        <div class="mb-4">
            <strong>Date:</strong> {{ now()->format('d/m/Y') }}
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-400 text-center">
                <thead class="bg-gray-100">
                    <tr class="border-b">
                        <th rowspan="2" class="border px-2 py-1">Time (WITA)</th>
                        <th rowspan="2" class="border px-2 py-1">Tank Source</th>
                        <th colspan="9" class="border px-2 py-1">CPO</th>
                        <th colspan="2" class="border px-2 py-1">Chemical</th>
                        <th colspan="3" class="border px-2 py-1">BPO</th>
                        <th colspan="7" class="border px-2 py-1">RPO</th>
                        <th colspan="2" class="border px-2 py-1">PFAD</th>
                        <th colspan="1" class="border px-2 py-1">Spent Earth</th>
                        <th rowspan="2" class="border px-2 py-1">PIC</th>
                        <th rowspan="2" class="border px-2 py-1">Remarks</th>
                    </tr>
                    <tr class="border-b">
                        {{-- CPO --}}
                        <th class="border px-2 py-1">Flow Rate</th>
                        <th class="border px-2 py-1">FFA</th>
                        <th class="border px-2 py-1">IV</th>
                        <th class="border px-2 py-1">PV</th>
                        <th class="border px-2 py-1">AnV</th>
                        <th class="border px-2 py-1">DOBI</th>
                        <th class="border px-2 py-1">Carotene</th>
                        <th class="border px-2 py-1">M&I</th>
                        <th class="border px-2 py-1">Color</th>

                        {{-- Chemical --}}

                        <th class="border px-2 py-1">PA</th>
                        <th class="border px-2 py-1">BE</th>

                        {{-- BPO --}}
                        <th class="border px-2 py-1">Color (R)</th>
                        <th class="border px-2 py-1">Color (Y)</th>
                        <th class="border px-2 py-1">Break Test</th>


                        {{-- RPO --}}
                        <th class="border px-2 py-1">FFA</th>
                        <th class="border px-2 py-1">Color (R)</th>
                        <th class="border px-2 py-1">Color (Y)</th>
                        <th class="border px-2 py-1">Color (B)</th>
                        <th class="border px-2 py-1">PV</th>
                        <th class="border px-2 py-1">M&I</th>
                        <th class="border px-2 py-1">Product Tank No.</th>

                        {{-- PFAD --}}
                        <th class="border px-2 py-1">Purity</th>
                        <th class="border px-2 py-1">Product Tank No.</th>

                        {{-- Spent Earth --}}
                        <th class="border px-2 py-1">OIC %</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr class="border">
                            <td class="border px-1 py-1">{{ optional($row->time)->format('H:i') }}</td>
                            <td class="border px-1 py-1">{{ $row->p_tank_source }}</td>
                            <td class="border px-1 py-1">{{ $row->p_flowrate }}</td>
                            <td class="border px-1 py-1">{{ $row->p_ffa }}</td>
                            <td class="border px-1 py-1">{{ $row->p_iv }}</td>
                            <td class="border px-1 py-1">{{ $row->p_pv }}</td>
                            <td class="border px-1 py-1">{{ $row->p_anv }}</td>
                            <td class="border px-1 py-1">{{ $row->p_dobi }}</td>
                            <td class="border px-1 py-1">{{ $row->p_carotene }}</td>
                            <td class="border px-1 py-1">{{ $row->{'p_m&i'} }}</td>
                            <td class="border px-1 py-1">{{ $row->p_color }}</td>

                            <td class="border px-1 py-1">{{ $row->c_pa }}</td>
                            <td class="border px-1 py-1">{{ $row->c_be }}</td>

                            <td class="border px-1 py-1">{{ $row->b_color_r }}</td>
                            <td class="border px-1 py-1">{{ $row->b_color_y }}</td>
                            <td class="border px-1 py-1">{{ $row->b_break_test }}</td>

                            <td class="border px-1 py-1">{{ $row->r_ffa }}</td>
                            <td class="border px-1 py-1">{{ $row->r_color_r }}</td>
                            <td class="border px-1 py-1">{{ $row->r_color_y }}</td>
                            <td class="border px-1 py-1">{{ $row->r_color_b }}</td>
                            <td class="border px-1 py-1">{{ $row->r_pv }}</td>
                            <td class="border px-1 py-1">{{ $row->{'r_m&i'} }}</td>
                            <td class="border px-1 py-1">{{ $row->r_product_tank_no }}</td>

                            <td class="border px-1 py-1">{{ $row->fp_purity }}</td>
                            <td class="border px-1 py-1">{{ $row->fp_product_tank_no }}</td>

                            <td class="border px-1 py-1">{{ $row->spent_earth_oic }}</td>

                            <td class="border px-1 py-1">{{ $row->pic }}</td>
                            <td class="border px-1 py-1">{{ $row->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br><br><br><br><br>
        {{-- Signature --}}
        <div class="grid grid-cols-4 text-center mt-8 text-xs">
            <div>
                <strong>Prepared by,</strong><br>Shift 1<br><br><br>________________
            </div>
            <div>
                <br>Shift 2<br><br><br>________________
            </div>
            <div>
                <br>Shift 3<br><br><br>________________
            </div>
            <div>
                <strong>Checked by:</strong><br>Head of Dept<br><br><br>________________
            </div>
        </div>
    </div>
@endsection
