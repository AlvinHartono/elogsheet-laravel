<?php

namespace App\Http\Controllers;

use App\Models\LSMaintenanceChangeProductHeader;
use App\Models\LSMaintenanceChangeProductDetail;
use App\Models\MLangkahKerja;
use App\Models\MProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class RptChangeProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        //
        $tanggal = $request->input('filter_tanggal');
        $time = $request->input('filter_time');

        $products = MProduct::select('*')->get();

        if (!$tanggal) {
            $tanggal = now()->startOfMonth()->format('Y-m-d');
        }
        if (!$time) {
            $time = '00:00:00';
        }

        $headers = LSMaintenanceChangeProductHeader::with([
            'details' => function ($query) {
                $query->select('id', 'id_hdr', 'check_item', 'status_item');
            }, // Loads the hasMany relationship
            'firstProduct' => function ($query) {
                $query->select('id', 'raw_material');
            }, // Loads the belongsTo relationship
            'nextProduct' => function ($query) {
                $query->select('id', 'raw_material');
            } // Loads the other belongsTo relationship
        ])
            ->whereHas('details') // This ensures we only get headers that HAVE details (like your INNER JOIN)
            // ->where(function ($query) {
            //     $query->whereNotNull('prepared_status')
            //         ->orWhereNotNull('checked_status');
            // })
            ->whereDate('transaction_date', $tanggal)
            ->where('flag', 'T')
            ->orderBy('id', 'asc')
            ->paginate(10)->withQueryString();

        return view('rpt_change_product.index', compact('headers', 'products', 'tanggal', 'time'));
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
        $header = LSMaintenanceChangeProductHeader::with([
            'firstProduct' => fn($q) => $q->select('id', 'raw_material'),

            'nextProduct' => fn($q) => $q->select('id', 'raw_material'),

            'details' => function ($query) {
                $query->with(['langkahKerja' => fn($q) => $q->select('code', 'name', 'sort_no')])
                    ->join('m_langkahkerja', 't_change_product_checklist_detail.check_item', '=', 'm_langkahkerja.code')
                    ->select('t_change_product_checklist_detail.*')
                    // Order the final list of details by the 'sort_no'
                    ->orderBy('m_langkahkerja.code', 'asc');
            }

        ])->findOrFail($id);

        return view('rpt_change_product.show', compact('header'));
    }

    /**
     * Approve as "Prepared By" (Shift Leader)
     */
    public function verifyApproval(Request $request, $id)
    {
        $header = LSMaintenanceChangeProductHeader::findOrFail($id);

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
        $header = LSMaintenanceChangeProductHeader::findOrFail($id);

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
        $header = LSMaintenanceChangeProductHeader::findOrFail($id);

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
        $header = LSMaintenanceChangeProductHeader::findOrFail($id);

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
        $header = LSMaintenanceChangeProductHeader::with([
            'firstProduct',
            'nextProduct',
            'details.langkahkerja' // Eager-load details and the related 'langkahKerja' for each detail
        ])->findOrFail($id);

        $sortedDetails = $header->details->filter(function ($detail) {
            return $detail->langkahKerja !== null;
        })->sortBy('langkahkerja.code');

        // Fetch all questions, ordered by category and sort_no
        // $langkahKerja = MLangkahKerja::orderBy('code')
        //     ->orderBy('code')
        //     ->get();


        // Group questions by category
        $groupedDetails = $sortedDetails->groupBy('langkahkerja.category');

        return view('rpt_change_product.preview_layout', compact('header', 'groupedDetails'));
    }

    /**
     * Generate a PDF for a specific report.
     */
    public function exportPdf(Request $request, $id)
    {
        // 1. Fetch the specific header by its ID.
        //    (Fixed the typo: 'langkahKerja' is now 'langkahkerja')
        $header = LSMaintenanceChangeProductHeader::with([
            'firstProduct',
            'nextProduct',
            'details.langkahkerja' // <-- TYPO FIXED
        ])->findOrFail($id); // <-- Use findOrFail to guarantee we have the report

        // 2. Sort details by sort_no from the related langkahKerja
        //    (I changed this to 'langkahkerja.code' to match your working preview function)
        $sortedDetails = $header->details->filter(function ($detail) {
            return $detail->langkahkerja !== null;
        })->sortBy('langkahkerja.code'); // <-- MATCHED to your preview function

        // 3. Group them by category
        $groupedDetails = $sortedDetails->groupBy('langkahkerja.category');


        $pdf = Pdf::loadView('exports.report_maintenance_change_product_pdf', [
            'header' => $header,
            'groupedDetails' => $groupedDetails
        ]);

        // Set paper size to A4 Portrait
        $pdf->setPaper('a4', 'portrait');

        // Get filename
        $fileName = 'change-product-checklist-' . $header->id . '.pdf';

        if ($request->input('mode') == 'preview') {
            return $pdf->stream($fileName); // Show in browser
        }
        return $pdf->download($fileName); // Download file
    }
}
