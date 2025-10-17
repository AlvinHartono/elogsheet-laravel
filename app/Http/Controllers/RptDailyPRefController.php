<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\LSDailyProductionRefinery;
use App\Exports\LSDailyProductionRefineryExport;
use App\Models\MRolesShiftPrepared;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class RptDailyPRefController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * 
     */
    public function index(Request $request)
    {
        $tanggal = $request->input('filter_tanggal', Carbon::today()->format('Y-m-d'));

        $reports = $this->buildBaseQuery($request, $tanggal)
            ->paginate(8)
            ->withQueryString();

        $refineryMachines = LSDailyProductionRefinery::select('work_center')->distinct()->get();
        $signatures = $this->getSignatures($tanggal, $request->input('filter_work_center'));
        $approvalStatus = $this->getApprovalStatus($tanggal);

        return view('rpt_daily_production.refinery.index', compact('reports', 'tanggal', 'refineryMachines', 'signatures') + $approvalStatus);
    }

    /**
     * SHOW detail report - Display the specified resource.
     */
    public function show($id)
    {
        $report = LSDailyProductionRefinery::findOrFail($id);
        return view('rpt_daily_production.refinery.show', compact('report'));
    }

    /**
     * EXPORT Excel
     */
    public function exportExcel(Request $request)
    {
        $tanggal = $request->input('filter_tanggal', Carbon::today()->format('Y-m-d'));
        $filename = 'logsheet_daily_production_refinery_' . Carbon::parse($tanggal)->format('Y_m_d') . '.xlsx';

        // Assuming an export class exists: App\Exports\LSProductionRefineryExport
        return Excel::download(new LSDailyProductionRefineryExport($tanggal), $filename);
    }

    /**
     * EXPORT Layout Preview
     */
    public function exportLayoutPreview(Request $request)
    {
        return $this->renderPreview($request, 'rpt_daily_production.refinery.preview');
    }

    /**
     * EXPORT PDF
     */
    public function exportPdf(Request $request)
    {
        return $this->renderPdf($request, 'exports.report_dailyPRef_layout_pdf');
    }

    /**
     * APPROVE all by date
     */
    public function approveDate(Request $request)
    {
        $date = $request->posting_date;
        $user = Auth::user();
        $role = $user->roles;

        $reports = LSDailyProductionRefinery::whereDate('posting_date', $date)
            ->where('flag', 'T')
            ->get();

        if ($reports->isEmpty()) {
            return back()->with('error', "Tidak ada data pada tanggal $date.");
        }

        if ($reports->contains(fn($r) => $r->prepared_status === 'Rejected')) {
            return back()->with('error', 'Data sudah direject oleh shift leader.');
        }

        if ($role === "LEAD_PROD" or $role === "LEAD") {
            $assignedShifts = MRolesShiftPrepared::where('username', $user->username)
                ->where('isactive', 'T')
                ->pluck('shift_code');

            if ($assignedShifts->isEmpty()) {
                return back()->with('error', "User tidak memiliki shift.");
            }

            LSDailyProductionRefinery::whereDate('posting_date', $date)
                ->whereIn('shift', $assignedShifts)
                ->update([
                    'prepared_status' => 'Approved',
                    'prepared_status_remarks' => null,
                    'prepared_date' => now(),
                    'prepared_by' => auth()->user()->username ?? auth()->user()->name,
                ]);

        } else if ($role === "MGR_PROD" or $role === "MGR") {
            if ($reports->contains(fn($r) => is_null($r->prepared_status))) {
                return back()->with('error', 'Belum dilakukan prepared oleh shift leader.');
            }

            LSDailyProductionRefinery::whereDate('posting_date', $date)->update([
                'checked_status' => 'Approved',
                'checked_status_remarks' => null,
                'checked_date' => now(),
                'checked_by' => auth()->user()->username ?? auth()->user()->name,
            ]);
        }

        return back()->with('success', "Semua data tanggal $date berhasil di-approve.");
    }

    /**
     * REJECT all by date
     */
    public function rejectDate(Request $request)
    {
        $request->validate(['posting_date' => 'required|date', 'remark' => 'nullable|string|max:255']);

        $date = $request->posting_date;
        $user = Auth::user();
        $role = $user->roles;
        $message = '';

        if ($role === 'LEAD_PROD' || $role === 'LEAD') {
            $assignedShifts = MRolesShiftPrepared::where('username', $user->username)->where('isactive', 'T')->pluck('shift_code');

            if ($assignedShifts->isEmpty()) {
                return back()->with('error', 'You have no active shifts assigned.');
            }

            LSDailyProductionRefinery::whereDate('posting_date', $date)
                ->whereIn('shift', $assignedShifts)
                ->update(['prepared_status' => 'Rejected', 'prepared_status_remarks' => $request->remark, 'prepared_date' => now(), 'prepared_by' => $user->username ?? $user->name]);

            $message = "All reports for your assigned shifts on {$date} have been rejected.";

        } elseif ($role === 'MGR_PROD' || $role === 'MGR') {
            LSDailyProductionRefinery::whereDate('posting_date', $date)
                ->update(['checked_status' => 'Rejected', 'checked_status_remarks' => $request->remark, 'checked_date' => now(), 'checked_by' => $user->username ?? $user->name]);
            $message = "All data on {$date} has been rejected (Checked).";
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
        $report = LSDailyProductionRefinery::findOrFail($id);
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
        $report = LSDailyProductionRefinery::findOrFail($id);
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

    /**
     * Build the base query for the index report listing.
     * @param Request $request
     * @param string $tanggal
     *
     */
    private function buildBaseQuery(Request $request, string $tanggal)
    {
        $user = Auth::user();
        $userRole = $user->roles;
        $query = LSDailyProductionRefinery::query()->whereDate('posting_date', $tanggal);

        if ($request->filled('filter_work_center')) {
            $query->where('work_center', $request->filter_work_center);
        }

        $query->where('t_daily_production_refinery.flag', 'T');

        if ($userRole === "LEAD" || $userRole === "LEAD_PROD") {
            // Join with m_roles_shift_prepared for shift filtering
            $query->join('m_roles_shift_prepared', 't_daily_production_refinery.shift', '=', 'm_roles_shift_prepared.shift_code')
                ->where('m_roles_shift_prepared.username', $user->username)->where('m_roles_shift_prepared.isactive', 'T')
                ->select('t_daily_production_refinery.*');
        }

        return $query->reorder()->orderBy('shift', 'asc'); // Ordering by shift is sufficient here
    }

    /**
     * Get the main data for exports.
     * @param string $tanggal
     * @param string|null $workCenter
     * @return \Illuminate\Support\Collection
     */
    private function getMainData(string $tanggal, ?string $workCenter)
    {
        $user = Auth::user();
        $userRole = $user->roles;
        $query = LSDailyProductionRefinery::whereDate('posting_date', $tanggal);

        if ($workCenter)
            $query->where('work_center', $workCenter);

        $query->where('t_daily_production_refinery.flag', 'T');

        if ($userRole === "MGR" or $userRole === "MGR_PROD") {
            return $query->select('t_daily_production_refinery.*')->orderBy('shift')->get();
        } elseif ($userRole === "LEAD" or $userRole === "LEAD_PROD") {
            return $query->join('m_roles_shift_prepared', 't_daily_production_refinery.shift', '=', 'm_roles_shift_prepared.shift_code')
                ->where('m_roles_shift_prepared.username', $user->username)->where('m_roles_shift_prepared.isactive', 'T')
                ->select('t_daily_production_refinery.*')->orderBy('shift')->get();
        }
        return collect();
    }

    /**
     * Get the signatures (prepared_by) for the report.
     * @param string $tanggal
     * @param string|null $workCenter
     * @return array
     */
    private function getSignatures(string $tanggal, ?string $workCenter): array
    {
        $get = function ($shift) use ($tanggal, $workCenter) {
            $q = LSDailyProductionRefinery::whereDate('posting_date', $tanggal)
                ->where('shift', $shift)
                ->where('prepared_status', 'Approved');
            if ($workCenter)
                $q->where('work_center', $workCenter);
            $row = $q->orderByDesc('prepared_date')->first(['prepared_by as name', 'prepared_date as date']);
            return $row ? ['name' => $row->name, 'date' => $row->date] : null;
        };

        // Assuming shifts are 'S1', 'S2', 'S3' or similar for Daily Production
        // Based on RptDeodorizingController, I will use shift codes from the DB if available, 
        // but since I don't know them, I'll use S1, S2, S3 as placeholders for shift-based signature logic.
        return [
            '1' => $get('1'),
            '2' => $get('2'),
            '3' => $get('3')
        ];
    }

    /**
     * Get form information (form_no, revision_no, etc.)
     * @param string $tanggal
     * @param string|null $workCenter
     * @return array
     */
    private function getFormInfo(string $tanggal, ?string $workCenter): array
    {
        $base = LSDailyProductionRefinery::whereDate('posting_date', $tanggal);
        if ($workCenter)
            $base->where('work_center', $workCenter);
        // Getting the first and last (by revision_date) available form info
        $first = (clone $base)->orderBy('revision_date')->first(['form_no', 'date_issued', 'revision_no', 'revision_date']);
        $last = (clone $base)->orderByDesc('revision_date')->first(['form_no', 'date_issued', 'revision_no', 'revision_date']);
        return [$first, $last];
    }

    /**
     * Get the overall approval status for the date.
     * @param string $tanggal
     * @return array
     */
    private function getApprovalStatus(string $tanggal): array
    {
        $reports = LSDailyProductionRefinery::whereDate('posting_date', $tanggal)->where('flag', 'T')->get();
        $statusMessage = null;
        $canApproveReject = false;
        $user = Auth::user();
        $userRole = $user->roles;

        if ($reports->isEmpty()) {
            $statusMessage = "Tidak ada data pada tanggal $tanggal.";
        } else {
            if ($userRole === "LEAD_PROD" or $userRole === "LEAD") {
                $assignedShifts = MRolesShiftPrepared::where('username', $user->username)->where('isactive', 'T')->pluck('shift_code');
                if ($assignedShifts->isEmpty()) {
                    $statusMessage = "User tidak memiliki shift.";
                } else {
                    $reportsForUserShifts = LSDailyProductionRefinery::whereDate('posting_date', $tanggal)->whereIn('shift', $assignedShifts)->get();
                    $reportedShifts = $reportsForUserShifts->pluck('shift')->unique();
                    $allShiftsReported = $assignedShifts->diff($reportedShifts)->isEmpty();

                    if ($reportsForUserShifts->isEmpty()) {
                        $statusMessage = "No reports for your shifts yet.";
                    } elseif (!$allShiftsReported) {
                        $missingShifts = $assignedShifts->diff($reportedShifts)->implode(', ');
                        $statusMessage = "Waiting for reports from shift(s): {$missingShifts}.";
                    } elseif ($reportsForUserShifts->contains(fn($r) => !is_null($r->prepared_status))) {
                        $statusMessage = 'You have already prepared the reports.';
                    } else {
                        $canApproveReject = true;
                    }
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

    /**
     * Renders the layout preview for export.
     * @param Request $request
     * @param string $view
     * @return \Illuminate\Contracts\View\View
     */
    private function renderPreview(Request $request, string $view)
    {
        $tanggal = $request->input('filter_tanggal', now()->toDateString());
        $workCenter = $request->input('filter_work_center');
        $data = $this->getMainData($tanggal, $workCenter);
        [$formInfoFirst, $formInfoLast] = $this->getFormInfo($tanggal, $workCenter);

        // Grouping is based on work_center, similar to refinery_machine in Deodorizing
        $groupedData = empty($workCenter) ? $data->groupBy('work_center') : collect(); 

        // Calculate shift summaries for all data
        // If a workCenter is selected, it only summarizes data for that WC.
        // If no workCenter is selected, we need to calculate summaries per WC group.
        if (empty($workCenter)) {
            // Calculate summaries for each WC group
            $shiftSummaries = $groupedData->map(fn($rows) => $this->getShiftSummaries($rows));
        } else {
            // Calculate summaries for the single, filtered data set
            $shiftSummaries = $this->getShiftSummaries($data);
        }

        $signatures = $this->getSignatures($tanggal, $workCenter);
        $sign = $data->first();

        return view($view, compact('data', 'groupedData', 'shiftSummaries', 'tanggal', 'workCenter', 'signatures', 'sign', 'formInfoFirst', 'formInfoLast'));
    }

    /**
     * Renders the PDF for export.
     * @param Request $request
     * @param string $view
     * @return \Illuminate\Http\Response
     */
    private function renderPdf(Request $request, string $view)
    {
        $tanggal = $request->input('filter_tanggal', now()->toDateString());
        $workCenter = $request->input('filter_work_center');
        $data = $this->getMainData($tanggal, $workCenter);
        [$formInfoFirst, $formInfoLast] = $this->getFormInfo($tanggal, $workCenter);

        $groupedData = empty($workCenter) ? $data->groupBy('work_center') : collect();

        // Calculate shift summaries for PDF export
        if (empty($workCenter)) {
            $shiftSummaries = $groupedData->map(fn($rows) => $this->getShiftSummaries($rows));
        } else {
            $shiftSummaries = $this->getShiftSummaries($data);
        }

        $signatures = $this->getSignatures($tanggal, $workCenter);
        
        $pdf = Pdf::loadView($view, compact('data', 'groupedData','shiftSummaries','tanggal', 'workCenter', 'formInfoFirst', 'formInfoLast', 'signatures'))->setPaper('a3', 'landscape');
        return $pdf->stream("daily_production_refinery_report_{$tanggal}.pdf");
    }

    private function getShiftSummaries(\Illuminate\Support\Collection $data){
        // 1. Group by shift
        $groupedByShift = $data->groupBy('shift');

        
        // 2. Aggregate the data for each shift
        $shiftSummaries = $groupedByShift->map(function ($shiftReports, $shift) {
            $lastReport = $shiftReports->last();
            $budgetQty = optional($lastReport)->uu_budget_qty;
            $totalCPO = $shiftReports->sum(fn ($report) => (float) $report->uu_total_cpo);
            $totalSteam = $shiftReports->sum(fn ($report) => (float) $report->uu_total_steam);
            $steamCpo = $shiftReports->sum(fn ($report) => (float) $report->uu_steam_cpo);
            $yieldPercent = $shiftReports->sum(fn ($report) => (float) $report->uu_yield_percent);
            
            $beTotalBag = $shiftReports->sum(fn ($report) => (float) $report->be_total_bag);
            $paTotal = $shiftReports->sum(fn ($report) => (float) $report->pa_total);
            
            $beLotBatchNumber = optional($lastReport)->be_lot_batch_number;
            $paLotBatchNumber = optional($lastReport)->pa_lot_batch_number;
            
            $beJenis = optional($lastReport)->be_total_jenis;

            $beYieldPercent = optional($lastReport)->be_yield_percent ?? 0;
            $paYieldPercent = optional($lastReport)->pa_yield_percent ?? 0;

            return (object) [
                'shift' => $shift,
                // Utilities Summary
                'uu_budget_qty' => $budgetQty, // Total Steam for the shift
                'uu_total_cpo' => $totalCPO, // Total CPO for the shift
                'uu_total_steam' => $totalSteam, // Total Steam for the shift
                'uu_steam_cpo' => $steamCpo, 
                'uu_yield_percent' => $yieldPercent, 
                'uu_item' => $shiftReports->pluck('uu_item')->filter()->unique()->implode(', '), // Show unique items
                
                // Chemicals Summary (SUM)
                'be_total_bag' => $beTotalBag,
                'pa_total' => $paTotal,
                
                'be_lot_batch_number' => $beLotBatchNumber,
                'pa_lot_batch_number' => $paLotBatchNumber,

                'be_total_jenis' => $beJenis,

                'be_yield_percent' => $beYieldPercent,
                'pa_yield_percent' => $paYieldPercent,

                // Remarks Summary (Concatenate all remarks for the shift)
                'remarks' => $shiftReports->pluck('remarks')->filter()->implode(PHP_EOL . '---' . PHP_EOL),
            ];
        })->sortBy('shift')->values(); // Sort by shift and reset keys

        return $shiftSummaries;
    }
}
