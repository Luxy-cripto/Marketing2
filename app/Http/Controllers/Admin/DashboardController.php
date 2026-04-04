<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Konsumen;
use App\Models\Transaksi;
use App\Models\FollowUp;
use App\Models\Target;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now();

        // =========================
        // STATISTIK UTAMA
        // =========================
        $totalKonsumen = Konsumen::count();
        $totalProspek  = Konsumen::where('status', 'Prospek')->count();
        $totalDeal     = Konsumen::where('status', 'Deal')->count();
        $totalOmset    = Transaksi::sum('total');

        // =========================
        // DEAL PER BULAN
        // =========================
        $dealData = Konsumen::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->where('status', 'Deal')
            ->whereYear('created_at', $today->year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $dealPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $dealPerBulan[] = $dealData[$i] ?? 0;
        }

        // =========================
        // FOLLOW UP TERBARU
        // =========================
        $followUps = FollowUp::with(['konsumen', 'user'])
            ->latest()
            ->take(10)
            ->get();

        // =========================
        // FOLLOW UP HARI INI
        // =========================
        $todayFollowUps = FollowUp::whereDate('follow_up_date', $today->toDateString())
            ->count();

        // =========================
        // FOLLOW UP TERLEWAT (OVERDUE)
        // =========================
        $overdueFollowUps = FollowUp::whereDate('follow_up_date', '<', $today->toDateString())
            ->where('status', '!=', 'Deal')
            ->count();

        // =========================
        // TARGET OMSET BULAN INI
        // =========================
        $targets = Target::with('user')
            ->where('tahun', $today->year)
            ->where('bulan', $today->month)
            ->get();

        $targetNotifications = [];

        foreach ($targets as $target) {

            $userOmset = Transaksi::whereHas('konsumen', function ($q) use ($target) {
                    $q->where('user_id', $target->user_id);
                })
                ->whereMonth('created_at', $today->month)
                ->whereYear('created_at', $today->year)
                ->sum('total');

            // kalau target tercapai
            if ($userOmset >= $target->target_omset) {
                $targetNotifications[] = [
                    'user_name'   => $target->user->name,
                    'target_omset'=> $target->target_omset,
                    'total_omset' => $userOmset,
                ];
            }
        }

        // =========================
        // RETURN KE VIEW
        // =========================
        return view('admin.dashboard', compact(
            'totalKonsumen',
            'totalProspek',
            'totalDeal',
            'totalOmset',
            'dealPerBulan',
            'followUps',
            'todayFollowUps',
            'overdueFollowUps',
            'targetNotifications'
        ));
    }
}
