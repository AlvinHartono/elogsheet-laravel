<?php

namespace App\Http\Controllers;

use App\Models\LSDryFractionation;
use App\Models\MRolesShiftPrepared;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RptLogsheetDryFraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tanggal = $request->input('filter_tanggal', Carbon::today()->format('Y-m-d'));

        $reports = $this->buildBaseQuery($request, $tanggal)->paginate(8)->withQueryString();

        $workCenter = LSDryFractionation::select('work_center')->distinct()->get();
        $signatures = $this->getSignatures($tanggal, $workCenter);
        $approvalStatus = $this->getApprovalStatus($tanggal);

        return view('rpt_logsheetDryFra.index', compact('reports', 'tanggal', 'workCenter', 'signatures') + $approvalStatus);
    }

    /**
     * SHOW detail report
     */
    public function show($id)
    {
        $report = LSDryFractionation::findOrFail($id);
        return view('rpt_logsheetDryFra.show', compact('report'));
    }

    /**
     * EXPORT Excel
     */
    public function exportExcel(Request $request)
    {
        $tanggal = $request->input('filter_tanggal', Carbon::today()->format('Y-m-d'));
        $filename = 'logsheet_dry_fractionation_' . Carbon::parse($tanggal)->format('Y_m_d') . '.xlsx';

        return Excel::download(new LSDryFractionation($tanggal), $filename);
    }

    /**
     * EXPORT PDF
     */
    public function exportPdf(Request $request)
    {
        // return $this->renderPdf($request, 'exports.logsheet_dry_frac_layout_pdf');
        $tanggal = $request->input('filter_tanggal', now()->toDateString());
        $workCenter = $request->input('filter_work_center');

        // Use the existing buildBaseQuery and get all results
        $data = $this->buildBaseQuery($request, $tanggal)->get();
        [$formInfoFirst, $formInfoLast] = $this->getFormInfo($tanggal, $workCenter);
        $groupedData = empty($workCenter) ? $data->groupBy('work_center') : collect();
        $signatures = $this->getSignatures($tanggal, $workCenter);

        $pdf = Pdf::loadView('exports.report_dry_frac_layout_pdf', compact('data', 'groupedData', 'tanggal', 'workCenter', 'formInfoFirst', 'formInfoLast', 'signatures'))
            ->setPaper('a3', 'landscape');

        return $pdf->stream("dry_fractionation_report_{$tanggal}.pdf");
    }

    public function exportLayoutPreview(Request $request)
    {
        // return view('rpt_logsheetDryFra.preview');
        return $this->renderPreview($request, 'rpt_logsheetDryFra.preview');
    }

    /**
     * APPROVE all by date
     */
    public function approveDate(Request $request)
    {
        $date = $request->posting_date;
        $user = Auth::user();
        $role = $user->roles;

        $reports = LSDryFractionation::whereDate('posting_date', $date)
            ->where('flag', 'T')
            ->get();

        if ($reports->isEmpty()) {
            return back()->with('error', 'Data sudah direject oleh shift leader. ');
        }

        if ($role === 'LEAD_PROD' or $role === 'LEAD') {
            LSDryFractionation::whereDate('posting_date', $date)
                ->where('flag', 'T')
                ->update([
                    'prepared_status' => 'Approved',
                    'prepared_status_remarks' => null,
                    'prepared_date' => now(),
                    'prepared_by' => auth()->user()->username ?? auth()->user()->name,
                ]);
        } else if ($role === 'MGR_PROD' or $role === 'MGR') {
            LSDryFractionation::whereDate('posting_date', $date)
                ->where('flag', 'T')
                ->update([
                    'checked_status' => 'Approved',
                    'checked_status_remarks' => null,
                    'checked_date' => now(),
                    'checked_by' => auth()->user()->username ?? auth()->user()->name,
                ]);
        }

        return back()->with('success', "Semua data tanggal $date berhasil di-approve.");
    }

    /**
     * REJECT by date
     */
    public function rejectDate(Request $request)
    {
        $request->validate(['posting_date' => 'required|date', 'remark' => 'nullable|string|max:255']);

        $date = $request->posting_date;
        $user = Auth::user();
        $role = $user->roles;
        $remark = $request->remark;
        $message = '';

        if ($role === 'LEAD_PROD' || $role === 'LEAD') {
            LSDryFractionation::whereDate('posting_date', $date)
                ->where('flag', 'T')
                ->update([
                    'prepared_status' => 'Rejected',
                    'prepared_status_remarks' => $remark,
                    'prepared_date' => now(),
                    'prepared_by' => $user->username ?? $user->name
                ]);
            $message = "Semua laporan pada tanggal {$date} telah di-reject.";
        } elseif ($role === 'MGR_PROD' || $role === 'MGR') {
            LSDryFractionation::whereDate('posting_date', $date)
                ->where('flag', 'T')
                ->update([
                    'checked_status' => 'Rejected',
                    'checked_status_remarks' => $remark,
                    'checked_date' => now(),
                    'checked_by' => $user->username ?? $user->name
                ]);
            $message = "Semua data pada tanggal {$date} telah di-reject (Checked).";
        } else {
            return back()->with('error', 'You do not have permission to perform this action.');
        }

        return back()->with('success', $message);
    }

    /**
     * APPROVE by ticket id
     */
    public function approveTicket($id)
    {
        $report = LSDryFractionation::findOrFail($id);
        $userRole = Auth::user()->roles;

        if ($userRole === "LEAD" or $userRole === "LEAD_PROD") {
            $report->update(['prepared_status' => 'Approved', 'prepared_status_remarks' => null, 'prepared_date' => now(), 'prepared_by' => auth()->user()->username ?? auth()->user()->name]);
        } elseif ($userRole === "MGR" or $userRole === "MGR_PROD") {
            $report->update(['checked_status' => 'Approved', 'checked_status_remarks' => null, 'checked_date' => now(), 'checked_by' => auth()->user()->username ?? auth()->user()->name]);
        }
        return back()->with('success', "Tiket {$report->id} berhasil di-approve.");
    }

    /**
     * REJECT by ticket id
     */
    public function rejectTicket(Request $request, $id)
    {
        $request->validate(['remark' => 'nullable|string|max:255']);
        $report = LSDryFractionation::findOrFail($id);
        $userRole = Auth::user()->roles;

        if ($userRole === "LEAD" or $userRole === "LEAD_PROD") {
            $report->update(['prepared_status' => 'Rejected', 'prepared_status_remarks' => $request->remark, 'prepared_date' => now(), 'prepared_by' => auth()->user()->username ?? auth()->user()->name]);
        } elseif ($userRole === "MGR" or $userRole === "MGR_PROD") {
            $report->update(['checked_status' => 'Rejected', 'checked_status_remarks' => $request->remark, 'checked_date' => now(), 'checked_by' => auth()->user()->username ?? auth()->user()->name]);
        }
        return back()->with('success', "Tiket {$report->id} berhasil di-reject.");
    }

    /* ==========================================================================
     * PRIVATE HELPERS
     * ========================================================================== */
    private function buildBaseQuery(Request $request, string $tanggal)
    {
        $query = LSDryFractionation::query()->whereDate('posting_date', $tanggal);

        if ($request->filled('filter_work_center')) {
            $query->where('work_center', $request->filter_work_center);
        }

        $query->where('flag', 'T');

        return $query->orderBy('transaction_date', 'asc');
    }

    private function getSignatures(string $tanggal, ?string $workCenter): array
    {
        $baseQuery = LSDryFractionation::whereDate('posting_date', $tanggal)->where('flag', 'T');

        if ($workCenter) {
            $baseQuery->where('work_center', $workCenter);
        }

        $prepared = (clone $baseQuery)->where('prepared_status', 'Approved')->orderByDesc('prepared_date')->first();

        $checked = (clone $baseQuery)->where('checked_status', 'Approved')->orderByDesc('checked_date')->first();

        return [
            'leader_shift' => $prepared ? ['name' => $prepared->prepared_by, 'date' => $prepared->prepared_date] : null,
            'supervisor' => $checked ? ['name' => $checked->checked_by, 'date' => $checked->checked_date] : null,
        ];
    }

    private function getApprovalStatus(string $tanggal): array
    {
        $reports = LSDryFractionation::whereDate('posting_date', $tanggal)->where('flag', 'T')->get();
        $statusMessage = null;
        $canApproveReject = false;
        $user = Auth::user();
        $userRole = $user->roles;

        if ($reports->isEmpty()) {
            $statusMessage = "Tidak ada data pada tanggal $tanggal.";
        } else {
            if ($userRole === "LEAD_PROD" or $userRole === "LEAD") {
                if ($reports->contains(fn($r) => !is_null($r->prepared_status))) {
                    $statusMessage = 'Laporan ini sudah Anda proses (approve/reject).';
                } else {
                    $canApproveReject = true; // Can approve if no reports have been prepared yet
                }
            } elseif ($userRole === "MGR_PROD" or $userRole === "MGR") {
                if ($reports->contains(fn($r) => is_null($r->prepared_status)))
                    $statusMessage = 'Belum dilakukan prepared oleh shift leader.';
                elseif ($reports->contains(fn($r) => $r->prepared_status === 'Rejected'))
                    $statusMessage = 'Data sudah direject oleh shift leader.';
                elseif ($reports->every(fn($r) => !is_null($r->checked_status)))
                    $statusMessage = 'Semua data sudah di-review oleh Anda.';
                elseif ($reports->every(fn($r) => $r->prepared_status === 'Approved'))
                    $canApproveReject = true;
                else
                    $statusMessage = 'Terdapat data yang tidak valid untuk diproses.';
            }

        }
        return ['canApproveReject' => $canApproveReject, 'statusMessage' => $statusMessage];
    }

    // private function renderPdf(Request $request, string $view)
    // {
    //     $tanggal = $request->input('filter_tanggal', now()->toDateString());
    //     $data = $this->buildBaseQuery($request, $tanggal)->get();
    //     $signatures = $this->getSignatures($tanggal, $workCenter);
    //     $pdf = Pdf::loadView($view, compact('data', 'groupedData', 'tanggal', 'refineryMachine', 'formInfoFirst', 'formInfoLast', 'signatures'))->setPaper('a3', 'landscape');
    //     return $pdf->stream("dry_fractionation_report_{$tanggal}.pdf");
    // }

    // private function getMainData(string $tanggal, ?string $workCenter)
    // {
    //     $user = Auth::user();
    //     $userRole = $user->roles;
    //     $query = LSDryFractionation::whereDate('posting_date', $tanggal);
    //     if ($workCenter) {
    //         $query->where('work_center', $workCenter);
    //     }

    //     $query->where('flag', 'T');


    // }

    private function getFormInfo(string $tanggal, ?string $workCenter)
    {
        $base = LSDryFractionation::whereDate('posting_date', $tanggal);
        if ($workCenter) {
            $base->where('work_center', $workCenter);
        }
        $first = (clone $base)->orderBy('revision_date')->first(['form_no', 'date_issued', 'revision_no', 'revision_date']);
        $last = (clone $base)->orderByDesc('revision_date')->first(['form_no', 'date_issued', 'revision_no', 'revision_date']);
        return [$first, $last];
    }

    private function renderPreview(Request $request, string $view)
    {
        $tanggal = $request->input('filter_tanggal', now()->toDateString());
        $workCenter = $request->input('filter_work_center');
        $data = $this->buildBaseQuery($request, $tanggal)->get();
        [$formInfoFirst, $formInfoLast] = $this->getFormInfo($tanggal, $workCenter);

        $groupedData = empty($workCenter) ? $data->groupBy('work_center') : collect();
        $signatures = $this->getSignatures($tanggal, $workCenter);
        $sign = $data->first();

        return view($view, compact('data', 'groupedData', 'tanggal', 'workCenter', 'signatures', 'sign', 'formInfoFirst', 'formInfoLast'));
    }
}
