<?php

namespace App\Exports;

use App\Models\MBusinessUnit;
use Maatwebsite\Excel\Concerns\FromCollection;

class BusinessUnitExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return MBusinessUnit::all();
    }
}
