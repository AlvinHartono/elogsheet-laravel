<?php

namespace App\Traits;

use App\Models\LSQualityReport;

trait ReportHelper
{
  /**
   * Cek status shift berdasarkan tanggal dan jam
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

  /**
   * Ambil status semua shift dalam 1 hari
   */
  private function getShiftStatuses(string $tanggal, ?string $workCenter = null): array
  {
    $shifts = [
      'shift1' => ['08:00:00', '15:59:59'],
      'shift2' => ['16:00:00', '23:59:59'],
      'shift3' => ['00:00:00', '07:59:59'],
    ];

    $statuses = [];

    foreach ($shifts as $key => [$start, $end]) {
      $query = LSQualityReport::whereDate('posting_date', $tanggal)
        ->whereBetween('time', [$start, $end]);

      if (!empty($workCenter)) {
        $query->where('work_center', $workCenter);
      }

      if (!$query->exists()) {
        $statuses[$key] = 'Belum Ada Transaksi';
        continue;
      }

      $pending = $query->where(function ($q) {
        $q->whereNull('checked_status')
          ->orWhere('checked_status', '!=', 'Approved');
      })->exists();

      $statuses[$key] = $pending ? 'Belum Selesai' : 'Approved Semua';
    }

    return $statuses;
  }

  /**
   * Ambil tanda tangan Prepared per shift
   */
  private function getShiftSignatures(string $date, ?string $workCenter = null): array
  {
    $shifts = [
      'shift1' => ['08:00:00', '15:59:59'],
      'shift2' => ['16:00:00', '23:59:59'],
      'shift3' => ['00:00:00', '07:59:59'],
    ];

    $signatures = [];

    foreach ($shifts as $key => [$start, $end]) {
      $query = LSQualityReport::whereDate('posting_date', $date)
        ->whereBetween('time', [$start, $end])
        ->where('prepared_status', 'Approved');

      if (!empty($workCenter)) {
        $query->where('work_center', $workCenter);
      }

      $row = $query->orderBy('time', 'desc')
        ->first(['prepared_by as name', 'prepared_date as date']);

      $signatures[$key] = $row ? ['name' => $row->name, 'date' => $row->date] : null;
    }

    return $signatures;
  }

  /**
   * Ambil informasi form (first & last revision)
   */
  private function getFormInfo(string $selectedDate, ?string $workCenter = null): array
  {
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

    return [
      'first' => $formInfoFirst,
      'last'  => $formInfoLast,
    ];
  }

  /**
   * Ambil refinery & oil type
   */
  private function getRefineryAndOilType(string $selectedDate, ?string $workCenter = null): array
  {
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

    return [
      'refinery' => $refinery,
      'oilType'  => $oilType,
    ];
  }

  /**
   * Ambil data utama laporan + grouping (kalau workCenter kosong)
   */
  private function getReportData(string $selectedDate, ?string $workCenter = null): array
  {
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

    $groupedData = empty($workCenter) ? $data->groupBy('work_center') : collect();

    return [$data, $groupedData];
  }

  /**
   * Update status QC checked (approve/reject)
   */
  private function updateCheckedStatus(string $date, string $status, ?string $remark = null): void
  {
    LSQualityReport::whereDate('posting_date', $date)
      ->update([
        'checked_status' => $status,
        'checked_status_remarks' => $remark,
        'checked_date' => now(),
        'checked_by' => auth()->user()->username ?? auth()->user()->name,
      ]);
  }
}
