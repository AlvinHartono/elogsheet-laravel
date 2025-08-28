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

        // Default hari ini
        $tanggal = $request->input('filter_tanggal', Carbon::today()->format('Y-m-d'));
        $query->whereDate('posting_date', $tanggal);

        // Filter by time
        if ($request->filled('filter_jam')) {
            $query->where('time', $request->filter_jam);
        }

        // Filter by work center
        if ($request->filled('filter_work_center')) {
            $query->where('work_center', $request->filter_work_center);
        }

        // Ambil data utama
        $reports = $query
            ->reorder()
            ->orderByRaw("CASE WHEN time >= '08:00' THEN 0 ELSE 1 END")
            ->orderBy('shift', 'asc')
            ->orderBy('time', 'asc')
            ->paginate(8)
            ->withQueryString();

        // Status shift
        $shiftStatuses = [
            'shift1' => $this->checkShiftStatus($tanggal, '08:00:00', '15:59:59'),
            'shift2' => $this->checkShiftStatus($tanggal, '16:00:00', '23:59:59'),
            'shift3' => $this->checkShiftStatus($tanggal, '00:00:00', '07:59:59'),
        ];

        // Ambil list work center unik untuk dropdown
        $workCenters = LSQualityReport::select('work_center')->distinct()->get();

        return view('rpt_quality.index', compact('reports', 'shiftStatuses', 'tanggal', 'workCenters'));
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
        $workCenter   = $request->input('filter_work_center'); // ambil work center dari filter

        // Data tabel utama (untuk grid)
        $dataQuery = LSQualityReport::whereDate('posting_date', $selectedDate);

        if (!empty($workCenter)) {
            $dataQuery->where('work_center', $workCenter);
        }

        $data = $dataQuery
            ->join('m_mastervalue', 't_quality_report_refinery.work_center', '=', 'm_mastervalue.code')
            ->select('t_quality_report_refinery.*', 'm_mastervalue.name as refinery_name')
            ->orderByRaw("CASE
        WHEN time >= '08:00:00' AND time <= '15:59:59' THEN 1
        WHEN time >= '16:00:00' AND time <= '23:59:59' THEN 2
        WHEN time >= '00:00:00' AND time <= '07:59:59' THEN 3
        ELSE 4 END")
            ->orderBy('time', 'asc')
            ->get();


        // Grouping jika tidak filter work_center
        $groupedData = empty($workCenter) ? $data->groupBy('work_center') : collect();

        // Helper tanda tangan per shift
        $getShiftSignature = function (string $date, string $start, string $end) use ($workCenter) {
            $query = LSQualityReport::whereDate('posting_date', $date)
                ->whereBetween('time', [$start, $end])
                ->where('prepared_status', 'Approved');

            if (!empty($workCenter)) {
                $query->where('work_center', $workCenter);
            }

            $row = $query->orderBy('time', 'desc')
                ->first(['prepared_by as name', 'prepared_date as date']);

            return $row ? ['name' => $row->name, 'date' => $row->date] : null;
        };

        $signatures = [
            'shift1' => $getShiftSignature($selectedDate, '08:00:00', '15:59:59'),
            'shift2' => $getShiftSignature($selectedDate, '16:00:00', '23:59:59'),
            'shift3' => $getShiftSignature($selectedDate, '00:00:00', '07:59:59'),
        ];

        $sign = $data->first();

        // --- Ambil form info sesuai selectedDate ---
        $formInfoFirstQuery = LSQualityReport::whereDate('posting_date', $selectedDate);
        $formInfoLastQuery  = LSQualityReport::whereDate('posting_date', $selectedDate);

        if (!empty($workCenter)) {
            $formInfoFirstQuery->where('work_center', $workCenter);
            $formInfoLastQuery->where('work_center', $workCenter);
        }

        $formInfoFirst = $formInfoFirstQuery
            ->orderBy('revision_date', 'asc')
            ->first(['form_no', 'date_issued', 'revision_no', 'revision_date']);

        $formInfoLast = $formInfoLastQuery
            ->orderBy('revision_date', 'desc')
            ->first(['form_no', 'date_issued', 'revision_no', 'revision_date']);

        // --- Ambil refinery name dari join ---
        $refineryQuery = LSQualityReport::whereDate('posting_date', $selectedDate)
            ->join('m_mastervalue', 't_quality_report_refinery.work_center', '=', 'm_mastervalue.code')
            ->select('t_quality_report_refinery.work_center', 'm_mastervalue.name');

        if (!empty($workCenter)) {
            $refineryQuery->where('t_quality_report_refinery.work_center', $workCenter);
        }

        $refinery = $refineryQuery->first();

        $oilTypeQuery = LSQualityReport::whereDate('posting_date', $selectedDate)
            ->select('oil_type');

        if (!empty($workCenter)) {
            $oilTypeQuery->where('work_center', $workCenter);
        }

        $oilType = $oilTypeQuery->first();

        return view('rpt_quality.preview', compact(
            'data',
            'groupedData',
            'selectedDate',
            'signatures',
            'sign',
            'formInfoFirst',
            'formInfoLast',
            'refinery',
            'oilType',
            'workCenter'
        ));
    }


    public function exportPdf(Request $request)
    {
        $selectedDate = $request->input('filter_tanggal', now()->toDateString());
        $workCenter   = $request->input('filter_work_center'); // konsisten dengan preview

        // Data tabel utama
        $dataQuery = LSQualityReport::whereDate('posting_date', $selectedDate);

        if (!empty($workCenter)) {
            $dataQuery->where('work_center', $workCenter);
        }

        $data = $dataQuery
            ->join('m_mastervalue', 't_quality_report_refinery.work_center', '=', 'm_mastervalue.code')
            ->select('t_quality_report_refinery.*', 'm_mastervalue.name as refinery_name')
            ->orderByRaw("CASE
            WHEN time >= '08:00:00' AND time <= '15:59:59' THEN 1
            WHEN time >= '16:00:00' AND time <= '23:59:59' THEN 2
            WHEN time >= '00:00:00' AND time <= '07:59:59' THEN 3
            ELSE 4 END")
            ->orderBy('time', 'asc')
            ->get();

        // Grouping jika tidak filter work_center
        $groupedData = empty($workCenter) ? $data->groupBy('work_center') : collect();

        // Helper tanda tangan per shift
        $getShiftSignature = function (string $date, string $start, string $end) use ($workCenter) {
            $query = LSQualityReport::whereDate('posting_date', $date)
                ->whereBetween('time', [$start, $end])
                ->where('prepared_status', 'Approved');

            if (!empty($workCenter)) {
                $query->where('work_center', $workCenter);
            }

            $row = $query->orderBy('time', 'desc')
                ->first(['prepared_by as name', 'prepared_date as date']);

            return $row ? ['name' => $row->name, 'date' => $row->date] : null;
        };

        $signatures = [
            'shift1' => $getShiftSignature($selectedDate, '08:00:00', '15:59:59'),
            'shift2' => $getShiftSignature($selectedDate, '16:00:00', '23:59:59'),
            'shift3' => $getShiftSignature($selectedDate, '00:00:00', '07:59:59'),
        ];


        // --- Ambil form info sesuai selectedDate ---
        $formInfoFirstQuery = LSQualityReport::whereDate('posting_date', $selectedDate);
        $formInfoLastQuery  = LSQualityReport::whereDate('posting_date', $selectedDate);

        if (!empty($workCenter)) {
            $formInfoFirstQuery->where('work_center', $workCenter);
            $formInfoLastQuery->where('work_center', $workCenter);
        }

        $formInfoFirst = $formInfoFirstQuery
            ->orderBy('revision_date', 'asc')
            ->first(['form_no', 'date_issued', 'revision_no', 'revision_date']);

        $formInfoLast = $formInfoLastQuery
            ->orderBy('revision_date', 'desc')
            ->first(['form_no', 'date_issued', 'revision_no', 'revision_date']);

        // --- Ambil refinery name dari join ---
        $refineryQuery = LSQualityReport::whereDate('posting_date', $selectedDate)
            ->join('m_mastervalue', 't_quality_report_refinery.work_center', '=', 'm_mastervalue.code')
            ->select('t_quality_report_refinery.work_center', 'm_mastervalue.name');

        if (!empty($workCenter)) {
            $refineryQuery->where('t_quality_report_refinery.work_center', $workCenter);
        }

        $refinery = $refineryQuery->first();

        // --- Ambil oilType ---
        $oilTypeQuery = LSQualityReport::whereDate('posting_date', $selectedDate)
            ->select('oil_type');

        if (!empty($workCenter)) {
            $oilTypeQuery->where('work_center', $workCenter);
        }

        $oilType = $oilTypeQuery->first();

        // Render ke PDF
        $pdf = Pdf::loadView('exports.report_quality_layout_pdf', compact(
            'data',
            'groupedData',
            'selectedDate',
            'workCenter',
            'formInfoFirst',
            'formInfoLast',
            'refinery',
            'oilType',
            'signatures'
        ))
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
                'checked_date' => now()->format('Y-m-d H:i:s'),
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
        $query = LSQualityReport::whereDate('posting_date', $tanggal)
            ->whereBetween('time', [$start, $end]);

        if (!$query->exists()) {
            return 'Belum Ada Transaksi';
        }

        $pending = $query->where(function ($q) {
            $q->whereNull('checked_status')
                ->orWhere('checked_status', '!=', 'Approved');
        })->exists();

        return $pending ? 'Belum Selesai' : 'Approved Semua';
    }



    public function indexQc(Request $request)
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
            'shift1' => $this->checkShiftStatus($tanggal, '08:00:00', '15:59:59'),
            'shift2' => $this->checkShiftStatus($tanggal, '16:00:00', '23:59:59'),
            'shift3' => $this->checkShiftStatus($tanggal, '00:00:00', '07:59:59'),
        ];

        return view('rpt_quality.QC.index', compact('reports', 'shiftStatuses', 'tanggal'));
    }

    public function exportLayoutPreviewQc(Request $request)
    {
        $selectedDate = $request->input('filter_tanggal', now()->toDateString());

        // Data tabel utama (untuk grid)
        $data = LSQualityReport::whereDate('posting_date', $selectedDate)
            ->orderByRaw("CASE
            WHEN time >= '08:00:00' AND time <= '15:59:59' THEN 1
            WHEN time >= '16:00:00' AND time <= '23:59:59' THEN 2
            WHEN time >= '00:00:00' AND time <= '07:59:59' THEN 3
            ELSE 4 END")
            ->orderBy('time', 'asc')
            ->get();

        // Helper tanda tangan per shift
        $getShiftSignature = function (string $date, string $start, string $end) {
            $row = LSQualityReport::whereDate('posting_date', $date)
                ->whereBetween('time', [$start, $end])
                ->where('prepared_status', 'Approved')
                ->orderBy('time', 'desc')
                ->first(['prepared_by as name', 'prepared_date as date']);

            return $row ? ['name' => $row->name, 'date' => $row->date] : null;
        };

        $signatures = [
            'shift1' => $getShiftSignature($selectedDate, '08:00:00', '15:59:59'),
            'shift2' => $getShiftSignature($selectedDate, '16:00:00', '23:59:59'),
            'shift3' => $getShiftSignature($selectedDate, '00:00:00', '07:59:59'),
        ];

        // Kalau kamu masih butuh "Checked by" dari salah satu baris, pakai baris pertama hari itu
        $sign = $data->first(); // aman kalau kolom checked_by/checked_date memang ada di LSQualityReport

        return view('rpt_quality.QC.preview', compact('data', 'selectedDate', 'signatures', 'sign'));
    }

    public function exportPdfQc(Request $request)
    {
        $selectedDate = $request->input('filter_tanggal', now()->toDateString());

        $data = LSQualityReport::whereDate('posting_date', $selectedDate)
            ->orderByRaw("CASE
        WHEN time >= '08:00:00' AND time <= '15:59:59' THEN 1
        WHEN time >= '16:00:00' AND time <= '23:59:59' THEN 2
        WHEN time >= '00:00:00' AND time <= '07:59:59' THEN 3
        ELSE 4 END")
            ->orderBy('time', 'asc')
            ->get();

        $pdf = Pdf::loadView('exports.report_quality_layout_pdf_qc', compact('data', 'selectedDate'))
            ->setPaper('a3', 'landscape');

        return $pdf->stream('quality_report_' . $selectedDate . '.pdf');
    }
}
