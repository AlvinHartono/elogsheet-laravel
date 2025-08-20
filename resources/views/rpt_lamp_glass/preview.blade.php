@extends('layouts.app')

@section('title', 'Checklist Lamps and Glass Control')

@section('content')
    {{-- <div class="bg-white p-6 rounded shadow-md text-sm"> --}}

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
            <div class="mt-1">Month: </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-400 text-center text-xs">
                <thead class="bg-gray-100">
                    {{-- Baris 1 --}}
                    <tr>
                        <th class="border px-2 py-1 w-8" rowspan="2">No</th>
                        <th class="border px-2 py-1 w-28" rowspan="2">Check/Item Pemeriksaan</th>
                        <th class="border px-2 py-1" colspan="31">Date</th>
                        <th class="border px-2 py-1 w-20" rowspan="2">Remark</th>
                    </tr>
                    {{-- Baris 2: angka tanggal --}}
                    <tr>
                        @for ($day = 1; $day <= 31; $day++)
                            <th class="border px-1 py-1 w-6">{{ $day }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 20; $i++) {{-- contoh 20 item --}}
                        <tr>
                            <td class="border px-2 py-1">{{ $i }}</td>
                            <td class="border px-2 py-1">IC{{ 20 + $i }}</td>
                            @for ($day = 1; $day <= 31; $day++)
                                <td class="border px-1 py-1">
                                    @if (rand(0, 1))
                                        ✓
                                    @endif
                                </td>
                            @endfor
                            <td class="border px-2 py-1"></td>
                        </tr>
                    @endfor
                    {{-- Baris paraf --}}
                    <tr>
                        <td class="border px-2 py-1" colspan="2">Paraf</td>
                        @for ($day = 1; $day <= 31; $day++)
                            <td class="border px-1 py-4"></td>
                        @endfor
                        <td class="border px-2 py-1"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Signature Section --}}
        <div class="flex justify-between mt-6 text-xs">
            <div>
                <div><strong>Checked by (Leader)</strong></div>
                <div class="mt-10">________________</div>
            </div>
            <div>
                <div><strong>Verified by</strong></div>
                <div class="mt-10">________________</div>
            </div>
        </div>
    </div>
@endsection
