@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow" x-data="{ tab: 'input' }">

        <!-- Header -->
        <div class="flex items-center space-x-3 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21V9l6-3v6l6-3v12H3zm12 0h6V9h-6v12z" />
            </svg>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Monitoring Dry Fractionation Plant</h2>
                <div class="text-sm text-gray-600 mt-1">
                    <span class="font-medium text-gray-700">Logsheet Code:</span>
                    <span class="inline-block px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded">
                        F-RFA-010
                    </span>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="flex space-x-6">
                <button @click="tab = 'input'"
                    :class="tab === 'input' ? 'border-blue-600 text-blue-600' :
                        'border-transparent text-gray-600 hover:text-blue-600'"
                    class="py-2 px-1 border-b-2 font-medium text-sm">
                    Input Data
                </button>
                <button @click="tab = 'data'"
                    :class="tab === 'data' ? 'border-blue-600 text-blue-600' :
                        'border-transparent text-gray-600 hover:text-blue-600'"
                    class="py-2 px-1 border-b-2 font-medium text-sm">
                    Data Tersimpan
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div>
            <!-- Form Input -->
            <!-- Form Input -->
            <div x-show="tab === 'input'">
                <form action="{{ route('dryfrac.store') }}" method="POST">
                    @csrf

                    <!-- Form Header Info -->
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 mb-6 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" name="tanggal"
                                    class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Work Center</label>
                                <select name="work_center"
                                    class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition">
                                    <option value=""> Pilih Work Center </option>
                                    @foreach ($workCenter as $wc)
                                        <option value="{{ $wc->code }}">{{ $wc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Oil Processed / Plant</label>
                                <select name="oil_type"
                                    class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition">
                                    <option value=""> Pilih Oil Type </option>
                                    @foreach ($oilType as $ot)
                                        <option value="{{ $ot->code }}">{{ $ot->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Table Input -->
                    <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                        <h3 class="text-md font-semibold mb-4 text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 21V9l6-3v6l6-3v12H3zm12 0h6V9h-6v12z" />
                            </svg>
                            Detail Batch
                        </h3>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 text-gray-700 text-sm">
                                    <tr>
                                        <th class="px-3 py-2 text-left font-semibold">Crystallizer Batch #</th>
                                        <th class="px-3 py-2 text-left font-semibold">Filling Start Time</th>
                                        <th class="px-3 py-2 text-left font-semibold">Filling End Time</th>
                                        <th class="px-3 py-2 text-left font-semibold">Cooling Start Time</th>
                                        <th class="px-3 py-2 text-left font-semibold">Initial Oil Level %</th>
                                        <th class="px-3 py-2 text-left font-semibold">Initial Tank</th>
                                        <th class="px-3 py-2 text-left font-semibold">Feed IV</th>
                                        <th class="px-3 py-2 text-left font-semibold">Agitator (Hz)</th>
                                        <th class="px-3 py-2 text-left font-semibold">Pump (bar)</th>
                                        <th class="px-3 py-2 text-left font-semibold">Crystal Start</th>
                                        <th class="px-3 py-2 text-left font-semibold">Crystal Temp</th>
                                        <th class="px-3 py-2 text-left font-semibold">Filtration Start</th>
                                        <th class="px-3 py-2 text-left font-semibold">Filtration Temp</th>
                                        <th class="px-3 py-2 text-left font-semibold">Filtration Cycle #</th>
                                        <th class="px-3 py-2 text-left font-semibold">Final Oil %</th>
                                        <th class="px-3 py-2 text-left font-semibold">Olein IV</th>
                                        <th class="px-3 py-2 text-left font-semibold">Cloud Point</th>
                                        <th class="px-3 py-2 text-left font-semibold">Stearin IV</th>
                                        <th class="px-3 py-2 text-left font-semibold">Slip Point</th>
                                        <th class="px-3 py-2 text-left font-semibold">Yield %</th>
                                        <th class="px-3 py-2 text-center font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="batchTable" class="text-gray-600">
                                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 transition">
                                        <td class="border px-2 py-1">
                                            <input type="text" name="batch[0][crystallizer]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="time" name="batch[0][filling_start_time]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="time" name="batch[0][filling_end_time]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="time" name="batch[0][cooling_start_time]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="number" name="batch[0][initial_oil_level]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="number" name="batch[0][initial_tank]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="number" name="batch[0][feed_iv]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="number" name="batch[0][agitator]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="number" name="batch[0][pump]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="text" name="batch[0][crystal_start]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="text" name="batch[0][crystal_temp]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="text" name="batch[0][filtration_start]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="text" name="batch[0][filtration_temp]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="number" name="batch[0][filtration_cycle]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="number" name="batch[0][final_oil]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="number" name="batch[0][olein_iv]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <td class="border px-2 py-1">
                                            <input type="number" name="batch[0][cloud_point]"
                                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                                        </td>
                                        <div>
                                            <div class="flex items-center space-x-3 mb-1">
                                                <!-- Lampu -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 2a7 7 0 0 0-7 7c0 2.5 1.5 4.7 3.5 6a3 3 0 0 1 1.5 2.6V20h4v-2.4a3 3 0 0 1 1.5-2.6c2-1.3 3.5-3.5 3.5-6a7 7 0 0 0-7-7z" />
                                                </svg>
                                                <div>
                                                    <h2 class="text-lg font-semibold text-gray-800">Lamps and Glass Control
                                                    </h2>
                                                    <div class="text-sm text-gray-600 mt-1">
                                                        <span class="font-medium text-gray-700">Logsheet Code:</span>
                                                        <span
                                                            class="inline-block px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">
                                                            F-RFA-013
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('report-lampnglass.export', ['filter_tanggal' => $selectedDate]) }}"
                                                target="_blank"
                                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2"
                                                    fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                                                </svg>
                                                Export Excel
                                            </a>
                                            <a href="{{ route('report-lampnglass.export.view') }}"
                                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />
                                                </svg>
                                                View Layout
                                            </a>
                                            <div class="flex flex-col sm:flex-row gap-2">
                                                {{-- Tombol Download --}}
                                                <a href="{{ route('report-lampnglass.export.pdf', ['filter_tanggal' => $selectedDate, 'mode' => 'preview']) }}"
                                                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow transition"
                                                    target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M7 10l5 5 5-5M12 4v12" />
                                                    </svg>
                                                    Download PDF
                                                </a>
                                            </div>
                                        </div>
                        </div>
                        <td class="border px-2 py-1">
                            <input type="number" name="batch[0][stearin_iv]"
                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                        </td>
                        <td class="border px-2 py-1">
                            <input type="number" name="batch[0][slip_point]"
                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                        </td>
                        <td class="border px-2 py-1">
                            <input type="number" name="batch[0][yield]"
                                class="w-full rounded-md border-gray-300 text-sm px-2 py-1 focus:ring-2 focus:ring-blue-500">
                        </td>
                        <td class="border px-2 py-1 text-center">
                            <button type="button" onclick="deleteRow(this)"
                                class="p-1 rounded-full bg-red-100 hover:bg-red-200 text-red-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </td>
                        </tr>
                        </tbody>
                        </table>
                    </div>

                    <!-- Add Batch -->
                    <button type="button" onclick="addRow()"
                        class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Batch
                    </button>
            </div>

            <!-- Notes -->
            <div class="mt-6 bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                <label class="text-sm font-medium text-gray-700 mb-2 block">Catatan</label>
                <textarea name="catatan" rows="3"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 text-sm p-2"
                    placeholder="Tulis catatan tambahan di sini..."></textarea>
            </div>

            <!-- Action -->
            <div class="mt-6 flex justify-end gap-2">
                <button type="reset"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition">
                    Reset
                </button>
                <button type="submit"
                    class="inline-flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16v2a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h2M15 2h6v6M10 14L21 3" />
                    </svg>
                    Simpan Data
                </button>
            </div>
            </form>
        </div>


        <!-- Data Tersimpan -->
        <div x-show="tab === 'data'">
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-md font-semibold mb-4 text-gray-800">Data Tersimpan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="border p-2">Tanggal</th>
                                <th class="border p-2">Work Center</th>
                                <th class="border p-2">Oil Type</th>
                                <th class="border p-2">Batch</th>
                                <th class="border p-2">Yield %</th>
                                <th class="border p-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @forelse($savedData as $row)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border p-2">{{ $row->tanggal }}</td>
                                        <td class="border p-2">{{ $row->work_center }}</td>
                                        <td class="border p-2">{{ $row->oil_type }}</td>
                                        <td class="border p-2">{{ $row->crystallizer }}</td>
                                        <td class="border p-2">{{ $row->yield }}%</td>
                                        <td class="border p-2">
                                            <a href="{{ route('dryfrac.show', $row->id) }}"
                                                class="text-blue-600 hover:underline">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-gray-500 p-4">Belum ada data tersimpan
                                        </td>
                                    </tr>
                                @endforelse --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
    </div>

    <script>
        let batchIndex = 1; // mulai dari 1 karena row awal = 0

        function addRow() {
            const table = document.getElementById('batchTable');
            const newRow = table.rows[0].cloneNode(true);

            // Update name pakai batchIndex manual
            newRow.querySelectorAll('input').forEach(input => {
                input.value = '';
                input.name = input.name.replace(/\[\d+\]/, `[${batchIndex}]`);
            });

            batchIndex++; // increment biar urut
            table.appendChild(newRow);
        }

        function deleteRow(button) {
            const row = button.closest('tr');
            const table = document.getElementById('batchTable');
            if (table.rows.length > 1) {
                row.remove();
            } else {
                alert("Minimal 1 batch harus ada.");
            }
        }
    </script>
@endsection
