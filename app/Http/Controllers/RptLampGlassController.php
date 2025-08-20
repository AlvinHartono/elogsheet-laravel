<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LSLampGlassHeader;

class RptLampGlassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tanggalAwal = $request->input('filter_tanggal_awal');
        $tanggalAkhir = $request->input('filter_tanggal_akhir');

        if (!$tanggalAwal || !$tanggalAkhir) {
            $tanggalAwal = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
            $tanggalAkhir = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $query = LSLampGlassHeader::with('details')
            ->whereBetween('check_date', [
                \Carbon\Carbon::parse($tanggalAwal)->startOfDay(),
                \Carbon\Carbon::parse($tanggalAkhir)->endOfDay()
            ])
            ->orderBy('check_date', 'asc');

        // pakai paginate (10 per halaman)
        $documents = $query->paginate(10)->appends([
            'filter_tanggal_awal' => $tanggalAwal,
            'filter_tanggal_akhir' => $tanggalAkhir,
        ]);

        return view('rpt_lamp_glass.index', compact('documents', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function approve($id)
    {
        $doc = LSLampGlassHeader::findOrFail($id);
        $doc->checked_by = auth()->user()->username ?? auth()->user()->name;
        $doc->checked_date = now();
        $doc->checked_status = 'Approved';
        $doc->save();

        return back()->with('success', 'Document approved successfully.');
    }

    public function reject($id)
    {
        $doc = LSLampGlassHeader::findOrFail($id);
        $doc->checked_by = auth()->user()->username ?? auth()->user()->name;
        $doc->checked_date = now();
        $doc->checked_status = 'Rejected';
        $doc->save();

        return back()->with('success', 'Document rejected successfully.');
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
        //
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

    public function exportLayoutPreview(Request $request)
    {
        // Ambil filter tanggal jika ada, default ambil bulan ini
        $tanggalAwal = $request->input('filter_tanggal_awal', now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->input('filter_tanggal_akhir', now()->endOfMonth()->format('Y-m-d'));

        // Ambil semua header beserta detail di rentang tanggal
        $documents = LSLampGlassHeader::with('details')
            ->whereBetween('check_date', [
                \Carbon\Carbon::parse($tanggalAwal)->startOfDay(),
                \Carbon\Carbon::parse($tanggalAkhir)->endOfDay()
            ])
            ->orderBy('check_date', 'asc')
            ->get();

        // Ambil tanggal unik dari data header untuk kolom tabel
        $uniqueDates = $documents->pluck('check_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->sort()
            ->values();

        // Tentukan month & year dari tanggal pertama
        $firstDate = $uniqueDates->first();
        $month = $firstDate ? \Carbon\Carbon::parse($firstDate)->format('F') : '';
        $year  = $firstDate ? \Carbon\Carbon::parse($firstDate)->format('Y') : '';

        return view('rpt_lamp_glass.preview', compact('documents', 'uniqueDates', 'month', 'year'));
    }
}
