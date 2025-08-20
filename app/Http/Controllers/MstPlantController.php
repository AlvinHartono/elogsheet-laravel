<?php

namespace App\Http\Controllers;

use App\Models\MBusinessUnit;
use Illuminate\Http\Request;
use App\Models\MPlant;

class MstPlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = MPlant::query(); // ambil semua data dari tabel m_plant

        if ($request->filled('kode')) {
            $query->where('plant_code', 'like', '%' . $request->kode . '%');
        }
        $plants = $query->paginate(10);

        return view('mst_plant.index', compact('plants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $businessUnits = MBusinessUnit::all();

        return view('mst_plant.create', compact('businessUnits'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'plant_code' => 'required|string|max:10|unique:m_plant,plant_code',
            'plant_name' => 'required|string|max:100',
            'bu_code' => 'required|exists:m_business_unit,bu_code',
            'isactive' => 'required|in:T,F',
        ]);

        MPlant::create($request->only(['plant_code', 'plant_name', 'bu_code', 'isactive']));

        return redirect()->route('master-plant.index')->with('success', 'Data Plant berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($plant_code)
    {
        $plant = MPlant::where('plant_code', $plant_code)->firstOrFail();
        $businessUnits = MBusinessUnit::all();

        return view('mst_plant.edit', compact('plant', 'businessUnits'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $plant_code)
    {
        $request->validate([
            'plant_name' => 'required|string|max:100',
            'plant_name' => 'required|string|max:100',
            'bu_code' => 'required|exists:m_business_unit,bu_code',
            'isactive' => 'required|in:T,F',
        ]);

        $plant = MPlant::where('plant_code', $plant_code)->firstOrFail();

        $plant->update($request->only(['plant_name', 'plant_name', 'bu_code', 'isactive']));

        return redirect()->route('master-plant.index')->with('success', 'Data Plant berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($plant_code)
    {
        $plant = MPlant::findOrFail($plant_code);
        $plant->delete();

        return redirect()->route('master-plant.index')->with('success', 'Data berhasil dihapus');
    }
}
