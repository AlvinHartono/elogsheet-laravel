<?php

namespace App\Http\Controllers;

use App\Exports\LSQualityExport;
use App\Models\LSQualityReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\ReportHelper;

class RptQualityController extends Controller
{
  use ReportHelper;

  // ==============================
  // NON QC
  // ==============================
  public function index(Request $request)
  {
    $tanggal = $request->input('filter_tanggal', Carbon::today()->toDateString());
    $workCenter = $request->input('filter_work_center');

    [$reports, $groupedData] = $this->getReportData($tanggal, $workCenter);
    $shiftStatuses = $this->getShiftStatuses($tanggal, $workCenter);

    $workCenters = LSQualityReport::select('work_center')
      ->distinct()
      ->orderBy('work_center')
      ->get();


    return view('rpt_quality.index', compact(
      'reports',
      'groupedData',
      'shiftStatuses',
      'tanggal',
      'workCenters'
    ));
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
    $selectedDate = $request->input('filter_tanggal', Carbon::today()->toDateString());
    $workCenter   = $request->input('filter_work_center');

    [$data, $groupedData] = $this->getReportData($selectedDate, $workCenter);
    $signatures = $this->getShiftSignatures($selectedDate, $workCenter);
    $formInfo   = $this->getFormInfo($selectedDate, $workCenter);
    $refineryOilType = $this->getRefineryAndOilType($selectedDate, $workCenter);

    return view('rpt_quality.preview', [
      'data'        => $data,
      'groupedData' => $groupedData,
      'selectedDate' => $selectedDate,
      'signatures'  => $signatures,
      'formInfoFirst' => $formInfo['first'],
      'formInfoLast'  => $formInfo['last'],
      'refinery'    => $refineryOilType['refinery'],
      'oilType'     => $refineryOilType['oilType'],
      'workCenter'  => $workCenter,
    ]);
  }

  public function exportPdf(Request $request)
  {
    $selectedDate = $request->input('filter_tanggal', Carbon::today()->toDateString());
    $workCenter   = $request->input('filter_work_center');

    [$data, $groupedData] = $this->getReportData($selectedDate, $workCenter);
    $signatures = $this->getShiftSignatures($selectedDate, $workCenter);
    $formInfo   = $this->getFormInfo($selectedDate, $workCenter);
    $refineryOilType = $this->getRefineryAndOilType($selectedDate, $workCenter);

    $pdf = Pdf::loadView('exports.report_quality_layout_pdf', [
      'data'        => $data,
      'groupedData' => $groupedData,
      'selectedDate' => $selectedDate,
      'workCenter'  => $workCenter,
      'formInfoFirst' => $formInfo['first'],
      'formInfoLast'  => $formInfo['last'],
      'refinery'    => $refineryOilType['refinery'],
      'oilType'     => $refineryOilType['oilType'],
      'signatures'  => $signatures,
    ])->setPaper('a3', 'landscape');

    return $pdf->stream('quality_report_' . $selectedDate . '.pdf');
  }

  public function approveDate(Request $request)
  {
    $this->updateCheckedStatus($request->posting_date, 'Approved');
    return redirect()->back()->with('success', "Semua data tanggal {$request->posting_date} berhasil di-approve.");
  }

  public function rejectDate(Request $request)
  {
    $request->validate([
      'posting_date' => 'required|date',
      'remark' => 'nullable|string|max:255',
    ]);

    $this->updateCheckedStatus($request->posting_date, 'Rejected', $request->remark);
    return redirect()->back()->with('success', "Semua data tanggal {$request->posting_date} berhasil direject dengan alasan: {$request->remark}");
  }

  // ==============================
  // QC
  // ==============================
  public function indexQc(Request $request)
  {
    $tanggal = $request->input('filter_tanggal', Carbon::today()->toDateString());
    $workCenter = $request->input('filter_work_center');

    [$reports, $groupedData] = $this->getReportData($tanggal, $workCenter);
    $shiftStatuses = $this->getShiftStatuses($tanggal, $workCenter);

    $workCenters = LSQualityReport::select('work_center')
      ->distinct()
      ->orderBy('work_center')
      ->get();


    return view('rpt_quality.QC.index', compact(
      'reports',
      'groupedData',
      'shiftStatuses',
      'tanggal',
      'workCenters'
    ));
  }

  public function exportLayoutPreviewQc(Request $request)
  {
    $selectedDate = $request->input('filter_tanggal', Carbon::today()->toDateString());
    $workCenter   = $request->input('filter_work_center');

    [$data, $groupedData] = $this->getReportData($selectedDate, $workCenter);
    $signatures = $this->getShiftSignatures($selectedDate, $workCenter);
    $formInfo   = $this->getFormInfo($selectedDate, $workCenter);
    $refineryOilType = $this->getRefineryAndOilType($selectedDate, $workCenter);

    return view('rpt_quality.QC.preview', [
      'data'        => $data,
      'groupedData' => $groupedData,
      'selectedDate' => $selectedDate,
      'signatures'  => $signatures,
      'formInfoFirst' => $formInfo['first'],
      'formInfoLast'  => $formInfo['last'],
      'refinery'    => $refineryOilType['refinery'],
      'oilType'     => $refineryOilType['oilType'],
      'workCenter'  => $workCenter,
    ]);
  }

  public function exportPdfQc(Request $request)
  {
    $selectedDate = $request->input('filter_tanggal', Carbon::today()->toDateString());
    $workCenter   = $request->input('filter_work_center');

    [$data, $groupedData] = $this->getReportData($selectedDate, $workCenter);
    $signatures = $this->getShiftSignatures($selectedDate, $workCenter);
    $formInfo   = $this->getFormInfo($selectedDate, $workCenter);
    $refineryOilType = $this->getRefineryAndOilType($selectedDate, $workCenter);

    $pdf = Pdf::loadView('exports.report_quality_layout_pdf_qc', [
      'data'        => $data,
      'groupedData' => $groupedData,
      'selectedDate' => $selectedDate,
      'workCenter'  => $workCenter,
      'formInfoFirst' => $formInfo['first'],
      'formInfoLast'  => $formInfo['last'],
      'refinery'    => $refineryOilType['refinery'],
      'oilType'     => $refineryOilType['oilType'],
      'signatures'  => $signatures,
    ])->setPaper('a3', 'landscape');

    return $pdf->stream('quality_report_' . $selectedDate . '.pdf');
  }
}
