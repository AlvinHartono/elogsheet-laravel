<?php

namespace App\Exports;

use App\Models\LSPretreatmentBleachingFiltration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LSPretreatmentBleachingFiltrationExport implements FromCollection, WithHeadings, WithEvents
{
    protected $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        return LSPretreatmentBleachingFiltration::whereDate('transaction_date', $this->tanggal)
            ->where('flag', 'T')
            ->orderBy('time', 'asc')
            ->get()
            ->map(function ($row) {
                return [
                    optional($row->time)->format('H:i'),
                    $row->pt_fit001,
                    $row->pt_e001a_inlet,
                    $row->pt_f0012,
                    $row->pt_h3po4,
                    $row->pt_be,
                    $row->bl_vacum,
                    $row->bl_t_inlet,
                    $row->bl_t_b602,
                    $row->bl_spurge,
                    $row->p_a,
                    $row->p_b,
                    $row->p_c,
                    $row->fn_f601,
                    $row->fn_f602,
                    $row->fn_f603,
                    $row->fb_604a,
                    $row->fb_604b,
                    $row->fb_604c,
                    $row->fc_605a,
                    $row->fc_605b,
                    $row->clarity,
                    $row->remarks,
                ];
            });
    }

    public function headings(): array
    {
        // Note: Update Form No and other details as needed for this report
        return [
            ['Form No.     : F/RFA-002'],
            ['Date Issued  : 210101'],
            ['Revision     : 01'],
            ['Rev. Date    : 210901'],
            [],
            [
                'Time',
                'Flow',
                'T Inlet',
                'Flow BE',
                'Flow H3PO4',
                'Flow BE',
                'Vacum',
                'T Inlet',
                'T B602',
                'Spurge',
                'P(A)',
                'P(B)',
                'P(C)',
                'FN F601',
                'FN F602',
                'FN F603',
                'FB 604A',
                'FB 604B',
                'FB 604C',
                'FC 605A',
                'FC 605B',
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
                    $sheet->mergeCells("A{$row}:W{$row}"); // Merged to W (23 columns)
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                }

                foreach (range('A', 'W') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $sheet->getStyle('A6:W6')->getFont()->setBold(true);
                $sheet->getStyle('A6:W6')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A6:W6')->getAlignment()->setVertical('center');
            }
        ];
    }
}