<?php

namespace App\Http\Controllers;

use App\Models\LSDryFractionation;
use App\Models\MMastervalue;
use App\Models\MDataFormNo;
use App\Models\MControlnumber;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LogsheetDryFraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $workCenter = MMastervalue::select('code', 'name')
            ->where('group', 'REFINERY')
            ->orderBy('number')
            ->get();

        $oilType = MMastervalue::select('code')
            ->where('group', 'OIL_TYPE')
            ->orderBy('number')
            ->get();


        return view('log_dryfractination.index', compact('workCenter', 'oilType'));
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
    // pastikan sudah ada model ini

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'work_center' => 'required|string',
            'oil_type' => 'required|string',
            'batch' => 'required|array|min:1',
        ]);

        $plant = session('plant_code', '-');
        $company = session('business_unit_code', null);

        // 🔹 Ambil data form
        $DataForm = MDataFormNo::select('f_code', 'f_date_issued', 'f_revision_no', 'f_revision_date')
            ->where('is_menu', 'Logsheet_Dry_Fractination')
            ->where('is_active', 'T')
            ->first();

        // 🔹 Ambil control number
        $control = MControlnumber::where('plantid', 'PS21')
            ->where('imenu', 'Monitoring Dry Fractination')
            ->where('category_apps', 'Web')
            ->first();

        if (!$control) {
            return back()->with('error', 'Control number tidak ditemukan.');
        }

        // Simpan nomor awal supaya tahu kenaikan autonumber
        $startAuto = $control->autonumber;

        // 🔹 Loop setiap batch, buat ticket unik
        foreach (array_values($request->batch) as $i => $batch) {
            $currentAuto = $startAuto + ($i + 1);
            $number = str_pad($currentAuto, $control->lengthpad, '0', STR_PAD_LEFT);
            $ticketNo = $control->prefix . $control->plantid . $control->accountingyear . $number;

            LSDryFractionation::create([
                'id' => $ticketNo, // gunakan ticketNo sebagai ID unik
                'company' => $company,
                'plant' => $plant,

                // Header form
                'transaction_date' => $request->tanggal,
                'posting_date' => now(),
                'work_center' => $request->work_center,
                'oil_type' => $request->oil_type,

                // Detail batch
                'crystalizier' => $batch['crystallizer'] ?? null,
                'filling_start_time' => $batch['filling_start_time'] ?? null,
                'filling_end_time' => $batch['filling_end_time'] ?? null,
                'colling_start_time' => $batch['cooling_start_time'] ?? null,
                'initial_oil_level' => $batch['initial_oil_level'] ?? null,
                'initial_tank' => $batch['initial_tank'] ?? null,
                'feed_iv' => $batch['feed_iv'] ?? null,
                'agitator_speed' => $batch['agitator'] ?? null,
                'water_pump_press' => $batch['pump'] ?? null,
                'crystal_start_time' => $batch['crystal_start'] ?? null,
                'crystal_temp' => $batch['crystal_temp'] ?? null,
                'filtration_start_time' => $batch['filtration_start'] ?? null,
                'filtration_temp' => $batch['filtration_temp'] ?? null,
                'filtration_cycle_no' => $batch['filtration_cycle'] ?? null,
                'filtration_oil_level' => $batch['final_oil'] ?? null,
                'olein_iv_red' => $batch['olein_iv'] ?? null,
                'olein_cloud_point' => $batch['cloud_point'] ?? null,
                'stearin_iv' => $batch['stearin_iv'] ?? null,
                'stearin_slep_point_red' => $batch['slip_point'] ?? null,
                'olein_yield' => $batch['yield'] ?? null,

                // Lain-lain
                'remarks' => $request->catatan ?? null,
                'entry_by' => Auth::check() ? Auth::user()->username : null,
                'entry_date' => now(),

                // Form data
                'form_no' => $DataForm?->f_code,
                'date_issued' => $DataForm?->f_date_issued,
                'revision_no' => $DataForm?->f_revision_no,
                'revision_date' => $DataForm?->f_revision_date,
            ]);
        }

        // 🔹 Update autonumber sekali setelah semua batch tersimpan
        MControlnumber::where('plantid', $control->plantid)
            ->where('imenu', $control->imenu)
            ->where('category_apps', $control->category_apps)
            ->update(['autonumber' => $startAuto + count($request->batch)]);

        return back()->with('success', 'Data berhasil disimpan. Ticket terakhir: ' . $ticketNo);
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
}
