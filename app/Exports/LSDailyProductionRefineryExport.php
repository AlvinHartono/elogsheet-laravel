<?php

namespace App\Exports;

use App\Models\LSDailyProductionRefinery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LSDailyProductionRefineryExport implements FromCollection, WithHeadings, WithEvents
{
    protected $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        return LSDailyProductionRefinery::whereDate('transaction_date', $this->tanggal)
            ->where('flag', 'T')
            ->orderBy('shift', 'asc')
            ->get()
            ->map(function ($row) {
                // Formatting time and outputting key data points
                return [
                    $row->work_center,
                    $row->shift,
                    $row->cpo_tank,
                    $row->oil_type_rm,
                    optional($row->oil_type_rm_awal_jam)->format('H:i'),
                    $row->oil_type_rm_awal_flowmeter,
                    optional($row->oil_type_rm_akhir_jam)->format('H:i'),
                    $row->oil_type_rm_akhir_flowmeter,
                    $row->oil_type_rm_total,

                    $row->oil_type_fg,
                    optional($row->oil_type_fg_awal_jam)->format('H:i'),
                    $row->oil_type_fg_awal_flowmeter,
                    optional($row->oil_type_fg_akhir_jam)->format('H:i'),
                    $row->oil_type_fg_akhir_flowmeter,
                    $row->oil_type_fg_total,
                    $row->oil_type_fg_to_tank,

                    optional($row->bp_awal_jam)->format('H:i'),
                    $row->bp_awal_flowmeter,
                    optional($row->bp_akhir_jam)->format('H:i'),
                    $row->bp_akhir_flowmeter,
                    $row->bp_total,
                    $row->bp_to_tank,

                    $row->be_ref_tank,
                    $row->be_ref_qty,
                    $row->be_total_bag,
                    $row->be_total_jenis,
                    $row->be_yield_percent,

                    $row->pa_ref_tank,
                    $row->pa_ref_qty,
                    $row->pa_total,
                    $row->pa_yield_percent,

                    $row->uu_total_cpo,
                    $row->uu_total_steam,
                    $row->remarks,
                ];
            });
    }

    // TODO: Adjust the headings
    public function headings(): array
    {
        // Headers are set to span 33 columns (A to AG)
        return [
            ['Form No.     : F/RPR-001'],
            ['Date Issued  : 210101'],
            ['Revision     : 01'],
            ['Rev. Date    : 210901'],
            [],
            [
                'Work Center',
                'Shift',
                'CPO Tank',
                'RM Oil Type',
                // RM CPO Input
                'Start Time', 'Start Flowmeter', 'End Time', 'End Flowmeter', 'Total',
                // FG Output
                'FG Oil Type', 'Start Time', 'Start Flowmeter', 'End Time', 'End Flowmeter', 'Total', 'To Tank',
                // By Product (BP) Output
                'Start Time', 'Start Flowmeter', 'End Time', 'End Flowmeter', 'Total', 'To Tank',
                // Spent Earth (BE)
                'Ref. Tank', 'Ref. Qty', 'Total Bag', 'Total Jenis', 'Yield (%)',
                // Phospatidic Acid (PA)
                'Ref. Tank', 'Ref. Qty', 'Total', 'Yield (%)',
                // Utilities
                'Total CPO', 'Total Steam',
                'Remarks'
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastCol = 'AG'; // 33 columns total

                // Merge Form Info rows
                foreach ([1, 2, 3, 4] as $row) {
                    $sheet->mergeCells("A{$row}:{$lastCol}{$row}");
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                }

                // Merge Group Headers (Row 6)
                $sheet->mergeCells('D6:I6'); // RM Oil Type + CPO Input
                $sheet->mergeCells('J6:Q6'); // FG Output
                $sheet->mergeCells('R6:W6'); // BP Output
                $sheet->mergeCells('X6:AB6'); // Spent Earth (BE)
                $sheet->mergeCells('AC6:AF6'); // Phospatidic Acid (PA)
                $sheet->mergeCells('AG6:AG6'); // Remarks

                // Auto width
                foreach (range('A', $lastCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Header style
                $sheet->getStyle("A6:{$lastCol}6")->getFont()->setBold(true);
                $sheet->getStyle("A6:{$lastCol}6")->getAlignment()->setHorizontal('center')->setVertical('center');
            }
        ];
    }
}