@extends('layouts.app')

@section('title', 'Logsheet Dry Fractionation')

@section('content')
    <div class="bg-white p-6 rounded shadow-md text-sm">
        {{-- Header --}}
        <div class="text-center mb-4">
            <h2 class="text-lg font-bold uppercase">PT Priscolin</h2>
            <h3 class="text-xl font-bold uppercase">LOGSHEET DRY FRACTIONATION</h3>
            <div class="mt-1">Date: 22-07-2025 | Shift: PP 500</div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-400 text-center text-xs">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-1 py-1">Crystallizer (Batch #)</th>
                        <th class="border px-1 py-1">Filling Start Time</th>
                        <th class="border px-1 py-1">Filling End Time</th>
                        <th class="border px-1 py-1">Cooling Start Time</th>
                        <th class="border px-1 py-1">Initial Oil Level (%)</th>
                        <th class="border px-1 py-1">Feed IV</th>
                        <th class="border px-1 py-1">Agitator Speed (Hz)</th>
                        <th class="border px-1 py-1">Water Pump Pres (bar)</th>
                        <th class="border px-1 py-1">Crystal Start Time & Temp</th>
                        <th class="border px-1 py-1">Filtration Start Time & Temp</th>
                        <th class="border px-1 py-1">Filtration Cycle Number</th>
                        <th class="border px-1 py-1">Final Oil Level (%)</th>
                        <th class="border px-1 py-1">Olein IV RED</th>
                        <th class="border px-1 py-1">Olein Cloud Point (°C)</th>
                        <th class="border px-1 py-1">Stearin IV</th>
                        <th class="border px-1 py-1">Stearin Slop Point (°C) RED</th>
                        <th class="border px-1 py-1">Olein Yield (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $data = [
                            [
                                '401',
                                '23:55',
                                '01:45',
                                '01:46',
                                '95',
                                '3.4',
                                '40',
                                '2.8',
                                '1:05 / 44',
                                '1:05 / 46',
                                '1',
                                '56',
                                '5.8',
                                '5.4',
                                '',
                                '',
                                '',
                            ],
                            [
                                '403',
                                '01:00',
                                '02:14',
                                '02:15',
                                '98',
                                '2.4',
                                '40',
                                '2.8',
                                '2:00 / 64',
                                '2:00 / 64',
                                '2',
                                '64',
                                '3.0',
                                '5.4',
                                '',
                                '',
                                '',
                            ],
                            [
                                '402',
                                '05:32',
                                '10:17',
                                '10:17',
                                '95',
                                '1.8',
                                '40',
                                '2.0',
                                '9:10 / 65',
                                '9:10 / 65',
                                '1',
                                '56',
                                '2.0',
                                '5.8',
                                '',
                                '',
                                '',
                            ],
                            [
                                '401',
                                '10:17',
                                '10:50',
                                '11:05',
                                '95',
                                '1.8',
                                '40',
                                '2.0',
                                '10:55 / 63',
                                '10:55 / 63',
                                '2',
                                '36',
                                '2.0',
                                '4.7',
                                '',
                                '',
                                '',
                            ],
                            [
                                '403',
                                '12:05',
                                '12:16',
                                '11:08',
                                '98',
                                '1.8',
                                '40',
                                '5.0',
                                '12:10 / 64',
                                '12:10 / 64',
                                '2',
                                '64',
                                '2.0',
                                '5.0',
                                '',
                                '',
                                '',
                            ],
                            [
                                '404',
                                '10:46',
                                '11:32',
                                '11:50',
                                '98',
                                '1.8',
                                '40',
                                '2.0',
                                '10:47 / 65',
                                '13:00 / 65',
                                '2',
                                '64',
                                '2.0',
                                '5.0',
                                '',
                                '',
                                '',
                            ],
                            [
                                '402',
                                '20:25',
                                '21:05',
                                '21:05',
                                '95',
                                '1.8',
                                '40',
                                '2.0',
                                '1:05 / 65',
                                '21:00 / 65',
                                '1',
                                '36',
                                '2.0',
                                '5.0',
                                '',
                                '',
                                '',
                            ],
                            [
                                '403',
                                '21:00',
                                '23:00',
                                '23:00',
                                '92',
                                '1.8',
                                '40',
                                '2.0',
                                '1:05 / 66',
                                '23:00 / 66',
                                '3',
                                '32',
                                '2.0',
                                '5.0',
                                '',
                                '',
                                '',
                            ],
                        ];
                    @endphp

                    @foreach ($data as $row)
                        <tr>
                            @foreach ($row as $cell)
                                <td class="border px-1 py-1">{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Catatan --}}
        <div class="mt-6 text-xs italic">
            <strong>Note:</strong> Filtrasi terganggu dikarenakan ada tapping jalur untuk olein jam 10:00 s/d 12:00<br>
            Jam 18:40 washing (23-07-25)
        </div>

        {{-- Tanda tangan --}}
        <div class="grid grid-cols-2 text-center mt-10 text-xs">
            <div>
                <strong>Prepared by,<br>Leader Shift</strong><br><br><br>
                __________________<br>(Name & Sign)
            </div>
            <div>
                <strong>Acknowledge by,<br>SPV</strong><br><br><br>
                __________________<br>(Name & Sign)
            </div>
        </div>
    </div>
@endsection
