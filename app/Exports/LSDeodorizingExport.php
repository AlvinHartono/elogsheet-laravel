<?php

namespace App\Exports;

use App\Models\LSDeodorizingFiltration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LSDeodorizingExport implements FromCollection, WithHeadings, WithEvents
{
    protected $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        return LSDeodorizingFiltration::whereDate('transaction_date', $this->tanggal)
            ->where('flag', 'T')
            ->orderBy('time', 'asc')
            ->get()
            ->map(function ($row) {
                return [
                    optional($row->time)->format('H:i'),
                    $row->fit701_bpo,
                    $row->d701_vacum,
                    $row->d701_td701,
                    $row->e702,
                    $row->thermopac_inlet,
                    $row->thermopac_outlet,
                    $row->d702_inlet,
                    $row->d702_outlet,
                    $row->d702_vacum,
                    $row->sparging_a,
                    $row->sparging_b,
                    $row->e730_inlet,
                    $row->steam_inlet,
                    $row->pish_706,
                    $row->tiwh_706,
                    $row->f702_a,
                    $row->f702_b,
                    $row->f702_c,
                    $row->fit704_rpo,
                    $row->e704,
                    $row->fit_705_pfad,
                    $row->e705,
                    $row->clarity,
                    $row->remarks,
                ];
            });
    }

    public function headings(): array
    {
        // Assuming F/RFA-003 is the next form number
        return [
            ['Form No.     : F/RFA-003'],
            ['Date Issued  : 210101'],
            ['Revision     : 01'],
            ['Rev. Date    : 210901'],
            [],
            [
                'Time',
                'Flow BPO',
                'D701 Vacum',
                'D701 T',
                'E702',
                'Thermopac Inlet',
                'Thermopac Outlet',
                'D702 Inlet',
                'D702 Outlet',
                'D702 Vacum',
                'Sparging A',
                'Sparging B',
                'E730 Inlet',
                'Steam Inlet',
                'PISH 706',
                'TIWH 706',
                'F702A',
                'F702B',
                'F702C',
                'Flow RPO',
                'E704',
                'Flow PFAD',
                'E705',
                'Clarity',
                'Remarks'
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                foreach ([1, 2, 3, 4] as $row) {
                    $sheet->mergeCells("A{$row}:Y{$row}"); // Merged to Y (25 columns)
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                }
                foreach (range('A', 'Y') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                $sheet->getStyle('A6:Y6')->getFont()->setBold(true);
                $sheet->getStyle('A6:Y6')->getAlignment()->setHorizontal('center')->setVertical('center');
            }
        ];
    }
}