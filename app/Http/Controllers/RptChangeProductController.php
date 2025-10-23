<?php

namespace App\Http\Controllers;

use App\Models\LSMaintenanceChangeProductHeader;
use App\Models\MProduct;
use Illuminate\Http\Request;

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

        $product = MProduct::select('*')->get();

        if (!$tanggal) {
            $tanggal = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
        }
        if (!$time) {
            $time = '00:00:00';
        }

        $headers = LSMaintenanceChangeProductHeader::with([
            'details', // Loads the hasMany relationship
            'firstProduct', // Loads the belongsTo relationship
            'nextProduct' // Loads the other belongsTo relationship
        ])
            ->whereHas('details') // This ensures we only get headers that HAVE details (like your INNER JOIN)
            ->whereDate('transaction_date', $tanggal)
            ->orderBy('id', 'asc')
            ->paginate(10)->withQueryString();

        return view('rpt_change_product.index', compact('headers', 'product', 'tanggal', 'time'));
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
}
