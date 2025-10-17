<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>PT.PRISCOLIN Daily Production Fractionation Report</title>
    <style>
        body {
            font-size: 8px;
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
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

        .text-right {
            text-align: right;
        }

        .mt-8 {
            margin-top: 20px;
        }

        .signature-table td {
            border: none;
            text-align: center;
            padding-top: 20px;
        }

        .header-meta {
            text-align: right;
            font-size: 9px;
            line-height: 1.3;
        }

        .note {
            margin-top: 10px;
            text-align: center;
            font-size: 8px;
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
        <div><strong>Form No.</strong> : {{ $formInfoFirst->form_no ?? 'F/RFA-XXX' }}</div>
        <div><strong>Date Issued</strong> :
            {{ $formInfoFirst ? \Carbon\Carbon::parse($formInfoFirst->date_issued)->format('d-m-Y') : 'YYMMDD' }}</div>
        <div><strong>Revision</strong> : {{ $formInfoLast->revision_no ?? '01' }}</div>
        <div><strong>Rev. Date</strong> :
            {{ $formInfoLast ? \Carbon\Carbon::parse($formInfoLast->revision_date)->format('d-m-Y') : 'YYMMDD' }}</div>
    </div>

    <div class="text-center" style="margin-bottom:15px;">
        <h2 style="text-transform:uppercase; font-weight:bold;">PT.PRISCOLIN</h2>
        <h3 style="text-transform:uppercase; font-weight:bold;">DAILY PRODUCTION FRACTIONATION REPORT</h3>
        <p>Date: {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</p>
    </div>

    @if (!empty($workCenter))
        {{-- Single Work Center --}}
        <div class="text-center" style="margin:5px 0;">
            <h4 style="font-weight:bold;">Work Center: {{ $workCenter }}</h4>
        </div>
        @include('rpt_daily_production.fractionation._table_dailyPFra', ['rows' => $data])
    @else
        {{-- Grouped by Work Center --}}
        @foreach ($groupedData as $wc => $rows)
            <div class="text-center" style="margin:5px 0;">
                <h4 style="font-weight:bold;">Work Center: {{ $wc }}</h4>
            </div>
            @include('rpt_daily_production.fractionation._table_dailyPFra', ['rows' => $rows])
            @if (!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    @endif

    {{-- Signature section --}}
    <div class="mt-8">
        <table class="signature-table" width="100%">
            <tr>
                @foreach (['1' => 'SHIFT 1', '2' => 'SHIFT 2', '3' => 'SHIFT 3'] as $key => $label)
                    <td>
                        Prepared by: ({{ $label }})<br><br><br>
                        @if (isset($signatures[$key]))
                            <strong>({{ $signatures[$key]['name'] }})</strong><br>
                            {{ \Carbon\Carbon::parse($signatures[$key]['date'])->format('d-m-Y H:i') }}
                        @else
                            (_________________)
                            <br>
                            -
                        @endif
                    </td>
                @endforeach
                <td>
                    Checked By<br><br><br>
                    @php $first = $data->first() ?? $groupedData->first()->first() ?? null; @endphp
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
