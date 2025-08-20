@extends('layouts.app')

@section('title', 'Logsheet Pretreatment, Bleaching & Filtration')

@section('content')
    <div class="bg-white p-6 rounded shadow-md text-sm">
        {{-- Header --}}
        <div class="text-center mb-4">
            <h2 class="text-lg font-bold uppercase">PT PRISCOLIN</h2>
            <h3 class="text-xl font-bold uppercase">LOGSHEET PRETREATMENT, BLEACHING & FILTRATION</h3>
            <div class="mt-1">Refinery Plant: 150 TPD</div>
            <div class="mt-1">Tanggal: {{ now()->format('d F Y') }}</div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-400 text-center text-xs">
                <thead class="bg-gray-100">
                    <tr>
                        <th rowspan="2" class="border px-2 py-1">Time</th>
                        <th rowspan="2" class="border px-2 py-1">FTT001<br>(TPH)</th>
                        <th rowspan="2" class="border px-2 py-1">E001A<br>(°C)</th>
                        <th rowspan="2" class="border px-2 py-1">F001/2<br>(bar)</th>
                        <th rowspan="2" class="border px-2 py-1">H₃PO₄<br>(%)</th>
                        <th rowspan="2" class="border px-2 py-1">BE<br>(%)</th>
                        <th rowspan="2" class="border px-2 py-1">Vacuum<br>(mmHg)</th>

                        <th colspan="3" class="border px-2 py-1">Bleacher (B602)</th>
                        <th colspan="3" class="border px-2 py-1">Pump (P602)</th>
                        <th colspan="6" class="border px-2 py-1">Niagara Filter</th>
                        <th colspan="2" class="border px-2 py-1">Filter Bag</th>
                        <th rowspan="2" class="border px-2 py-1">Clarity</th>
                        <th rowspan="2" class="border px-2 py-1">Remarks</th>
                    </tr>
                    <tr>
                        <th class="border px-1 py-1">T-inlet (°C)</th>
                        <th class="border px-1 py-1">T B602 (°C)</th>
                        <th class="border px-1 py-1">Spurge (bar)</th>

                        <th class="border px-1 py-1">A</th>
                        <th class="border px-1 py-1">B</th>
                        <th class="border px-1 py-1">C</th>

                        <th class="border px-1 py-1">F601</th>
                        <th class="border px-1 py-1">F602</th>
                        <th class="border px-1 py-1">F603</th>
                        <th class="border px-1 py-1">F604A</th>
                        <th class="border px-1 py-1">F604B</th>
                        <th class="border px-1 py-1">F605A</th>

                        <th class="border px-1 py-1">F605B</th>
                        <th class="border px-1 py-1">Cart.</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $times = collect(range(8, 23))->merge(range(0, 7));
                    @endphp
                    @foreach ($times as $hour)
                        <tr>
                            <td class="border px-1 py-1">{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</td>
                            <td class="border px-1 py-1">5</td>
                            <td class="border px-1 py-1">46</td>
                            <td class="border px-1 py-1">6</td>
                            <td class="border px-1 py-1">0.06</td>
                            <td class="border px-1 py-1">0.6</td>
                            <td class="border px-1 py-1">-680</td>

                            <td class="border px-1 py-1">110</td>
                            <td class="border px-1 py-1">105</td>
                            <td class="border px-1 py-1">2</td>

                            <td class="border px-1 py-1">4.5</td>
                            <td class="border px-1 py-1">4.5</td>
                            <td class="border px-1 py-1">4.5</td>

                            <td class="border px-1 py-1">2</td>
                            <td class="border px-1 py-1">2</td>
                            <td class="border px-1 py-1">2</td>
                            <td class="border px-1 py-1">0.1</td>
                            <td class="border px-1 py-1">0.1</td>
                            <td class="border px-1 py-1">0.1</td>

                            <td class="border px-1 py-1">0.1</td>
                            <td class="border px-1 py-1">0.1</td>
                            <td class="border px-1 py-1">clear</td>
                            <td class="border px-1 py-1"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Signature --}}
        <div class="grid grid-cols-4 text-center mt-10 text-xs">
            <div>
                <strong>Leader Shift I</strong><br><br><br>________________<br>(Nama, Paraf)
            </div>
            <div>
                <strong>Leader Shift II</strong><br><br><br>________________<br>(Nama, Paraf)
            </div>
            <div>
                <strong>Leader Shift III</strong><br><br><br>________________<br>(Nama, Paraf)
            </div>
            <div>
                <strong>Approved by:</strong><br><br><br>________________<br>(Sect. Head)
            </div>
        </div>
    </div>
@endsection
