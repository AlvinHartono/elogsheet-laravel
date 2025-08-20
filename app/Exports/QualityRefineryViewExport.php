<?php

namespace App\Exports;

use App\Models\QualityReportRefinery;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class QualityRefineryViewExport implements FromView
{
  public function view(): View
  {
    $data = QualityReportRefinery::all();
    return view('exports.quality_refinery_layout', compact('data'));
  }
}
