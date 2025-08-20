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
    </style>
</head>

<body>
    <div class="text-center">
        <h2 style="text-transform: uppercase; font-weight: bold;">PT.PRISCOLIN</h2>
        <h3 style="text-transform: uppercase; font-weight: bold;">Quality Report</h3>
        <p>Refinery: 500 | Oil Type: RBD PO</p>
        <p>Date: {{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }}</p>
    </div>

    <div class="header-meta">
        <p><strong>Form No.</strong>: F/RFA-001</p>
        <p><strong>Date Issued</strong>: 191101</p>
        <p><strong>Revision</strong>: 01</p>
        <p><strong>Rev. Date</strong>: 210901</p>
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
                <!-- Raw Material -->
                <th>Temp (°C)</th>
                <th>FFA (%)</th>
                <th>IV</th>
                <th>DOBI</th>
                <th>AV</th>
                <th>M&I (%)</th>
                <th>PV (%)</th>

                <!-- Bleach Oil -->
                <th>Color R</th>
                <th>BREAK TEST</th>
                <th>FFA (%)</th>
                <th>IV</th>
                <th>PV</th>
                <th>M&I</th>

                <!-- RBD Oil -->
                <th>Color R</th>
                <th>Color Y</th>
                <th>To Tank</th>

                <!-- Fatty Acid -->
                <th>FFA (%)</th>
                <th>M&I</th>

                <!-- SBE -->
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

    <div class="mt-8">
        <table class="signature-table" style="width: 100%;">
            <tr>
                <td>1st SHIFT<br><br><br>______________<br>(Shift Leader)</td>
                <td>2nd SHIFT<br><br><br>______________<br>(Shift Leader)</td>
                <td>3rd SHIFT<br><br><br>______________<br>(Shift Leader)</td>
                <td>Checked by:<br><br><br>______________<br>(Department Head)</td>
            </tr>
        </table>
    </div>
</body>

</html>
