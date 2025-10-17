<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>PT.PRISCOLIN Deodorizing & Filtration Report</title>
    <style>
        body {
            font-size: 9px;
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 3px;
            text-align: center;
        }

        th {
            background-color: #f3f3f3;
        }

        .text-center {
            text-align: center;
        }

        .mt-8 {
            margin-top: 40px;
        }

        .signature-table td {
            border: none;
            text-align: center;
            padding-top: 30px;
        }

        .header-meta {
            text-align: right;
            font-size: 10px;
            line-height: 1.3;
        }

        .note {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            font-style: italic;
            color: #555;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header-meta">
        <div><strong>Form No.</strong> : {{ $formInfoFirst->form_no ?? 'F/RFA-010' }}</div>
        <div><strong>Date Issued</strong> :
            {{ $formInfoFirst ? \Carbon\Carbon::parse($formInfoFirst->date_issued)->format('d-m-Y') : '210101' }}</div>
        <div><strong>Revision</strong> : {{ $formInfoLast->revision_no ?? '01' }}</div>
        <div><strong>Rev. Date</strong> :
            {{ $formInfoLast ? \Carbon\Carbon::parse($formInfoLast->revision_date)->format('d-m-Y') : '210901' }}</div>
    </div>

    <div class="text-center" style="margin-bottom:15px;">
        <h2 style="text-transform:uppercase; font-weight:bold;">PT.PRISCOLIN</h2>
        <h3 style="text-transform:uppercase; font-weight:bold;">LOGSHEET DRY FRACTIONATION LOGSHEET</h3>
        <p>Date: {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</p>
    </div>

    @if (!empty($workCenter))
        {{-- This block runs if a specific machine is selected --}}
        <table>
            <thead>
                <tr>
                    <th>Crystalizier (Batch #)</th>
                    <th>Filling Start Time</th>
                    <th>Filling End Time</th>
                    <th>Colling Start Time</th>
                    <th>Initial Oil Level (%)</th>
                    <th>Initial Tank</th>
                    <th>Feed IV</th>
                    <th>Agitator Speed (Hz)</th>
                    <th>Water Pump Press (Bar)</th>
                    <th>Crystal Start Time</th>
                    <th>Crystal Temp</th>
                    <th>Filtration Start Time</th>
                    <th>Filtration Temp</th>
                    <th>Filtration Cycle No</th>
                    <th>Filtration Oil Level (%)</th>
                    <th>Olein IV RED</th>
                    <th>Olein Cloud Point</th>
                    <th>Stearin IV</th>
                    <th>Stearin Slep Point Red</th>
                    <th>Olein Yield (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->crystalizier }}</td>
                        <td>{{ $row->filling_start_time }}</td>
                        <td>{{ $row->filling_end_time }}</td>
                        <td>{{ $row->colling_start_time }}</td>
                        <td>{{ $row->initial_oil_level }}</td>
                        <td>{{ $row->initial_tank }}</td>
                        <td>{{ $row->feed_iv }}</td>
                        <td>{{ $row->agitator_speed }}</td>
                        <td>{{ $row->water_pump_press }}</td>
                        <td>{{ $row->crystal_start_time }}</td>
                        <td>{{ $row->crystal_temp }}</td>
                        <td>{{ $row->filtration_start_time }}</td>
                        <td>{{ $row->filtration_temp }}</td>
                        <td>{{ $row->filtration_cycle_no }}</td>
                        <td>{{ $row->filtration_oil_level }}</td>
                        <td>{{ $row->olein_iv_red }}</td>
                        <td>{{ $row->olein_cloud_point }}</td>
                        <td>{{ $row->stearin_iv }}</td>
                        <td>{{ $row->stearin_slep_point_red }}</td>
                        <td>{{ $row->olein_yield }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        {{-- This block runs if NO specific machine is selected, grouping all of them --}}
        @foreach ($groupedData as $rm => $rows)
            <div class="text-center" style="margin:15px 0;">
                <h4 style="font-weight:bold;">Refinery Machine: {{ $rm }}</h4>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Crystalizier (Batch #)</th>
                        <th>Filling Start Time</th>
                        <th>Filling End Time</th>
                        <th>Colling Start Time</th>
                        <th>Initial Oil Level (%)</th>
                        <th>Initial Tank</th>
                        <th>Feed IV</th>
                        <th>Agitator Speed (Hz)</th>
                        <th>Water Pump Press (Bar)</th>
                        <th>Crystal Start Time</th>
                        <th>Crystal Temp</th>
                        <th>Filtration Start Time</th>
                        <th>Filtration Temp</th>
                        <th>Filtration Cycle No</th>
                        <th>Filtration Oil Level (%)</th>
                        <th>Olein IV RED</th>
                        <th>Olein Cloud Point</th>
                        <th>Stearin IV</th>
                        <th>Stearin Slep Point Red</th>
                        <th>Olein Yield (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td>{{ $row->crystalizier }}</td>
                            <td>{{ $row->filling_start_time }}</td>
                            <td>{{ $row->filling_end_time }}</td>
                            <td>{{ $row->colling_start_time }}</td>
                            <td>{{ $row->initial_oil_level }}</td>
                            <td>{{ $row->initial_tank }}</td>
                            <td>{{ $row->feed_iv }}</td>
                            <td>{{ $row->agitator_speed }}</td>
                            <td>{{ $row->water_pump_press }}</td>
                            <td>{{ $row->crystal_start_time }}</td>
                            <td>{{ $row->crystal_temp }}</td>
                            <td>{{ $row->filtration_start_time }}</td>
                            <td>{{ $row->filtration_temp }}</td>
                            <td>{{ $row->filtration_cycle_no }}</td>
                            <td>{{ $row->filtration_oil_level }}</td>
                            <td>{{ $row->olein_iv_red }}</td>
                            <td>{{ $row->olein_cloud_point }}</td>
                            <td>{{ $row->stearin_iv }}</td>
                            <td>{{ $row->stearin_slep_point_red }}</td>
                            <td>{{ $row->olein_yield }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endif

    {{-- Signature section remains the same --}}
    <div class="mt-8">
        <table class="signature-table" width="100%">
            <tr>
                <td>
                    Prepared by<br>Leader Shift<br><br><br>
                    @php $first = $data->first() ?? $groupedData->first()->first(); @endphp
                    @if ($first && $first->prepared_by)
                        <strong>({{ $first->prepared_by }})</strong><br>
                        {{ \Carbon\Carbon::parse($first->prepared_date)->format('d-m-Y H:i') }}
                    @else
                        (_________________)
                        <br>
                        -
                    @endif
                </td>
                <td>
                    Acknowledge by,<br>SPV<br><br><br>
                    @php $first = $data->first() ?? $groupedData->first()->first(); @endphp
                    @if ($first && $first->checked_by)
                        <strong>({{ $first->checked_by }})</strong><br>
                        {{ \Carbon\Carbon::parse($first->checked_date)->format('d-m-Y H:i') }}
                    @else
                        (_________________)
                        <br>
                        -
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="note">
        Dokumen ini telah disetujui secara elektronik melalui sistem [E-Form],<br>
        sehingga tidak memerlukan tanda tangan asli.
    </div>
</body>

</html>
