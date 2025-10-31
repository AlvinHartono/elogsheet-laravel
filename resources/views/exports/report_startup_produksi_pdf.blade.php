<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Start Up Produksi Checklist - {{ $header->id }}</title>
    <style>
        body {
            font-size: 10px;
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 4px;
            vertical-align: top;
        }

        /* Helper Classes */
        .no-border {
            border: none;
        }

        .no-border td,
        .no-border th {
            border: none;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .font-bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .mt-6 {
            margin-top: 24px;
        }

        .mt-10 {
            margin-top: 40px;
        }

        .w-full {
            width: 100%;
        }

        .text-xs {
            font-size: 9px;
        }

        .italic {
            font-style: italic;
        }

        .bg-gray {
            background-color: #f3f3f3;
        }

        /* Use DejaVu Sans for special characters like ticks */
        .symbol {
            font-family: DejaVu Sans, sans-serif;
        }

        .check-item {
            height: 16px;
        }

        .text-green {
            color: green;
        }

        .text-red {
            color: red;
        }
    </style>
</head>

<body>
    {{-- Header Table (Logo, Title, Form Info) --}}
    <table class="w-full mb-4 no-border">
        <tbody>
            <tr style="vertical-align: top;">
                {{-- Column 1: Logo and Bekasi --}}
                <td class="w-1-5 text-center no-border" style="width: 20%;">
                    {{-- dompdf requires a full path to the image --}}
                    <img src="{{ public_path('images/KPN Corp.jpg') }}" alt="Logo"
                        style="height: 48px; margin: 0 auto 4px auto;">
                    <span class="font-bold">Bekasi</span>
                </td>

                {{-- Column 2: Titles --}}
                <td class="w-3-5 text-center no-border" style="width: 60%; padding-top: 8px;">
                    <h3 class="uppercase" style="font-size: 16px; margin: 0;">START UP PRODUKSI <br>CHECKLIST</h3>
                </td>

                {{-- Column 3: Form Info --}}
                <td class="w-1-5 no-border" style="width: 20%;">
                    <table class="text-xs" style="border: 1px solid #444;">
                        <tr>
                            <td style="border: none; padding: 2px;"><strong>Form No.</strong></td>
                            <td style="border: none; padding: 2px;">: {{ $header->form_no ?? 'F-RFA-015' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 2px;"><strong>Date Issued</strong></td>
                            <td style="border: none; padding: 2px;">:
                                {{ $header->form_date_issued ? \Carbon\Carbon::parse($header->form_date_issued)->format('ymd') : '241019' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 2px;"><strong>Revision</strong></td>
                            <td style="border: none; padding: 2px;">: {{ $header->form_revision ?? '00' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 2px;"><strong>Rev. Date</strong></td>
                            <td style="border: none; padding: 2px;">:
                                {{ $header->form_rev_date ? \Carbon\Carbon::parse($header->form_rev_date)->format('ymd') : '00' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Header Info Section --}}
    <table class="mb-4">
        <tbody>
            <tr>
                <td style="width: 50%;">
                    <strong>Tanggal</strong>:
                    {{ $header->transaction_date ? \Carbon\Carbon::parse($header->transaction_date)->format('d-m-Y') : '' }}
                    <br>
                    <strong>Jam</strong>: {{ $header->transaction_time ?? '' }} <br>
                    <strong>Plant</strong>: {{ $header->plant ?? '' }} <br>
                    <strong>Work Center</strong>: {{ $header->work_center ?? '' }}
                </td>
                <td style="width: 50%;">
                    <strong>First Product</strong>:
                    {{ $header->product->raw_material ?? 'N/A' }} <br>
                    <strong>Remarks</strong>: {{ $header->remarks ?? '-' }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- 1. Define the categories for each table (Same as preview) --}}
    @php
        $refineryCategoryNames = ['Pre Treatment Section', 'Bleacher Section', 'Deodorization Section'];
        $refineryGroups = $groupedDetails->filter(function ($details, $category) use ($refineryCategoryNames) {
            return in_array($category, $refineryCategoryNames);
        });
        $fractionationGroups = $groupedDetails->reject(function ($details, $category) use ($refineryCategoryNames) {
            return in_array($category, $refineryCategoryNames);
        });
    @endphp

    {{-- Table 1: Refinery Plant --}}
    <h4 class="font-bold mb-2 mt-4" style="font-size: 12px; margin-bottom: 4px;">Refinery Plant</h4>
    <table class="w-full">
        <thead class="bg-gray">
            <tr>
                <th class="text-center" style="width: 15%;">Checklist</th>
                <th class="text-left" style="width: 85%;">Check Item / Parameter</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($refineryGroups as $category => $details)
                {{-- Category Header --}}
                <tr>
                    <td colspan="2" class="font-bold bg-gray">
                        {{ $category }}
                    </td>
                </tr>
                {{-- Checklist Items (Details) --}}
                @foreach ($details as $detail)
                    <tr>
                        <td class="text-center check-item">
                            @if ($detail->status_item == 'T')
                                <span class="font-bold text-green symbol">&#10003;</span>
                            @elseif ($detail->status_item == 'F')
                                <span class="font-bold text-red symbol"></span>
                            @else
                                <span class="text-xs">{{ $detail->status_item }}</span>
                            @endif
                        </td>
                        <td>
                            {{ $detail->langkahkerjaStartup->sort_no ?? '' }} &nbsp;
                            {{ $detail->langkahkerjaStartup->name ?? 'Unknown Item' }}
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="2" class="text-center italic">
                        No items found for Refinery Plant.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Table 2: Fractionation --}}
    <h4 class="font-bold mb-2 mt-2" style="font-size: 12px; margin-bottom: 4px;">Fractionation Plant</h4>
    <table class="w-full">
        <thead class="bg-gray">
            <tr>
                <th class="text-center" style="width: 15%;">Checklist</th>
                <th class="text-left" style="width: 85%;">Check Item / Parameter</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($fractionationGroups as $category => $details)
                {{-- Category Header --}}
                <tr>
                    <td colspan="2" class="font-bold bg-gray">
                        {{ $category }}
                    </td>
                </tr>
                {{-- Checklist Items (Details) --}}
                @foreach ($details as $detail)
                    <tr>
                        <td class="text-center check-item">
                            @if ($detail->status_item == 'T')
                                <span class="font-bold text-green symbol">&#10003;</span>
                            @elseif ($detail->status_item == 'F')
                                <span class="font-bold text-red symbol"></span>
                            @else
                                <span class="text-xs">{{ $detail->status_item }}</span>
                            @endif
                        </td>
                        <td>
                            {{ $detail->langkahkerjaStartup->sort_no ?? '' }} &nbsp;
                            {{ $detail->langkahkerjaStartup->name ?? 'Unknown Item' }}
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="2" class="text-center italic">
                        No items found for Fractionation.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Signature Section --}}
    <table class="mt-10 no-border text-center text-xs">
        <tbody>
            <tr>
                <td class="no-border" style="width: 33.3%;">
                    <strong>Done by,</strong><br>
                    (Operator)<br>
                    <br><br><br>
                    ( {{ $header->entry_by ?? '_______________________' }} )<br>
                    <small>Date:
                        {{ $header->entry_date ? \Carbon\Carbon::parse($header->entry_date)->format('d-m-Y H:i') : '' }}</small>
                </td>
                <td class="no-border" style="width: 33.3%;">
                    <strong>Prepared by:</strong><br>
                    (Shift Leader)<br>
                    <br><br><br>
                    ( {{ $header->prepared_by ?? '_______________________' }} )<br>
                    <small>Date:
                        {{ $header->prepared_date ? \Carbon\Carbon::parse($header->prepared_date)->format('d-m-Y H:i') : '' }}</small>
                </td>
                <td class="no-border" style="width: 33.3%;">
                    <strong>Checked by:</strong><br>
                    (Section Head)<br>
                    <br><br><br>
                    ( {{ $header->checked_by ?? '_______________________' }} )<br>
                    <small>Date:
                        {{ $header->checked_date ? \Carbon\Carbon::parse($header->checked_date)->format('d-m-Y H:i') : '' }}</small>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Electronic Approval Footer --}}
    <div class="mt-6 text-center text-xs italic" style="color: #555;">
        Dokumen ini telah disetujui secara elektronik melalui sistem [E-Logsheet],
        sehingga tidak memerlukan tanda tangan asli.
    </div>

</body>

</html>
