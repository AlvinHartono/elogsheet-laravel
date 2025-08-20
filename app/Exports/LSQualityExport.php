<?php

namespace App\Exports;

use App\Models\LSQualityReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;

class LSQualityExport implements FromCollection, WithHeadings, WithEvents
{
    protected $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        return LSQualityReport::whereDate('transaction_date', $this->tanggal)
            ->orderBy('time', 'asc')
            ->get()
            ->map(function ($row) {
                return [
                    optional($row->time)->format('H:i'),
                    $row->rm_tank_source,
                    $row->rm_temp,
                    $row->rm_ffa,
                    $row->rm_iv,
                    $row->rm_dobi,
                    $row->rm_av,
                    $row->{'rm_m&i'},
                    $row->rm_pv,
                    $row->bo_color,
                    $row->bo_break_test,
                    $row->fg_ffa,
                    $row->fg_iv,
                    $row->fg_pv,
                    $row->{'fg_m&i'},
                    $row->fg_color_r,
                    $row->fg_color_y,
                    $row->fg_tank_to,
                    $row->bp_ffa,
                    $row->{'bp_m&i'},
                    $row->w_sbe_qc,
                ];
            });
    }

    public function headings(): array
    {
        return [
            [
                'Form No.     : F/RFA-001',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                'Date Issued  : 191101',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                'Revision     : 01',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                'Rev. Date    : 210901',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [],
            [
                'Time',
                'Source (Tank)',
                // Raw Material
                'Temp (°C)',
                'FFA (%)',
                'IV',
                'DOBI',
                'AV',
                'M&I (%)',
                'PV (%)',
                // Bleach Oil
                'Color R',
                'BREAK TEST',
                // RBD Oil
                'FFA (%)',
                'IV',
                'PV',
                'M&I',
                'Color R',
                'Color Y',
                'To Tank',
                // Fatty Acid
                'FFA (%)',
                'M&I',
                // SBE
                'QC (%)'
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Styling: Merge and bold Form Info
                $sheet = $event->sheet->getDelegate();

                // Merge Form Info rows (merge across 22 columns)
                foreach ([1, 2, 3, 4] as $row) {
                    $sheet->mergeCells("A{$row}:V{$row}");
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                }

                // Set column widths (optional)
                foreach (range('A', 'V') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Header style
                $sheet->getStyle('A6:V6')->getFont()->setBold(true);
                $sheet->getStyle('A6:V6')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A6:V6')->getAlignment()->setVertical('center');
            }
        ];
    }
}
