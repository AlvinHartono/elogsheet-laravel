<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>PT.PRISCOLIN Daily Quality Refinery 500 MT Production Report</title>
    <style>
        body {
            font-size: 9px;
            font-family: sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 0.5px solid #444;
            padding: 3px;
            text-align: center;
        }

        th {
            background-color: #f3f3f3;
        }

        .text-center {
            text-align: center;
        }

        .section-table {
            width: 32%;
            float: left;
            margin-right: 1%;
            margin-top: 20px;
        }

        .section-table th,
        .section-table td {
            border: 0.5px solid #444;
            padding: 3px;
        }

        .signature-table {
            width: 100%;
            margin-top: 60px;
        }

        .signature-table td {
            border: none;
            text-align: center;
            height: 80px;
        }

        @page {
            size: A3 landscape;
            margin: 15mm;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <h2 style="text-transform: uppercase; font-weight: bold;">PT.PRISCOLIN</h2>
        <h3 style="text-transform: uppercase; font-weight: bold;">Daily Quality Refinery 500 MT Production Report</h3>
        <p>Date: {{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }}</p>
    </div>
    <div style="text-align:right; font-size:9px;">
        <p><strong>Form No.</strong>: FQCO-002</p>
        <p><strong>Revision</strong>: 0</p>
        <p><strong>Eff. Date</strong>: 210901</p>
    </div>

    <!-- Tabel utama -->
    <table>
        <thead>
            <tr>
                <th rowspan="2">Time</th>
                <th rowspan="2">From Tank No.</th>
                <th rowspan="2">Flow Rate</th>
                <th colspan="10">RAW MATERIAL</th>
                <th colspan="4">BPO</th>
                <th colspan="10">RRPO</th>
                <th colspan="3">PFAD</th>
                <th colspan="2">SPENT EARTH</th>
                <th rowspan="2">REMARKS</th>
            </tr>
            <tr>
                <th>FFA (%)</th>
                <th>M&I (%)</th>
                <th>Dobi</th>
                <th>IV</th>
                <th>PV</th>
                <th>AV</th>
                <th>Totox</th>
                <th>Color R</th>
                <th>Color Y</th>
                <th>Color B</th>
                <th>Color R</th>
                <th>Color Y</th>
                <th>Color W/B</th>
                <th>Break Test</th>
                <th>FFA</th>
                <th>Moist</th>
                <th>IMP</th>
                <th>IV</th>
                <th>PV</th>
                <th>Color R</th>
                <th>Color Y</th>
                <th>Color W/B</th>
                <th>To Tank</th>
                <th>Remarks</th>
                <th>FFA (%)</th>
                <th>M&I (%)</th>
                <th>To Tank</th>
                <th>M&I (%)</th>
                <th>SPTH (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ optional($row->time)->format('H:i') }}</td>
                    <td>{{ $row->rm_tank_source }}</td>
                    <td>{{ $row->rm_flowrate }}</td>
                    <td>{{ $row->rm_ffa }}</td>
                    <td>{{ $row->{'rm_m&i'} }}</td>
                    <td>{{ $row->rm_dobi }}</td>
                    <td>{{ $row->rm_iv }}</td>
                    <td>{{ $row->rm_pv }}</td>
                    <td>{{ $row->rm_av }}</td>
                    <td>{{ $row->rm_totox }}</td>
                    <td>{{ $row->rm_color_r }}</td>
                    <td>{{ $row->rm_color_y }}</td>
                    <td>{{ $row->rm_color_b }}</td>
                    <td>{{ $row->bo_color_r }}</td>
                    <td>{{ $row->bo_color_y }}</td>
                    <td>{{ $row->bo_color_b }}</td>
                    <td>{{ $row->bo_break_test }}</td>
                    <td>{{ $row->fg_ffa }}</td>
                    <td>{{ $row->fg_moist }}</td>
                    <td>{{ $row->fg_impurities }}</td>
                    <td>{{ $row->fg_iv }}</td>
                    <td>{{ $row->fg_pv }}</td>
                    <td>{{ $row->fg_color_r }}</td>
                    <td>{{ $row->fg_color_y }}</td>
                    <td>{{ $row->fg_color_b }}</td>
                    <td>{{ $row->fg_tank_to }}</td>
                    <td>{{ $row->fg_tank_to_others_remarks }}</td>
                    <td>{{ $row->bp_ffa }}</td>
                    <td>{{ $row->{'bp_m&i'} }}</td>
                    <td>{{ $row->bp_to_tank }}</td>
                    <td>{{ $row->w_sbe_qc }}</td>
                    <td>{{ $row->{'w_sbe_m&i'} }}</td>
                    <td>{{ $row->remarks }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="33"></td>
            </tr>
        </tbody>
    </table>

    <!-- Bagian bawah -->
    <div style="margin-top:20px; display:flex; justify-content:space-between;">
        <!-- Daily Chemical Usage -->
        <table class="section-table">
            <thead>
                <tr>
                    <th colspan="3">Daily Chemical Usage</th>
                </tr>
                <tr>
                    <th>Chemical</th>
                    <th>Amount</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Bleaching Earth</td>
                    <td>{{ $dailyUsage['bleaching_earth_amount'] ?? '-' }}</td>
                    <td>{{ $dailyUsage['bleaching_earth_percent'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Phosphoric Acid</td>
                    <td>{{ $dailyUsage['phosphoric_acid_amount'] ?? '-' }}</td>
                    <td>{{ $dailyUsage['phosphoric_acid_percent'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>RPO Usage</td>
                    <td colspan="2">{{ $dailyUsage['rpo_usage'] ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Theoretical Yield -->
        <table class="section-table">
            <thead>
                <tr>
                    <th colspan="2">Theoretical Yield</th>
                </tr>
                <tr>
                    <th>Product</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>RRPO</td>
                    <td>{{ $yield['rrpo'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>PFAD</td>
                    <td>{{ $yield['pfad'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>LOSES</td>
                    <td>{{ $yield['loses'] ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Signature -->
    <table class="signature-table">
        <tr>
            <td>
                <p>Prepared by,</p>
                <br><br><br>
                <p><strong>{{ $prepared_by ?? 'QC Ass Supervisor' }}</strong></p>
            </td>
            <td>
                <p>Acknowledged by,</p>
                <br><br><br>
                <p><strong>{{ $acknowledged_by ?? 'QC Section Head' }}</strong></p>
            </td>
        </tr>
    </table>
</body>

</html>
