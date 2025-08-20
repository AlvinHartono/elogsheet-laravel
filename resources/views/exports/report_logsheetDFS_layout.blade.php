@extends('layouts.app')

@section('title', 'Logsheet Deodorizing & Filtration')

@section('content')
    <div class="bg-white p-6 rounded shadow-md text-sm">
        {{-- Header --}}
        <div class="text-center mb-4">
            <h2 class="text-lg font-bold uppercase">PT.PRISCOLIN</h2>
            <h3 class="text-xl font-bold uppercase">LOGSHEET DEODORIZING & FILTRATION SECTION</h3>
            <div class="mt-1">Refinery Plant: 150 TPD</div>
            <div class="mt-1">Tanggal: {{ now()->format('d F Y') }}</div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-400 text-center text-xs">
                <thead class="bg-gray-100">
                    <tr>
                        <th rowspan="2" class="border px-2 py-2">Time</th>
                        <th rowspan="2" class="border px-2 py-2">FTT701<br>(BPO)</th>
                        <th rowspan="2" class="border px-2 py-2">Vacuum T 701<br>(cmHg)</th>
                        <th rowspan="2" class="border px-2 py-2">E702<br>(°C)</th>
                        <th colspan="2" class="border px-2 py-2">Thermopac</th>
                        <th colspan="3" class="border px-2 py-2">D702</th>
                        <th rowspan="2" class="border px-2 py-2">Sparging</th>
                        <th colspan="2" class="border px-2 py-2">E703</th>
                        <th colspan="2" class="border px-2 py-2">PISH</th>
                        <th colspan="2" class="border px-2 py-2">TWH</th>
                        <th colspan="3" class="border px-2 py-2">F702</th>
                        <th rowspan="2" class="border px-2 py-2">FIT704<br>(RPO)</th>
                        <th colspan="2" class="border px-2 py-2">E704</th>
                        <th colspan="2" class="border px-2 py-2">FIT705 (PFAD)</th>
                        <th rowspan="2" class="border px-2 py-2">E705<br>(°C)</th>
                        <th rowspan="2" class="border px-2 py-2">Clarity</th>
                        <th rowspan="2" class="border px-2 py-2">Remarks</th>
                    </tr>
                    <tr>
                        <th class="border px-2 py-2">Inlet</th>
                        <th class="border px-2 py-2">Outlet</th>
                        <th class="border px-2 py-2">Inlet</th>
                        <th class="border px-2 py-2">Outlet</th>
                        <th class="border px-2 py-2">Vacuum</th>
                        <th class="border px-2 py-2">A</th>
                        <th class="border px-2 py-2">B</th>
                        <th class="border px-2 py-2">Inlet</th>
                        <th class="border px-2 py-2">Steam</th>
                        <th class="border px-2 py-2">706</th>
                        <th class="border px-2 py-2">706A</th>
                        <th class="border px-2 py-2">A</th>
                        <th class="border px-2 py-2">B</th>
                        <th class="border px-2 py-2">C</th>
                        <th class="border px-2 py-2">tph</th>
                        <th class="border px-2 py-2">°C</th>
                        <th class="border px-2 py-2">°C</th>
                        <th class="border px-2 py-2">°C</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $times = collect(range(8, 23))->merge(range(0, 7));
                    @endphp
                    @foreach ($times as $hour)
                        <tr>
                            <td class="border px-2 py-2">{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</td>
                            <td class="border px-2 py-2">4.5</td>
                            <td class="border px-2 py-2">76</td>
                            <td class="border px-2 py-2">88</td>
                            <td class="border px-2 py-2">230</td>
                            <td class="border px-2 py-2">260</td>
                            <td class="border px-2 py-2">210</td>
                            <td class="border px-2 py-2">240</td>
                            <td class="border px-2 py-2">4.2</td>
                            <td class="border px-2 py-2">1</td>
                            <td class="border px-2 py-2">175</td>
                            <td class="border px-2 py-2">9.1</td>
                            <td class="border px-2 py-2">29</td>
                            <td class="border px-2 py-2">0.1</td>
                            <td class="border px-2 py-2">4.6</td>
                            <td class="border px-2 py-2">62</td>
                            <td class="border px-2 py-2">2</td>
                            <td class="border px-2 py-2">70</td>
                            <td class="border px-2 py-2">4.7</td>
                            <td class="border px-2 py-2">60</td>
                            <td class="border px-2 py-2">2</td>
                            <td class="border px-2 py-2">70</td>
                            <td class="border px-2 py-2">clear</td>
                            <td class="border px-2 py-2">-</td>
                            <td class="border px-2 py-2">-</td>
                            <td class="border px-2 py-2">-</td>
                            <td class="border px-2 py-2">-</td>
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
