<?php

namespace App\Http\Controllers;

use App\Models\LSStartUpProduksiChecklistHeader;
use App\Models\MProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RptStartupProduksiController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $tanggal = $request->input('filter_tanggal');
        $time = $request->input('filter_time');
        $selectedWorkCenter = $request->input('filter_work_center');
        $selectedProduct = $request->input('filter_product');

        $products = MProduct::select('id', 'raw_material')->orderBy('raw_material')->get();

        $work_centers = LSStartUpProduksiChecklistHeader::select('work_center')
            ->distinct()
            ->whereNotNull('work_center')
            ->orderBy('work_center')
            ->get();

        if (!$tanggal) {
            $tanggal = now()->startOfMonth()->format('Y-m-d');
        }
        if (!$time) {
            $time = '';
        }

        // $headers = LSStartUpProduksiChecklistHeader::with([
        //     'detail' => function ($query) {
        //         $query->select('id', 'id_hdr', 'check_item', 'status_item');
        //     }, // Loads the hasMany relationship
        //     'product' => function ($query) {
        //         $query->select('id', 'raw_material');
        //     }, // Loads the belongsTo relationship
        // ])
        //     ->whereHas('detail') // This ensures we only get headers that HAVE details (like your INNER JOIN)
        //     ->whereDate('transaction_date', $tanggal)
        //     ->where('flag', 'T')
        //     ->paginate(10)->withQueryString();

        $sql = LSStartUpProduksiChecklistHeader::with([
            'details' => function ($query) {
                $query->select('id', 'id_hdr', 'check_item', 'status_item');
            },
            'oilProduct' => function ($query) {
                $query->select('id', 'raw_material');
            },
        ])
            ->whereHas('details')
            ->whereDate('transaction_date', $tanggal)
            ->where('flag', 'T');
        if ($time && $time != "") {
            $sql->where('transaction_time', $time);
        }
        if ($selectedWorkCenter) {
            $sql->where('work_center', $selectedWorkCenter);
        }
        if ($selectedProduct) {
            $sql->where('product', $selectedProduct);
        }

        $headers = $sql->orderBy('id', 'asc')
            ->paginate(10)->withQueryString();

        // dd('Fetched rows: ' . $headers->total());


        return view('rpt_startup_produksi.index', compact('headers', 'products', 'work_centers', 'tanggal', 'time', 'selectedWorkCenter', 'selectedProduct'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        //
        $header = LSStartUpProduksiChecklistHeader::with([
            'oilProduct' => fn($q) => $q->select('id', 'raw_material'),
            'details' => function ($query) {
                $query->with(['langkahKerjaStartup' => fn($q) => $q->select('code', 'name', 'sort_no')])
                    ->join('m_langkahkerja_startup', 't_startup_produksi_checklist_detail.check_item', '=', 'm_langkahkerja_startup.code')
                    ->select('t_startup_produksi_checklist_detail.*')
                    // Order the final list of details by the 'sort_no'
                    ->orderBy('m_langkahkerja_startup.code', 'asc');
            }

        ])->findOrFail($id);

        return view('rpt_startup_produksi.show', compact('header'));
    }

    /**
     * Approve as "Prepared By" (Shift Leader)
     */
    public function verifyApproval(Request $request, $id)
    {
        $header = LSStartUpProduksiChecklistHeader::findOrFail($id);

        if ($header->prepared_status) {
            if ($header->prepared_status) {
                return redirect()->back()->with('error', 'Sudah diverifikasi shift leader.');
            }
        }

        $header->prepared_status = 'Approved';
        $header->prepared_by = auth()->user()->username; // Assumes user is logged in
        $header->prepared_date = Carbon::now();
        $header->save();

        return redirect()->back()->with('success', 'Change Report berhasil diverifikasi.');
    }

    public function verifyReject(Request $request, $id)
    {
        $header = LSStartUpProduksiChecklistHeader::findOrFail($id);

        if ($header->prepared_status) {
            return redirect()->back()->with('error', 'sudah diverifikasi oleh shift leader.');
        }

        $header->prepared_status = 'Rejected';
        $header->prepared_by = auth()->user()->username; // Assumes user is logged in
        $header->prepared_date = Carbon::now();
        // You might want to add remarks from the request, e.g.:
        // $header->prepared_status_remarks = $request->input('remarks');
        $header->save();

        return redirect()->back()->with('success', 'Change Report berhasil direject.');
    }

    /**
     * Approve as "Checked By" (Manager)
     */
    public function checkApproval(Request $request, $id)
    {
        $header = LSStartUpProduksiChecklistHeader::findOrFail($id);

        if ($header->prepared_status !== 'Approved') {
            return redirect()->back()->with('error', 'Harus diverifikasi shift leader terlebih dahulu.');
        }

        if ($header->checked_status) {
            return redirect()->back()->with('error', 'Already checked.');
        }

        $header->checked_status = 'Approved';
        $header->checked_by = auth()->user()->username; // Assumes user is logged in
        $header->checked_date = Carbon::now();
        $header->save();

        return redirect()->back()->with('success', 'Document checked and approved.');
    }

    /**
     * Reject as "Checked By" (Manager)
     */
    public function checkReject(Request $request, $id)
    {
        $header = LSStartUpProduksiChecklistHeader::findOrFail($id);

        if ($header->prepared_status !== 'Approved') {
            return redirect()->back()->with('error', 'Must be prepared by Shift Leader first.');
        }

        if ($header->checked_status) {
            return redirect()->back()->with('error', 'Already checked.');
        }

        $header->checked_status = 'Rejected';
        $header->checked_by = auth()->user()->username; // Assumes user is logged in
        $header->checked_date = Carbon::now();
        // You might want to add remarks from the request, e.g.:
        // $header->checked_status_remarks = $request->input('remarks');
        $header->save();

        return redirect()->back()->with('success', 'Document rejected.');
    }

    public function exportLayoutPreview($id)
    {
        // 1. Fetch the specific header by its ID, along with its related data
        $header = LSStartUpProduksiChecklistHeader::with([
            'oilProduct',
            'details.langkahkerjaStartup' // Eager-load details and the related 'langkahKerja' for each detail
        ])->findOrFail($id);

        $sortedDetails = $header->details->filter(function ($detail) {
            return $detail->langkahKerjaStartup !== null;
        })->sortBy('langkahkerjaStartup.code');

        // Fetch all questions, ordered by category and sort_no
        // $langkahKerja = MLangkahKerja::orderBy('code')
        //     ->orderBy('code')
        //     ->get();


        // Group questions by category
        $groupedDetails = $sortedDetails->groupBy('langkahkerjaStartup.category');

        return view('rpt_startup_produksi.preview_layout', compact('header', 'groupedDetails'));
    }

    /**
     * Generate a PDF for a specific report.
     */
    public function exportPdf(Request $request, $id)
    {
        // 1. Fetch the specific header by its ID.
        //    (Fixed the typo: 'langkahKerja' is now 'langkahkerja')
        $header = LSStartUpProduksiChecklistHeader::with([
            'oilProduct',
            'details.langkahkerjaStartup'
        ])->findOrFail($id); // <-- Use findOrFail to guarantee we have the report

        // 2. Sort details by sort_no from the related langkahKerja
        //    (I changed this to 'langkahkerja.code' to match your working preview function)
        $sortedDetails = $header->details->filter(function ($detail) {
            return $detail->langkahkerjaStartup !== null;
        })->sortBy('langkahkerjaStartup.code'); // <-- MATCHED to your preview function

        // 3. Group them by category
        $groupedDetails = $sortedDetails->groupBy('langkahkerjaStartup.category');


        $pdf = Pdf::loadView('exports.report_startup_produksi_pdf', [
            'header' => $header,
            'groupedDetails' => $groupedDetails
        ]);

        // Set paper size to A4 Portrait
        $pdf->setPaper('a4', 'portrait');

        // Get filename
        $fileName = 'startup-produksi-checklist-' . $header->id . '.pdf';

        if ($request->input('mode') == 'preview') {
            return $pdf->stream($fileName); // Show in browser
        }
        return $pdf->download($fileName); // Download file
    }
}
