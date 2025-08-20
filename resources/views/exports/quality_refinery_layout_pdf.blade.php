<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Quality Refinery Report</title>
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
            padding: 2px;
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

        .text-xs {
            font-size: 8px;
        }
    </style>
</head>

<body>
    <div>
        <div class="text-center">
            <h2 style="text-transform: uppercase;">PT ENERGI UNGGUL PERSADA - BONTANG</h2>
            <h3 style="text-transform: uppercase;">Quality Report</h3>
            <h4 style="text-transform: uppercase;">Refinery Plant</h4>
        </div>

        <div>
            <strong>Date:</strong> {{ now()->format('d/m/Y') }}
        </div>

        <table>
            <thead>
                <tr>
                    <th rowspan="2">Time (WITA)</th>
                    <th rowspan="2">Tank Source</th>
                    <th colspan="8">CPO</th>
                    <th colspan="6">Chemical</th>
                    <th colspan="5">BPO</th>
                    <th colspan="4">RPO</th>
                    <th colspan="3">PFAD</th>
                    <th colspan="2">Spent Earth</th>
                    <th rowspan="2">PIC</th>
                    <th rowspan="2">Remarks</th>
                </tr>
                <tr>
                    <!-- CPO -->
                    <th>Flow Rate</th>
                    <th>FFA</th>
                    <th>IV</th>
                    <th>PV</th>
                    <th>AnV</th>
                    <th>DOBI</th>
                    <th>Carotene</th>
                    <th>M&I</th>

                    <!-- Chemical -->
                    <th>Color</th>
                    <th>PA</th>
                    <th>BE</th>
                    <th>Break Test</th>
                    <th>FFA</th>
                    <th>Color (R/Y/B)</th>

                    <!-- BPO -->
                    <th>Color</th>
                    <th>Break Test</th>
                    <th>FFA</th>
                    <th>R/Y</th>
                    <th>B</th>

                    <!-- RPO -->
                    <th>PV</th>
                    <th>M&I</th>
                    <th>Color</th>
                    <th>Tank No.</th>

                    <!-- PFAD -->
                    <th>Product</th>
                    <th>Purity</th>
                    <th>Tank No.</th>

                    <!-- Spent Earth -->
                    <th>OIC</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>{{ optional($row->time)->format('H:i') }}</td>
                        <td>{{ $row->p_tank_source }}</td>

                        {{-- CPO --}}
                        <td>{{ $row->p_flow_rate }}</td>
                        <td>{{ $row->p_ffa }}</td>
                        <td>{{ $row->p_iv }}</td>
                        <td>{{ $row->p_pv }}</td>
                        <td>{{ $row->p_anv }}</td>
                        <td>{{ $row->p_dobi }}</td>
                        <td>{{ $row->p_carotene }}</td>
                        <td>{{ $row->p_mi }}</td>

                        {{-- Chemical --}}
                        <td>{{ $row->c_color }}</td>
                        <td>{{ $row->c_pa }}</td>
                        <td>{{ $row->c_be }}</td>
                        <td>{{ $row->c_break_test }}</td>
                        <td>{{ $row->c_ffa }}</td>
                        <td>{{ $row->c_color_ryb }}</td>

                        {{-- BPO --}}
                        <td>{{ $row->bpo_color }}</td>
                        <td>{{ $row->bpo_break }}</td>
                        <td>{{ $row->bpo_ffa }}</td>
                        <td>{{ $row->bpo_ry }}</td>
                        <td>{{ $row->bpo_b }}</td>

                        {{-- RPO --}}
                        <td>{{ $row->rpo_pv }}</td>
                        <td>{{ $row->rpo_mi }}</td>
                        <td>{{ $row->rpo_color }}</td>
                        <td>{{ $row->rpo_tank }}</td>

                        {{-- PFAD --}}
                        <td>{{ $row->pfad_product }}</td>
                        <td>{{ $row->pfad_purity }}</td>
                        <td>{{ $row->pfad_tank }}</td>

                        {{-- Spent Earth --}}
                        <td>{{ $row->spent_oic }}</td>
                        <td>{{ $row->spent_percent }}</td>

                        {{-- PIC & Remarks --}}
                        <td>{{ $row->pic }}</td>
                        <td>{{ $row->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-8 text-center text-xs">
            <table style="width: 100%; border: none;">
                <tr>
                    <td>Prepared by,<br>Shift 1<br><br><br>________________</td>
                    <td><br>Shift 2<br><br><br>________________</td>
                    <td><br>Shift 3<br><br><br>________________</td>
                    <td>Checked by:<br>Head of Dept<br><br><br>________________</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
