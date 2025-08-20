<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LSQualityReport;
use App\Models\LSLampGlassHeader;
use App\Models\MBusinessUnit;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== Total Produksi (contoh menggunakan fg_ffa) =====
        $totalProduksi = LSQualityReport::sum('fg_ffa') ?? 0;

        // ===== Approved / Rejected Counts =====
        $totalReportsQ = LSQualityReport::count();
        $approvedQ = LSQualityReport::where('checked_status', 'Approved')->count();
        $rejectedQ = LSQualityReport::where('checked_status', 'Rejected')->count();

        $totalReportsL = LSLampGlassHeader::count();
        $approvedL = LSLampGlassHeader::where('checked_status', 'Approved')->count();
        $rejectedL = LSLampGlassHeader::where('checked_status', 'Rejected')->count();

        // Gabungkan data untuk dashboard
        $totalReports = $totalReportsQ + $totalReportsL;
        $approvedReports = $approvedQ + $approvedL;
        $issuesCount = $rejectedQ + $rejectedL;
        $percentApproved = $totalReports > 0 ? round(($approvedReports / $totalReports) * 100) : 0;

        // ===== Recent Activity (5 terakhir gabungan LSQualityReport & LampGlass) =====
        $recentReportsQ = LSQualityReport::select(
            'id',
            'checked_date',
            'checked_by',
            'checked_status',
            'checked_status_remarks',
            DB::raw("'QualityReport' as source")
        )->whereNotNull('checked_date');

        $recentReportsL = LSLampGlassHeader::select(
            'id',
            'checked_date',
            'checked_by',
            'checked_status',
            'checked_status_remarks',
            DB::raw("'LampGlass' as source")
        )->whereNotNull('checked_date');

        $recentReports = $recentReportsQ->unionAll($recentReportsL)
            ->orderBy('checked_date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($report) {
                return [
                    'source' => $report->source,
                    'checked_date' => $report->checked_date?->format('d M Y H:i') ?? '-',
                    'checked_by' => $report->checked_by ?? '-',
                    'checked_status' => $report->checked_status ?? 'Pending',
                    'checked_status_remarks' => $report->checked_status_remarks ?? '-',
                ];
            });

        $summaryQuality = [
            'total' => LSQualityReport::count(),
            'approved' => LSQualityReport::where('checked_status', 'Approved')->count(),
        ];

        $summaryLampGlass = [
            'total' => LSLampGlassHeader::count(),
            'approved' => LSLampGlassHeader::where('checked_status', 'Approved')->count(),
        ];

        return view('dashboard', compact(
            'totalProduksi',
            'percentApproved',
            'issuesCount',
            'recentReports',
            'summaryQuality',
            'summaryLampGlass'

        ));
    }
}
