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

    public function exportLayoutPreview(Request $request)
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


        $sign = $data->first();

        return view('rpt_quality.preview', compact('data', 'selectedDate', 'sign'));
    }


    public function exportPdf(Request $request)
    {
        $selectedDate = $request->input('filter_tanggal', now()->toDateString());

        $data = LSQualityReport::whereDate('posting_date', $selectedDate)
            ->orderBy('time', 'asc')
            ->get();

        $pdf = Pdf::loadView('exports.report_quality_layout_pdf', compact('data', 'selectedDate'))
            ->setPaper('a4', 'landscape');

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
