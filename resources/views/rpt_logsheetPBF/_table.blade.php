<div class="overflow-x-auto mb-6">
    <table class="min-w-full border border-gray-400 text-center text-xs">
        <thead class="bg-gray-100">
            <tr>
                <th rowspan="3" class="border px-2 py-1">Time</th>
                <th rowspan="3" class="border px-2 py-1">Oil Type</th>
                <th rowspan="3" class="border px-2 py-1">FIT001</th>
                <th rowspan="1" class="border px-2 py-1">E001A</th>
                <th rowspan="1" class="border px-2 py-1">F001/2</th>
                <th rowspan="1" class="border px-2 py-1">H<sub>3</sub>PO<sub>4</sub></th>
                <th rowspan="1" class="border px-2 py-1">BE</th>
                <th colspan="4" class="border px-2 py-1">Bleacher(B602)</th>
                <th colspan="3" class="border px-2 py-1">Pump(P602)</th>
                <th colspan="3" class="border px-2 py-1">Niagara Filter</th>
                <th colspan="3" class="border px-2 py-1">Filter Bag</th>
                <th colspan="2" class="border px-2 py-1">Catridge Filter </th>
                <th rowspan="3" class="border px-2 py-1">Clarity</th>
                <th rowspan="3" class="border px-2 py-1">Remarks</th>
            </tr>
            <tr>
                <th class="border px-2 py-1">Inlet</th>
                <th class="border px-2 py-1">Str.</th>
                <th class="border px-2 py-1">0.03-0.05</th>
                <th class="border px-2 py-1">0.6-1.5</th>
                <th rowspan="1" class="border px-2 py-1">Vacum</th>
                <th rowspan="1" class="border px-2 py-1">T Inlet</th>
                <th rowspan="1" class="border px-2 py-1">T B-602</th>
                <th rowspan="1" class="border px-2 py-1">Spurge</th>
                <th rowspan="1" class="border px-2 py-1">A</th>
                <th rowspan="1" class="border px-2 py-1">B</th>
                <th rowspan="1" class="border px-2 py-1">C</th>
                <th rowspan="1" class="border px-2 py-1">F-601</th>
                <th rowspan="1" class="border px-2 py-1">F-602</th>
                <th rowspan="1" class="border px-2 py-1">F-603</th>
                <th rowspan="1" class="border px-2 py-1">F604A</th>
                <th rowspan="1" class="border px-2 py-1">F604B</th>
                <th rowspan="1" class="border px-2 py-1">F604C</th>
                <th rowspan="1" class="border px-2 py-1">F605A</th>
                <th rowspan="1" class="border px-2 py-1">F605B</th>
            </tr>
            <tr>
                <th class="border px-2 py-1">(°C)</th>
                <th class="border px-2 py-1">bar</th>
                <th class="border px-2 py-1">%</th>
                <th class="border px-2 py-1">%</th>
                <th class="border px-2 py-1">mmHg</th>
                <th class="border px-2 py-1">(°C)</th>
                <th class="border px-2 py-1">(°C)</th>
                <th class="border px-2 py-1">bar</th>
                <th class="border px-2 py-1">bar</th>
                <th class="border px-2 py-1">bar</th>
                <th class="border px-2 py-1">bar</th>
                <th class="border px-2 py-1">bar</th>
                <th class="border px-2 py-1">bar</th>
                <th class="border px-2 py-1">bar</th>
                <th class="border px-2 py-1">bar</th>
                <th class="border px-2 py-1">bar</th>
                <th class="border px-2 py-1">bar</th>
                <th class="border px-2 py-1">bar</th>
                <th class="border px-2 py-1">bar</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td class="border px-1 py-1">{{ optional($row->time)->format('H:i') }}</td>
                    <td class="border px-1 py-1">{{ $row->oil_type }}</td>
                    <td class="border px-1 py-1">{{ $row->pt_fit001 }}</td>
                    <td class="border px-1 py-1">{{ $row->pt_e001a_inlet }}</td>
                    <td class="border px-1 py-1">{{ $row->pt_f0012 }}</td>
                    <td class="border px-1 py-1">{{ $row->pt_h3po4 }}</td>
                    <td class="border px-1 py-1">{{ $row->pt_be }}</td>
                    <td class="border px-1 py-1">{{ $row->bl_vacum }}</td>
                    <td class="border px-1 py-1">{{ $row->bl_t_inlet }}</td>
                    <td class="border px-1 py-1">{{ $row->bl_t_b602 }}</td>
                    <td class="border px-1 py-1">{{ $row->bl_spurge }}</td>
                    <td class="border px-1 py-1">{{ $row->p_a }}</td>
                    <td class="border px-1 py-1">{{ $row->p_b }}</td>
                    <td class="border px-1 py-1">{{ $row->p_c }}</td>
                    <td class="border px-1 py-1">{{ $row->fn_f601 }}</td>
                    <td class="border px-1 py-1">{{ $row->fn_f602 }}</td>
                    <td class="border px-1 py-1">{{ $row->fn_f603 }}</td>
                    <td class="border px-1 py-1">{{ $row->fb_604a }}</td>
                    <td class="border px-1 py-1">{{ $row->fb_604b }}</td>
                    <td class="border px-1 py-1">{{ $row->fb_604c }}</td>
                    <td class="border px-1 py-1">{{ $row->fc_605a }}</td>
                    <td class="border px-1 py-1">{{ $row->fc_605b }}</td>
                    <td class="border px-1 py-1">{{ $row->clarity }}</td>
                    <td class="border px-1 py-1">{{ $row->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
