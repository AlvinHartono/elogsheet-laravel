<?php

namespace App\Exports;

use App\Models\QualityReportRefinery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QualityRefineryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    public function collection()
    {
        return QualityReportRefinery::all();
    }

    public function headings(): array
    {
        return [
            [
                'Time (WITA)',
                'Tank Source',
                'Flow Rate',
                'FFA',
                'IV',
                'PV',
                'AnV',
                'DOBI',
                'Carotene',
                'M&I',
                'Color',
                'PA',
                'BE',
                'Break Test',
                'FFA',
                'Color (R/Y/B)',
                'Color',
                'Break Test',
                'FFA',
                'R/Y',
                'B',
                'PV',
                'M&I',
                'Color',
                'Tank No.',
                'Product',
                'Purity',
                'Tank No.',
                'OIC',
                '%',
                'PIC',
                'Remarks'
            ],
            [
                '',
                '', // for Time & Tank
                'CPO',
                'CPO',
                'CPO',
                'CPO',
                'CPO',
                'CPO',
                'CPO',
                'Chemical',
                'Chemical',
                'Chemical',
                'Chemical',
                'Chemical',
                'Chemical',
                'BPO',
                'BPO',
                'BPO',
                'BPO',
                'BPO',
                'RPO',
                'RPO',
                'RPO',
                'RPO',
                'PFAD',
                'PFAD',
                'PFAD',
                'Spent Earth',
                'Spent Earth',
                '',
                ''
            ]
        ];
    }

    public function map($row): array
    {
        return [
            optional($row->time)->format('H:i'),
            $row->p_tank_source,

            // CPO
            $row->p_flow_rate,
            $row->p_ffa,
            $row->p_iv,
            $row->p_pv,
            $row->p_anv,
            $row->p_dobi,
            $row->p_carotene,
            $row->p_mi,

            // Chemical
            $row->c_color,
            $row->c_pa,
            $row->c_be,
            $row->c_break_test,
            $row->c_ffa,
            $row->c_color_ryb,

            // BPO
            $row->bpo_color,
            $row->bpo_break,
            $row->bpo_ffa,
            $row->bpo_ry,
            $row->bpo_b,

            // RPO
            $row->rpo_pv,
            $row->rpo_mi,
            $row->rpo_color,
            $row->rpo_tank,

            // PFAD
            $row->pfad_product,
            $row->pfad_purity,
            $row->pfad_tank,

            // Spent Earth
            $row->spent_oic,
            $row->spent_percent,

            $row->pic,
            $row->remarks
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // First 2 rows = merged header
            1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            2 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge header for group labels
                $sheet->mergeCells('C1:J1'); // CPO
                $sheet->mergeCells('K1:P1'); // Chemical
                $sheet->mergeCells('Q1:U1'); // BPO
                $sheet->mergeCells('V1:Y1'); // RPO
                $sheet->mergeCells('Z1:AB1'); // PFAD
                $sheet->mergeCells('AC1:AD1'); // Spent Earth
                $sheet->mergeCells('AE1:AE2'); // PIC
                $sheet->mergeCells('AF1:AF2'); // Remarks
                $sheet->mergeCells('A1:A2');  // Time
                $sheet->mergeCells('B1:B2');  // Tank Source

                // Auto width
                foreach (range('A', 'AF') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Add borders
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];
                $highestRow = $sheet->getHighestRow();
                $highestCol = $sheet->getHighestColumn();
                $sheet->getStyle("A1:{$highestCol}{$highestRow}")->applyFromArray($styleArray);
            }
        ];
    }
}
