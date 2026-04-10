<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Konsumen;
use App\Models\Transaksi;
use App\Models\FollowUp;
use App\Models\Target;
use App\Models\DetailTransaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now();

        // =========================
        // 1. STATISTIK UTAMA
        // =========================
        $totalKonsumen = Konsumen::count();
        $totalProspek = Konsumen::where('status', 'Prospek')->count();
        $totalDeal = Konsumen::where('status', 'Deal')->count();

        $totalOmset = Transaksi::where('status', 'Lunas')->sum('total');

        // =========================
        // 2. GRAFIK DEAL PER BULAN
        // =========================
        $dealData = Konsumen::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('count(*) as total')
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
        // 3. 🔥 TOP PRODUK (AMAN)
        // =========================
        $produkTerlaris = DetailTransaksi::with('produk')
            ->select('produk_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('produk_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $labelsProduk = $produkTerlaris->map(function($item){
            return $item->produk->nama ?? 'Produk Dihapus';
        });

        $dataQtyProduk = $produkTerlaris->pluck('total_qty');

        // =========================
        // 4. FOLLOW UP
        // =========================
        $followUps = FollowUp::with('konsumen')->latest()->limit(10)->get();

        $todayFollowUps = FollowUp::whereDate('follow_up_date', Carbon::today())->get();

        // =========================
        // 5. TARGET NOTIF
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
                ->whereMonth('tanggal_transaksi', $today->month)
                ->whereYear('tanggal_transaksi', $today->year)
                ->where('status', 'Lunas')
                ->sum('total');

            if ($target->target_omset > 0 && $userOmset >= $target->target_omset) {
                $targetNotifications[] = [
                    'user_name' => $target->user->name ?? 'Sales',
                    'total_omset' => $userOmset,
                ];
            }
        }

        return view('admin.dashboard', compact(
            'totalKonsumen',
            'totalProspek',
            'totalDeal',
            'totalOmset',
            'dealPerBulan',
            'labelsProduk',
            'dataQtyProduk',
            'followUps',
            'todayFollowUps',
            'targetNotifications'
        ));
    }
}
