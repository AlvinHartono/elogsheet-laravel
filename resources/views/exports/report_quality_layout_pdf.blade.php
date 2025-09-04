<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>PT.PRISCOLIN Quality Report</title>
    <style>
        body {
            font-size: 10px;
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 4px;
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
    <div class="header-meta" style="text-align:right; font-size:10px; line-height:1.3;">
        <div><strong>Form No.</strong> : {{ $formInfoFirst->form_no ?? '' }}</div>
        <div><strong>Date Issued</strong> :
            {{ $formInfoFirst && $formInfoFirst->date_issued ? \Carbon\Carbon::parse($formInfoFirst->date_issued)->format('ymd') : '' }}
        </div>
        <div><strong>Revision</strong> :
            {{ $formInfoLast ? sprintf('%02d', $formInfoLast->revision_no) : '' }}
        </div>
        <div><strong>Rev. Date</strong> :
            {{ $formInfoLast && $formInfoLast->revision_date ? \Carbon\Carbon::parse($formInfoLast->revision_date)->format('ymd') : '' }}
        </div>
    </div>

    {{-- Judul --}}
    <div class="text-center" style="margin-bottom:15px;">
        <h2 style="text-transform:uppercase; font-weight:bold;">PT.PRISCOLIN</h2>
        <h3 style="text-transform:uppercase; font-weight:bold;">QUALITY REPORT</h3>
        <p>Date: {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</p>

        @if ($workCenter)
            <div style="margin-top:4px; font-weight:bold;">
                {{ $refinery->name ?? '-' }} ({{ $workCenter }}) | Oil Type: {{ $oilType->oil_type ?? '-' }}
            </div>
        @else
            <div style="margin-top:6px; font-weight:bold; text-align:center;">
                Work Centers:
                <ul style="list-style:none; padding:0; margin:6px 0 0 0;">
                    @foreach ($groupedData as $wc => $rows)
                        @php
                            $firstRow = $rows->first();
                            $oilTypeName = $firstRow->oil_type ?? '-';
                            $wcName = $firstRow->refinery_name ?? $wc;
                        @endphp
                        <li>{{ $wcName }} ({{ $wc }}) | Oil Type: {{ $oilTypeName }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    @if ($workCenter)
        <table>
            <thead>
                <tr>
                    <th rowspan="2">Time</th>
                    <th rowspan="2">Source (Tank)</th>
                    <th colspan="7">Raw Material</th>
                    <th colspan="6">Bleach Oil</th>
                    <th colspan="3">RBD Oil</th>
                    <th colspan="2">Fatty Acid</th>
                    <th colspan="1">SBE</th>
                </tr>
                <tr>
                    <th>Temp (°C)</th>
                    <th>FFA (%)</th>
                    <th>IV</th>
                    <th>DOBI</th>
                    <th>AV</th>
                    <th>M&I (%)</th>
                    <th>PV (%)</th>

                    <th>Color R</th>
                    <th>BREAK TEST</th>
                    <th>FFA (%)</th>
                    <th>IV</th>
                    <th>PV</th>
                    <th>M&I</th>

                    <th>Color R</th>
                    <th>Color Y</th>
                    <th>To Tank</th>

                    <th>FFA (%)</th>
                    <th>M&I</th>

                    <th>QC (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->time ? \Carbon\Carbon::parse($row->time)->format('H:i') : '' }}</td>
                        <td>{{ $row->rm_tank_source }}</td>
                        <td>{{ $row->rm_temp }}</td>
                        <td>{{ $row->rm_ffa }}</td>
                        <td>{{ $row->rm_iv }}</td>
                        <td>{{ $row->rm_dobi }}</td>
                        <td>{{ $row->rm_av }}</td>
                        <td>{{ $row->{'rm_m&i'} }}</td>
                        <td>{{ $row->rm_pv }}</td>
                        <td>{{ $row->bo_color }}</td>
                        <td>{{ $row->bo_break_test }}</td>
                        <td>{{ $row->fg_ffa }}</td>
                        <td>{{ $row->fg_iv }}</td>
                        <td>{{ $row->fg_pv }}</td>
                        <td>{{ $row->{'fg_m&i'} }}</td>
                        <td>{{ $row->fg_color_r }}</td>
                        <td>{{ $row->fg_color_y }}</td>
                        <td>{{ $row->fg_tank_to }}</td>
                        <td>{{ $row->bp_ffa }}</td>
                        <td>{{ $row->{'bp_m&i'} }}</td>
                        <td>{{ $row->w_sbe_qc }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        {{-- Jika tidak ada filter, tampilkan semua work center --}}
        @foreach ($groupedData as $workCenter => $rows)
            @php
                $refineryName = $rows->first()->refinery_name ?? '-';
                $oilType = $rows->first()->oil_type ?? '-';
                $workCenterName = $rows->first()->work_center_name ?? $workCenter;
            @endphp

            <div class="text-center" style="margin:15px 0;">
                <h4 style="font-weight:bold;">
                    {{ $refineryName }} ({{ $workCenter }}) - {{ $workCenterName }} | Oil Type:
                    {{ $oilType }}
                </h4>
            </div>

            <table>
                <thead>
                    <tr>
                        <th rowspan="2">Time</th>
                        <th rowspan="2">Source (Tank)</th>
                        <th colspan="7">Raw Material</th>
                        <th colspan="6">Bleach Oil</th>
                        <th colspan="3">RBD Oil</th>
                        <th colspan="2">Fatty Acid</th>
                        <th colspan="1">SBE</th>
                    </tr>
                    <tr>
                        <th>Temp (°C)</th>
                        <th>FFA (%)</th>
                        <th>IV</th>
                        <th>DOBI</th>
                        <th>AV</th>
                        <th>M&I (%)</th>
                        <th>PV (%)</th>

                        <th>Color R</th>
                        <th>BREAK TEST</th>
                        <th>FFA (%)</th>
                        <th>IV</th>
                        <th>PV</th>
                        <th>M&I</th>

                        <th>Color R</th>
                        <th>Color Y</th>
                        <th>To Tank</th>

                        <th>FFA (%)</th>
                        <th>M&I</th>

                        <th>QC (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            <td>{{ $row->time ? \Carbon\Carbon::parse($row->time)->format('H:i') : '' }}</td>
                            <td>{{ $row->rm_tank_source }}</td>
                            <td>{{ $row->rm_temp }}</td>
                            <td>{{ $row->rm_ffa }}</td>
                            <td>{{ $row->rm_iv }}</td>
                            <td>{{ $row->rm_dobi }}</td>
                            <td>{{ $row->rm_av }}</td>
                            <td>{{ $row->{'rm_m&i'} }}</td>
                            <td>{{ $row->rm_pv }}</td>
                            <td>{{ $row->bo_color }}</td>
                            <td>{{ $row->bo_break_test }}</td>
                            <td>{{ $row->fg_ffa }}</td>
                            <td>{{ $row->fg_iv }}</td>
                            <td>{{ $row->fg_pv }}</td>
                            <td>{{ $row->{'fg_m&i'} }}</td>
                            <td>{{ $row->fg_color_r }}</td>
                            <td>{{ $row->fg_color_y }}</td>
                            <td>{{ $row->fg_tank_to }}</td>
                            <td>{{ $row->bp_ffa }}</td>
                            <td>{{ $row->{'bp_m&i'} }}</td>
                            <td>{{ $row->w_sbe_qc }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if (!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    @endif

    <div class="mt-8">
        <table class="signature-table" width="100%">
            <tr>
                <td>
                    Shift 1<br>
                    @if ($signatures['shift1'])
                        {{ $signatures['shift1']['name'] }}<br>
                        {{ \Carbon\Carbon::parse($signatures['shift1']['date'])->format('d-m-Y H:i') }}
                    @else
                        (Belum disetujui)
                    @endif
                </td>
                <td>
                    Shift 2<br>
                    @if ($signatures['shift2'])
                        {{ $signatures['shift2']['name'] }}<br>
                        {{ \Carbon\Carbon::parse($signatures['shift2']['date'])->format('d-m-Y H:i') }}
                    @else
                        (Belum disetujui)
                    @endif
                </td>
                <td>
                    Shift 3<br>
                    @if ($signatures['shift3'])
                        {{ $signatures['shift3']['name'] }}<br>
                        {{ \Carbon\Carbon::parse($signatures['shift3']['date'])->format('d-m-Y H:i') }}
                    @else
                        (Belum disetujui)
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
