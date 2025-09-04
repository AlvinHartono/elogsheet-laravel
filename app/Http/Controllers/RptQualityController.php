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
    /**
     * INDEX (untuk report normal)
     */
    public function index(Request $request)
    {
        $tanggal = $request->input('filter_tanggal', Carbon::today()->format('Y-m-d'));

        $reports = $this->buildBaseQuery($request, $tanggal)
            ->paginate(8)
            ->withQueryString();

        $shiftStatuses = $this->getShiftStatuses($tanggal);
        $workCenters   = LSQualityReport::select('work_center')->distinct()->get();

        return view('rpt_quality.index', compact('reports', 'shiftStatuses', 'tanggal', 'workCenters'));
    }

    /**
     * INDEX (untuk QC)
     */
    public function indexQc(Request $request)
    {
        $tanggal = $request->input('filter_tanggal', Carbon::today()->format('Y-m-d'));

        $reports = $this->buildBaseQuery($request, $tanggal)
            ->paginate(8)
            ->withQueryString();

        $shiftStatuses = $this->getShiftStatuses($tanggal);
        $workCenters   = LSQualityReport::select('work_center')->distinct()->get();

        $signaturesQc = $this->getSignaturesQc($tanggal, $request->input('work_center'));

        // Panggil helper function
        $approvalStatus = $this->getApprovalStatus($tanggal);

        return view('rpt_quality.QC.index', compact(
            'reports',
            'shiftStatuses',
            'tanggal',
            'workCenters',
            'signaturesQc',
        ) + $approvalStatus);
    }

    /**
     * SHOW detail report
     */
    public function show($id)
    {
        $report = LSQualityReport::findOrFail($id);
        return view('rpt_quality.show', compact('report'));
    }

    /**
     * EXPORT Excel
     */
    public function exportExcel(Request $request)
    {
        $tanggal  = $request->input('filter_tanggal', Carbon::today()->format('Y-m-d'));
        $filename = 'logsheet_quality_report_' . Carbon::parse($tanggal)->format('Y_m_d') . '.xlsx';

        return Excel::download(new LSQualityExport($tanggal), $filename);
    }

    /**
     * EXPORT Layout Preview (normal)
     */
    public function exportLayoutPreview(Request $request)
    {
        return $this->renderPreview($request, 'rpt_quality.preview');
    }

    /**
     * EXPORT Layout Preview (QC)
     */
    public function exportLayoutPreviewQc(Request $request)
    {
        return $this->renderPreview($request, 'rpt_quality.QC.preview');
    }

    /**
     * EXPORT PDF (normal)
     */
    public function exportPdf(Request $request)
    {
        return $this->renderPdf($request, 'exports.report_quality_layout_pdf');
    }

    /**
     * EXPORT PDF (QC)
     */
    public function exportPdfQc(Request $request)
    {
        return $this->renderPdf($request, 'exports.report_quality_layout_pdf_qc');
    }

    /**
     * APPROVE all by date
     */
    public function approveDate(Request $request)
    {
        $date = $request->posting_date;

        LSQualityReport::whereDate('posting_date', $date)->update([
            'checked_status'         => 'Approved',
            'checked_status_remarks' => null,
            'checked_date'           => now(),
            'checked_by'             => auth()->user()->username ?? auth()->user()->name,
        ]);

        return back()->with('success', "Semua data tanggal $date berhasil di-approve.");
    }

    /**
     * REJECT all by date
     */
    public function rejectDate(Request $request)
    {
        $request->validate([
            'posting_date' => 'required|date',
            'remark'       => 'nullable|string|max:255',
        ]);

        $date = $request->posting_date;

        LSQualityReport::whereDate('posting_date', $date)->update([
            'checked_status'         => 'Rejected',
            'checked_status_remarks' => $request->remark,
            'checked_date'           => now(),
            'checked_by'             => auth()->user()->username ?? auth()->user()->name,
        ]);

        return back()->with('success', "Semua data tanggal $date berhasil direject dengan alasan: {$request->remark}");
    }

    /**
     * APPROVE all by date QC
     */
    public function approveDateQc(Request $request)
    {
        $date = $request->posting_date;

        // Cek kondisi prepared_status
        $reports = LSQualityReport::whereDate('posting_date', $date)->get();

        if ($reports->isEmpty()) {
            return back()->with('error', "Tidak ada data pada tanggal $date.");
        }

        // Kalau ada salah satu yang belum di-prepare
        if ($reports->contains(fn($r) => is_null($r->prepared_status))) {
            return back()->with('error', 'Belum dilakukan prepared oleh shift leader.');
        }

        // Kalau ada yang di-reject oleh shift leader
        if ($reports->contains(fn($r) => $r->prepared_status === 'Rejected')) {
            return back()->with('error', 'Data sudah direject oleh shift leader.');
        }

        // Hanya approve jika prepared_status = Approved
        if ($reports->every(fn($r) => $r->prepared_status === 'Approved')) {
            LSQualityReport::whereDate('posting_date', $date)->update([
                'checked_status'         => 'Approved',
                'checked_status_remarks' => null,
                'checked_date'           => now(),
                'checked_by'             => auth()->user()->username ?? auth()->user()->name,
            ]);

            return back()->with('success', "Semua data tanggal $date berhasil di-approve.");
        }

        return back()->with('error', 'Terdapat data yang tidak valid untuk di-approve.');
    }

    /**
     * REJECT all by date QC
     */
    public function rejectDateQc(Request $request)
    {
        $request->validate([
            'posting_date' => 'required|date',
            'remark'       => 'nullable|string|max:255',
        ]);

        $date = $request->posting_date;
        $reports = LSQualityReport::whereDate('posting_date', $date)->get();

        if ($reports->isEmpty()) {
            return back()->with('error', "Tidak ada data pada tanggal $date.");
        }

        if ($reports->contains(fn($r) => is_null($r->prepared_status))) {
            return back()->with('error', 'Belum dilakukan prepared oleh shift leader.');
        }

        if ($reports->contains(fn($r) => $r->prepared_status === 'Rejected')) {
            return back()->with('error', 'Data sudah direject oleh shift leader, tidak bisa direject ulang.');
        }

        if ($reports->every(fn($r) => $r->prepared_status === 'Approved')) {
            LSQualityReport::whereDate('posting_date', $date)->update([
                'checked_status'         => 'Rejected',
                'checked_status_remarks' => $request->remark,
                'checked_date'           => now(),
                'checked_by'             => auth()->user()->username ?? auth()->user()->name,
            ]);

            return back()->with('success', "Semua data tanggal $date berhasil direject dengan alasan: {$request->remark}");
        }

        return back()->with('error', 'Terdapat data yang tidak valid untuk direject.');
    }


    /**
     * APPROVE by ticket id Produksi
     */
    public function approveTicket($id)
    {
        $report = LSQualityReport::findOrFail($id);

        $report->update([
            'approved_status'         => 'Approved',
            'approved_status_remarks' => null,
            'approved_date'           => now(),
            'approved_by'             => auth()->user()->username ?? auth()->user()->name,
        ]);

        return back()->with('success', "Tiket {$report->id} berhasil di-approve (Produksi).");
    }

    /**
     * REJECT by ticket id Produksi
     */
    public function rejectTicket(Request $request, $id)
    {
        $request->validate([
            'remark' => 'nullable|string|max:255',
        ]);

        $report = LSQualityReport::findOrFail($id);

        $report->update([
            'approved_status'         => 'Rejected',
            'approved_status_remarks' => $request->remark,
            'approved_date'           => now(),
            'approved_by'             => auth()->user()->username ?? auth()->user()->name,
        ]);

        return back()->with('success', "Tiket {$report->id} berhasil direject (Produksi).");
    }

    /**
     * APPROVE by ticket id QC
     */
    public function approveTicketQc($id)
    {
        $report = LSQualityReport::findOrFail($id);

        $report->update([
            'prepared_status'         => 'Approved',
            'prepared_status_remarks' => null,
            'prepared_date'           => now(),
            'prepared_by'             => auth()->user()->username ?? auth()->user()->name,
        ]);

        return back()->with('success', "Tiket {$report->id} berhasil di-approve (QC).");
    }

    /**
     * REJECT by ticket id QC
     */
    public function rejectTicketQc(Request $request, $id)
    {
        $request->validate([
            'remark' => 'nullable|string|max:255',
        ]);

        $report = LSQualityReport::findOrFail($id);

        $report->update([
            'prepared_status'         => 'Rejected',
            'prepared_status_remarks' => $request->remark,
            'prepared_date'           => now(),
            'prepared_by'             => auth()->user()->username ?? auth()->user()->name,
        ]);

        return back()->with('success', "Tiket {$report->id} berhasil direject (QC).");
    }

    /* ==========================================================================
     * PRIVATE HELPERS
     * ========================================================================== */

    private function buildBaseQuery(Request $request, string $tanggal)
    {
        $query = LSQualityReport::query()->whereDate('posting_date', $tanggal);

        if ($request->filled('filter_jam')) {
            $query->where('time', $request->filter_jam);
        }

        if ($request->filled('filter_work_center')) {
            $query->where('work_center', $request->filter_work_center);
        }

        return $query->reorder()
            ->orderByRaw("CASE WHEN time >= '08:00' THEN 0 ELSE 1 END")
            ->orderBy('shift', 'asc')
            ->orderBy('time', 'asc');
    }

    private function getShiftStatuses(string $tanggal): array
    {
        return [
            'shift1' => $this->checkShiftStatus($tanggal, '08:00:00', '15:59:59'),
            'shift2' => $this->checkShiftStatus($tanggal, '16:00:00', '23:59:59'),
            'shift3' => $this->checkShiftStatus($tanggal, '00:00:00', '07:59:59'),
        ];
    }

    private function renderPreview(Request $request, string $view)
    {
        $tanggal    = $request->input('filter_tanggal', now()->toDateString());
        $workCenter = $request->input('filter_work_center');

        $data        = $this->getMainData($tanggal, $workCenter);
        $groupedData = empty($workCenter) ? $data->groupBy('work_center') : collect();
        $signatures  = $this->getSignatures($tanggal, $workCenter);
        $signaturesQc = $this->getSignaturesQc($tanggal, $workCenter);
        $sign        = $data->first();

        [$formInfoFirst, $formInfoLast] = $this->getFormInfo($tanggal, $workCenter);
        $refinery = $this->getRefinery($tanggal, $workCenter);
        $oilType  = $this->getOilType($tanggal, $workCenter);

        return view($view, compact(
            'data',
            'groupedData',
            'tanggal',
            'workCenter',
            'signatures',
            'signaturesQc',
            'sign',
            'formInfoFirst',
            'formInfoLast',
            'refinery',
            'oilType'
        ));
    }

    private function renderPdf(Request $request, string $view)
    {
        $tanggal    = $request->input('filter_tanggal', now()->toDateString());
        $workCenter = $request->input('filter_work_center');

        $data        = $this->getMainData($tanggal, $workCenter);
        $groupedData = empty($workCenter) ? $data->groupBy('work_center') : collect();

        // Versi produksi (tetap dikirim kalau view produksi perlu)
        $signatures   = $this->getSignatures($tanggal, $workCenter);
        // Versi QC (punya prepared & acknowledge)
        $signaturesQc = $this->getSignaturesQc($tanggal, $workCenter);

        // Ambil shift terakhir yang punya prepared/acknowledge untuk opsi 2
        $lastShift = collect($signaturesQc)
            ->filter(fn($s) => data_get($s, 'prepared') || data_get($s, 'acknowledge'))
            ->last();

        [$formInfoFirst, $formInfoLast] = $this->getFormInfo($tanggal, $workCenter);
        $refinery = $this->getRefinery($tanggal, $workCenter);
        $oilType  = $this->getOilType($tanggal, $workCenter);

        $pdf = Pdf::loadView($view, compact(
            'data',
            'groupedData',
            'tanggal',
            'workCenter',
            'formInfoFirst',
            'formInfoLast',
            'refinery',
            'oilType',
            'signatures',     // prod
            'signaturesQc',   // qc
            'lastShift'       // qc opsi 2
        ))->setPaper('a3', 'landscape');

        return $pdf->stream("quality_report_{$tanggal}.pdf");
    }


    private function getMainData(string $tanggal, ?string $workCenter)
    {
        $query = LSQualityReport::whereDate('posting_date', $tanggal);

        if ($workCenter) {
            $query->where('work_center', $workCenter);
        }

        return $query->join('m_mastervalue', 't_quality_report_refinery.work_center', '=', 'm_mastervalue.code')
            ->select('t_quality_report_refinery.*', 'm_mastervalue.name as refinery_name')
            ->orderByRaw("CASE
                WHEN time BETWEEN '08:00:00' AND '15:59:59' THEN 1
                WHEN time BETWEEN '16:00:00' AND '23:59:59' THEN 2
                WHEN time BETWEEN '00:00:00' AND '07:59:59' THEN 3
                ELSE 4 END")
            ->orderBy('time')
            ->get();
    }

    private function getSignatures(string $tanggal, ?string $workCenter): array
    {
        $get = function ($start, $end) use ($tanggal, $workCenter) {
            $q = LSQualityReport::whereDate('posting_date', $tanggal)
                ->whereBetween('time', [$start, $end])
                ->where('prepared_status', 'Approved');

            if ($workCenter) {
                $q->where('work_center', $workCenter);
            }

            $row = $q->orderByDesc('time')->first(['prepared_by as name', 'prepared_date as date']);
            return $row ? ['name' => $row->name, 'date' => $row->date] : null;
        };

        return [
            'shift1' => $get('08:00:00', '15:59:59'),
            'shift2' => $get('16:00:00', '23:59:59'),
            'shift3' => $get('00:00:00', '07:59:59'),
        ];
    }

    private function getSignaturesQc(string $tanggal, ?string $workCenter): array
    {
        $get = function ($start, $end) use ($tanggal, $workCenter) {
            $q = LSQualityReport::whereDate('posting_date', $tanggal)
                ->whereBetween('time', [$start, $end]);

            if ($workCenter) {
                $q->where('work_center', $workCenter);
            }

            $prepared = (clone $q)->orderByDesc('time')->first(['prepared_by', 'prepared_date']);
            $ack      = (clone $q)->orderByDesc('time')->first(['checked_by', 'checked_date']);

            return [
                'prepared'    => $prepared ? ['name' => $prepared->prepared_by, 'date' => $prepared->prepared_date] : null,
                'acknowledge' => $ack ? ['name' => $ack->checked_by, 'date' => $ack->checked_date] : null,
            ];
        };

        return [
            'shift1' => $get('08:00:00', '15:59:59'),
            'shift2' => $get('16:00:00', '23:59:59'),
            'shift3' => $get('00:00:00', '07:59:59'),
        ];
    }


    private function getFormInfo(string $tanggal, ?string $workCenter): array
    {
        $base = LSQualityReport::whereDate('posting_date', $tanggal);
        $firstQuery = clone $base;
        $lastQuery  = clone $base;

        if ($workCenter) {
            $firstQuery->where('work_center', $workCenter);
            $lastQuery->where('work_center', $workCenter);
        }

        $first = $firstQuery->orderBy('revision_date')->first(['form_no', 'date_issued', 'revision_no', 'revision_date']);
        $last  = $lastQuery->orderByDesc('revision_date')->first(['form_no', 'date_issued', 'revision_no', 'revision_date']);

        return [$first, $last];
    }

    private function getRefinery(string $tanggal, ?string $workCenter)
    {
        $q = LSQualityReport::whereDate('posting_date', $tanggal)
            ->join('m_mastervalue', 't_quality_report_refinery.work_center', '=', 'm_mastervalue.code')
            ->select('t_quality_report_refinery.work_center', 'm_mastervalue.name');

        if ($workCenter) {
            $q->where('t_quality_report_refinery.work_center', $workCenter);
        }

        return $q->first();
    }

    private function getOilType(string $tanggal, ?string $workCenter)
    {
        $q = LSQualityReport::whereDate('posting_date', $tanggal)->select('oil_type');

        if ($workCenter) {
            $q->where('work_center', $workCenter);
        }

        return $q->first();
    }

    private function checkShiftStatus(string $tanggal, string $start, string $end): string
    {
        $q = LSQualityReport::whereDate('posting_date', $tanggal)->whereBetween('time', [$start, $end]);

        if (!$q->exists()) {
            return 'Belum Ada Transaksi';
        }

        $pending = (clone $q)->where(function ($sub) {
            $sub->whereNull('checked_status')
                ->orWhere('checked_status', '!=', 'Approved');
        })->exists();

        return $pending ? 'Belum Selesai' : 'Approved Semua';
    }

    /**
     * Cek apakah tombol approve/reject bisa aktif untuk tanggal tertentu
     */
    private function getApprovalStatus(string $tanggal): array
    {
        $reports = LSQualityReport::whereDate('posting_date', $tanggal)->get();

        $statusMessage = null;
        $canApproveReject = false;

        if ($reports->isEmpty()) {
            $statusMessage = "Tidak ada data pada tanggal $tanggal.";
        } elseif ($reports->contains(fn($r) => is_null($r->prepared_status))) {
            $statusMessage = 'Belum dilakukan prepared oleh shift leader.';
        } elseif ($reports->contains(fn($r) => $r->prepared_status === 'Rejected')) {
            $statusMessage = 'Data sudah direject oleh shift leader.';
        } elseif ($reports->every(fn($r) => $r->prepared_status === 'Approved')) {
            $canApproveReject = true;
        } else {
            $statusMessage = 'Terdapat data yang tidak valid untuk diproses.';
        }

        return [
            'canApproveReject' => $canApproveReject,
            'statusMessage'    => $statusMessage,
        ];
    }
}
