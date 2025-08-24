@extends('layouts.app')

@section('page_title', 'Checklist Lamps and Glass Control')

@section('content')
    <div class="bg-white p-6 rounded shadow-md text-sm relative">
        {{-- Header Info --}}
        <div class="absolute top-4 right-6 text-xs leading-tight text-left">
            <div><strong>Form No.</strong> : F/RFA-013</div>
            <div><strong>Date Issued</strong> : 200701</div>
            <div><strong>Revision</strong> : 01</div>
            <div><strong>Rev. Date</strong> : 210901</div>
        </div>

        {{-- Judul --}}
        <div class="text-center mb-4">
            <h2 class="text-lg font-bold uppercase">PT.PRISCOLIN</h2>
            <h3 class="text-xl font-bold uppercase">CHECKLIST LAMPS AND GLASS CONTROL</h3>
            <div class="mt-1">Section: Ref-Frac</div>
            <div class="mt-1">Month: {{ $month ?? '' }} {{ $year ?? '' }}</div>
        </div>

        @php
            // Konversi nama bulan menjadi angka bulan (1–12)
            $monthNumber = \Carbon\Carbon::parse("1 $month $year")->month;
        @endphp

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-400 text-center text-xs">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 w-8" rowspan="2">No</th>
                        <th class="border px-2 py-1 w-20 max-w-xs truncate" rowspan="2">Check/Item Pemeriksaan</th>
                        <th class="border px-2 py-1" colspan="31">Date</th>
                        <th class="border px-2 py-1 w-20" rowspan="2">Remark</th>
                    </tr>
                    <tr>
                        @for ($day = 1; $day <= 31; $day++)
                            <th class="border px-1 py-1 w-6">{{ $day }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @php
                        $allDetails = collect();
                        foreach ($documents as $doc) {
                            foreach ($doc->details as $detail) {
                                if (!$allDetails->contains('check_item', $detail->check_item)) {
                                    $allDetails->push($detail);
                                }
                            }
                        }
                    @endphp

                    @foreach ($allDetails as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->check_item }}</td>
                            @for ($day = 1; $day <= 31; $day++)
                                @php
                                    $currentDate = \Carbon\Carbon::createFromDate($year, $monthNumber, $day)->format(
                                        'Y-m-d',
                                    );
                                    $status = null;
                                    foreach ($documents as $doc) {
                                        if (\Carbon\Carbon::parse($doc->check_date)->format('Y-m-d') === $currentDate) {
                                            foreach ($doc->details as $d) {
                                                if ($d->check_item === $detail->check_item) {
                                                    $status = $d->status_item;
                                                    break 2;
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                <td class="text-center">
                                    @if ($status === 'T')
                                        ✓
                                    @elseif ($status === 'F')
                                        ✗
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach

                    {{-- Baris paraf --}}
                    <tr>
                        <td class="border px-2 py-1" colspan="2">Paraf</td>
                        @for ($day = 1; $day <= 31; $day++)
                            @php
                                $currentDate = \Carbon\Carbon::createFromDate($year, $monthNumber, $day)->format(
                                    'Y-m-d',
                                );
                                $paraf = '';
                                foreach ($documents as $doc) {
                                    if (\Carbon\Carbon::parse($doc->check_date)->format('Y-m-d') === $currentDate) {
                                        // Tampilkan paraf jika checked_by ada dan status Approved
                                        if ($doc->checked_by && $doc->checked_status === 'Approved') {
                                            $paraf =
                                                $doc->checked_by .
                                                ' (' .
                                                \Carbon\Carbon::parse($doc->check_date)->format('d-m-Y H:i') .
                                                ')';
                                        }
                                        break; // cukup ambil dokumen pertama yang valid untuk tanggal ini
                                    }
                                }
                            @endphp
                            <td class="border px-1 py-4 text-center">{{ $paraf }}</td>
                        @endfor
                        <td class="border px-2 py-1"></td>
                    </tr>


                </tbody>
            </table>
        </div>

        @php
            $allApproved = true;

            // Loop tanggal 1 sampai akhir bulan
            $daysInMonth = \Carbon\Carbon::createFromDate($year, $monthNumber, 1)->daysInMonth;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = \Carbon\Carbon::createFromDate($year, $monthNumber, $day)->format('Y-m-d');

                $approvedForDay = false;

                foreach ($documents as $doc) {
                    if (\Carbon\Carbon::parse($doc->check_date)->format('Y-m-d') === $currentDate) {
                        if ($doc->checked_status === 'Approved') {
                            $approvedForDay = true;
                        }
                        break; // cukup cek dokumen pertama untuk tanggal ini
                    }
                }

                if (!$approvedForDay) {
                    $allApproved = false;
                    break; // cukup ada satu tanggal yang belum approved
                }
            }
        @endphp

        <div class="flex justify-between mt-6 text-xs">
            <div>
                <div><strong>Checked by (Leader)</strong></div>
                <div class="mt-10">________________</div>
            </div>

            <div>
                <div><strong>Verified by (Leader)</strong></div>
                <div class="mt-10">
                    @if ($allApproved)
                        {{ $documents[0]->verified_by ?? '________________' }}
                    @else
                        __________________
                    @endif
                </div>
            </div>
        </div>

        {{-- Informasi persetujuan elektronik --}}
        <div class="mt-6 text-center text-xs text-gray-600 italic">
            Dokumen ini telah disetujui secara elektronik melalui sistem [E-Logsheet],
            sehingga tidak memerlukan tanda tangan asli.
        </div>

    @endsection
