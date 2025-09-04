<div class="overflow-x-auto mb-6">
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
                <th class="border px-1 py-1">OC (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
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
