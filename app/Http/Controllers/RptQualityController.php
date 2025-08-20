<?php

namespace App\Http\Controllers;

use App\Exports\LSQualityExport;
use App\Models\LSQualityReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class RptQualityController extends Controller
{
    public function index(Request $request)
    {
        $query = LSQualityReport::query();

        // Gunakan hari ini jika filter_tanggal tidak diisi
        $tanggal = $request->input('filter_tanggal', Carbon::today()->format('Y-m-d'));

        $query->whereDate('posting_date', $tanggal);

        // Filter by time (jika diisi)
        if ($request->filled('filter_jam')) {
            $query->where('time', $request->filter_jam);
        }

        // Ambil data utama dengan custom order
        $reports = $query
            ->reorder()
            ->orderByRaw("CASE WHEN time >= '08:00' THEN 0 ELSE 1 END")
            ->orderBy('shift', 'asc')
            ->orderBy('time', 'asc')
            ->paginate(8)
            ->withQueryString();

        // --- Pengecekan status shift ---
        $shiftStatuses = [
            'shift1' => $this->checkShiftStatus($tanggal, '08:00:00', '15:00:00'),
            'shift2' => $this->checkShiftStatus($tanggal, '15:00:01', '23:59:59'),
            'shift3' => $this->checkShiftStatus($tanggal, '00:00:00', '07:59:59'),
        ];

        return view('rpt_quality.index', compact('reports', 'shiftStatuses'));
    }

    public function show($id)
    {
        $report = LSQualityReport::findOrFail($id);
        return view('rpt_quality.show', compact('report'));
    }

    public function exportExcel(Request $request)
    {
        $filterTanggal = $request->input('filter_tanggal', Carbon::today()->format('Y-m-d'));
        $filename = 'logsheet_quality_report_' . Carbon::parse($filterTanggal)->format('Y_m_d') . '.xlsx';

        return Excel::download(new LSQualityExport($filterTanggal), $filename);
    }

    // public function exportLayoutPreview(Request $request)
    // {
    //     $selectedDate = $request->input('filter_tanggal', now()->toDateString());

    //     $data = LSQualityReport::whereDate('posting_date', $selectedDate)
    //         ->orderByRaw("CASE
    //         WHEN time >= '08:00:00' AND time <= '15:00:00' THEN 1
    //         WHEN time >= '15:00:01' AND time <= '23:59:59' THEN 2
    //         WHEN time >= '00:00:00' AND time <= '07:59:59' THEN 3
    //         ELSE 4 END")
    //         ->orderBy('time', 'asc')
    //         ->get();

    //     $shiftRanges = [
    //         'shift1' => ['08:00:00', '15:00:00', 'prepared_status_shift1'],
    //         'shift2' => ['15:00:01', '23:59:59', 'prepared_status_shift2'],
    //         'shift3' => ['00:00:00', '07:59:59', 'prepared_status_shift3'],
    //     ];

    //     $signatures = [];

    //     foreach ($shiftRanges as $shift => [$start, $end, $statusField]) {
    //         $shiftData = $data->whereBetween('time', [$start, $end]);

    //         if ($shiftData->count() > 0 && $shiftData->every(fn($row) => $row->$statusField === 'Approved')) {
    //             $signatures[$shift] = $shiftData->last(); // simpan row terakhir
    //         } else {
    //             $signatures[$shift] = null;
    //         }
    //     }

    //     return view('rpt_quality.preview', compact('data', 'selectedDate', 'signatures'));
    // }
    public function exportLayoutPreview(Request $request)
    {
        $selectedDate = $request->input('filter_tanggal', now()->toDateString());

        // Data tabel utama (untuk grid)
        $data = LSQualityReport::whereDate('posting_date', $selectedDate)
            ->orderByRaw("CASE
            WHEN time >= '08:00:00' AND time <= '15:00:00' THEN 1
            WHEN time >= '15:00:01' AND time <= '23:59:59' THEN 2
            WHEN time >= '00:00:00' AND time <= '07:59:59' THEN 3
            ELSE 4 END")
            ->orderBy('time', 'asc')
            ->get();

        // Helper ambil tanda tangan per shift (baris terakhir yang Approved)
        $getShiftSignature = function (string $date, string $start, string $end, int $shiftNo) {
            $statusField = "prepared_status_shift{$shiftNo}";
            $nameField   = "prepared_by_shift{$shiftNo}";
            $dateField   = "prepared_date_shift{$shiftNo}";

            $row = LSQualityReport::whereDate('posting_date', $date)
                ->whereBetween('time', [$start, $end])
                ->where($statusField, 'Approved')
                ->orderBy('time', 'desc') // ambil yang paling akhir di shift tsb
                ->first([$nameField . ' as name', $dateField . ' as date']);

            return $row ? ['name' => $row->name, 'date' => $row->date] : null;
        };

        // Tanda tangan per shift
        $signatures = [
            'shift1' => $getShiftSignature($selectedDate, '08:00:00', '15:00:00', 1),
            'shift2' => $getShiftSignature($selectedDate, '15:00:01', '23:59:59', 2),
            'shift3' => $getShiftSignature($selectedDate, '00:00:00', '07:59:59', 3),
        ];

        // Kalau kamu masih butuh "Checked by" dari salah satu baris, pakai baris pertama hari itu
        $sign = $data->first(); // aman kalau kolom checked_by/checked_date memang ada di LSQualityReport

        return view('rpt_quality.preview', compact('data', 'selectedDate', 'signatures', 'sign'));
    }

    public function exportPdf(Request $request)
    {
        $selectedDate = $request->input('filter_tanggal', now()->toDateString());

        $data = LSQualityReport::whereDate('posting_date', $selectedDate)
            ->orderByRaw("CASE
        WHEN time >= '08:00:00' AND time <= '15:00:00' THEN 1
        WHEN time >= '15:00:01' AND time <= '23:59:59' THEN 2
        WHEN time >= '00:00:00' AND time <= '07:59:59' THEN 3
        ELSE 4 END")
            ->orderBy('time', 'asc')
            ->get();

        $pdf = Pdf::loadView('exports.report_quality_layout_pdf', compact('data', 'selectedDate'))
            ->setPaper('a3', 'landscape');

        return $pdf->stream('quality_report_' . $selectedDate . '.pdf');
    }

    public function approveDate(Request $request)
    {
        $date = $request->posting_date; // harusnya ini

        LSQualityReport::whereDate('posting_date', $date)
            ->update([
                'checked_status' => 'Approved',
                'checked_status_remarks' => null,
                'checked_date' => now(),
                'checked_by' => auth()->user()->username ?? auth()->user()->name,
            ]);

        return redirect()->back()->with('success', "Semua data tanggal $date berhasil di-approve.");
    }



    public function rejectDate(Request $request)
    {
        $request->validate([
            'posting_date' => 'required|date',
            'remark' => 'nullable|string|max:255',
        ]);

        $date = $request->posting_date;

        LSQualityReport::whereDate('posting_date', $date)
            ->update([
                'checked_status' => 'Rejected',
                'checked_status_remarks' => $request->remark, // simpan remark ke field
                'checked_date' => now(),
                'checked_by' => auth()->user()->username ?? auth()->user()->name,
            ]);

        return redirect()->back()->with('success', "Semua data tanggal $date berhasil direject dengan alasan: {$request->remark}");
    }


    /**
     * Helper untuk cek status shift
     */
    private function checkShiftStatus($tanggal, $start, $end): string
    {
        $pending = LSQualityReport::whereDate('posting_date', $tanggal)
            ->whereBetween('time', [$start, $end])
            ->where(function ($q) {
                $q->whereNull('checked_status')
                    ->orWhere('checked_status', '!=', 'Approved');
            })
            ->exists();

        return $pending ? 'Belum Selesai' : 'Approved Semua';
    }
}
