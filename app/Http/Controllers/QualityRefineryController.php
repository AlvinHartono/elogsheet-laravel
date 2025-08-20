<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QualityReportRefinery;
use App\Exports\QualityRefineryExport;
use App\Exports\QualityRefineryViewExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class QualityRefineryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = QualityReportRefinery::orderBy('entry_date', 'desc')->paginate(20);
        return view('rpt_quality_refinery.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $report = QualityReportRefinery::findOrFail($id);
        return view('rpt_quality_refinery.show', compact('report'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Method export excel
    public function export()
    {
        $tanggal = Carbon::now()->format('Y_m_d');
        $filename = 'quality_report_' . $tanggal . '.xlsx';
        return Excel::download(new QualityRefineryExport, $filename);
    }

    // Untuk tampilkan halaman layout
    public function exportLayoutPreview()
    {
        $data = \App\Models\QualityReportRefinery::all();
        return view('exports.quality_refinery_layout', compact('data'));
    }

    public function exportPdf()
    {
        $data = QualityReportRefinery::all(); // atau sesuaikan
        $pdf = Pdf::loadView('exports.quality_refinery_layout_pdf', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->download('quality_report_' . now()->format('Y_m_d') . '.pdf');
    }
}
