<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10pt;
            text-align: center;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    {{-- Judul --}}
    <div class="text-center">
        <h2 class="bold">PT ENERGI UNGGUL PERSADA - BONTANG</h2>
        <h3 class="bold">Quality Report</h3>
        <h4>Refinery Plant</h4>
        <p><strong>Date:</strong> {{ now()->format('d/m/Y') }}</p>
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
                {{-- CPO --}}
                <th>Flow Rate</th>
                <th>FFA</th>
                <th>IV</th>
                <th>PV</th>
                <th>AnV</th>
                <th>DOBI</th>
                <th>Carotene</th>
                <th>M&I</th>

                {{-- Chemical --}}
                <th>Color</th>
                <th>PA</th>
                <th>BE</th>
                <th>Break Test</th>
                <th>FFA</th>
                <th>Color (R/Y/B)</th>

                {{-- BPO --}}
                <th>Color</th>
                <th>Break Test</th>
                <th>FFA</th>
                <th>R/Y</th>
                <th>B</th>

                {{-- RPO --}}
                <th>PV</th>
                <th>M&I</th>
                <th>Color</th>
                <th>Tank No.</th>

                {{-- PFAD --}}
                <th>Product</th>
                <th>Purity</th>
                <th>Tank No.</th>

                {{-- Spent Earth --}}
                <th>OIC</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ optional($row->time)->format('H:i') }}</td>
                    <td>{{ $row->p_tank_source }}</td>
                    <td>{{ $row->p_flow_rate }}</td>
                    <td>{{ $row->p_ffa }}</td>
                    <td>{{ $row->p_iv }}</td>
                    <td>{{ $row->p_pv }}</td>
                    <td>{{ $row->p_anv }}</td>
                    <td>{{ $row->p_dobi }}</td>
                    <td>{{ $row->p_carotene }}</td>
                    <td>{{ $row->p_mi }}</td>

                    <td>{{ $row->c_color }}</td>
                    <td>{{ $row->c_pa }}</td>
                    <td>{{ $row->c_be }}</td>
                    <td>{{ $row->c_break_test }}</td>
                    <td>{{ $row->c_ffa }}</td>
                    <td>{{ $row->c_color_ryb }}</td>

                    <td>{{ $row->bpo_color }}</td>
                    <td>{{ $row->bpo_break }}</td>
                    <td>{{ $row->bpo_ffa }}</td>
                    <td>{{ $row->bpo_ry }}</td>
                    <td>{{ $row->bpo_b }}</td>

                    <td>{{ $row->rpo_pv }}</td>
                    <td>{{ $row->rpo_mi }}</td>
                    <td>{{ $row->rpo_color }}</td>
                    <td>{{ $row->rpo_tank }}</td>

                    <td>{{ $row->pfad_product }}</td>
                    <td>{{ $row->pfad_purity }}</td>
                    <td>{{ $row->pfad_tank }}</td>

                    <td>{{ $row->spent_oic }}</td>
                    <td>{{ $row->spent_percent }}</td>

                    <td>{{ $row->pic }}</td>
                    <td>{{ $row->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <br><br>
    <table style="width: 100%; border: none;">
        <tr style="text-align: center; border: none;">
            <td style="border: none;">Prepared by,<br>Shift 1<br><br><br>________________</td>
            <td style="border: none;">Shift 2<br><br><br>________________</td>
            <td style="border: none;">Shift 3<br><br><br>________________</td>
            <td style="border: none;">Checked by:<br>Head of Dept<br><br><br>________________</td>
        </tr>
    </table>
</body>

</html>
