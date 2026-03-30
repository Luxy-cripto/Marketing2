<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Konsumen;
use App\Models\Target;
use App\Models\Transaksi;
use App\Models\FollowUp;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        // =========================
        // FILTER BULAN & TAHUN (AUTO SEKARANG)
        // =========================
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        // =========================
        // TARGET
        // =========================
        $target = Target::when(!$isAdmin, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->first();

        $targetLead  = $target->target_lead ?? 0;
        $targetOmset = $target->target_omset ?? 0;

        // =========================
        // LEAD MASUK
        // =========================
        $totalLead = Konsumen::when(!$isAdmin, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        // =========================
        // DEAL (LEAD JADI DEAL)
        // =========================
        $deal = Konsumen::when(!$isAdmin, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'Deal')
            ->whereMonth('updated_at', $bulan)
            ->whereYear('updated_at', $tahun)
            ->count();

        // =========================
        // TIDAK TERTARIK
        // =========================
        $tidakTertarik = Konsumen::when(!$isAdmin, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'Tidak Tertarik')
            ->count();

        // =========================
        // 🔥 CLOSING (SUDAH BAYAR)
        // =========================
        $closing = Transaksi::when(!$isAdmin, function ($q) use ($user) {
                $q->whereHas('konsumen', function ($qq) use ($user) {
                    $qq->where('user_id', $user->id);
                });
            })
            ->whereIn('status', ['Lunas','Sudah Bayar'])
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->count();

        // =========================
        // OMSET (HANYA YANG BAYAR)
        // =========================
        $totalOmset = Transaksi::when(!$isAdmin, function ($q) use ($user) {
                $q->whereHas('konsumen', function ($qq) use ($user) {
                    $qq->where('user_id', $user->id);
                });
            })
            ->whereIn('status', ['Lunas','Sudah Bayar'])
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('total');

        // =========================
        // 🔥 LUNAS (JUMLAH + TOTAL)
        // =========================
        $jumlahLunas = Transaksi::when(!$isAdmin, function ($q) use ($user) {
                $q->whereHas('konsumen', function ($qq) use ($user) {
                    $qq->where('user_id', $user->id);
                });
            })
            ->whereIn('status', ['Lunas','Sudah Bayar'])
            ->count();

        $totalLunas = Transaksi::when(!$isAdmin, function ($q) use ($user) {
                $q->whereHas('konsumen', function ($qq) use ($user) {
                    $qq->where('user_id', $user->id);
                });
            })
            ->whereIn('status', ['Lunas','Sudah Bayar'])
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('total');

        // =========================
        // 🔥 BELUM BAYAR
        // =========================
        $jumlahBelumBayar = Transaksi::when(!$isAdmin, function ($q) use ($user) {
                $q->whereHas('konsumen', function ($qq) use ($user) {
                    $qq->where('user_id', $user->id);
                });
            })
            ->where('status', 'Belum Bayar')
            ->count();

        $totalBelumBayar = Transaksi::when(!$isAdmin, function ($q) use ($user) {
                $q->whereHas('konsumen', function ($qq) use ($user) {
                    $qq->where('user_id', $user->id);
                });
            })
            ->where('status', 'Belum Bayar')
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('total');

        // =========================
        // PROGRESS TARGET OMSET
        // =========================
        $progress = ($targetOmset > 0)
            ? min(($totalOmset / $targetOmset) * 100, 100)
            : 0;

        // =========================
        // KPI MARKETING
        // =========================
        $kpi = Konsumen::selectRaw('user_id, count(*) as total')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->groupBy('user_id')
            ->with('user')
            ->get();

        // =========================
        // FOLLOW UP HARI INI
        // =========================
        $followups = FollowUp::with('konsumen')
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->whereDate('follow_up_date', now())
            ->get();

        // =========================
        // LIST SUDAH BAYAR
        // =========================
        $sudahBayar = Konsumen::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->whereHas('transaksis', fn($q) => $q->whereIn('status', ['Lunas','Sudah Bayar']))
            ->with(['transaksis' => fn($q) => $q->whereIn('status', ['Lunas','Sudah Bayar'])->with('produk')])
            ->get();

        // =========================
        // LIST BELUM BAYAR
        // =========================
        $belumBayar = Konsumen::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->whereHas('transaksis', fn($q) => $q->where('status', 'Belum Bayar'))
            ->with(['transaksis' => fn($q) => $q->where('status', 'Belum Bayar')->with('produk')])
            ->get();

        return view('marketing.dashboard', compact(
            'target',
            'targetLead',
            'targetOmset',
            'totalLead',
            'deal',
            'tidakTertarik',
            'closing',
            'totalOmset',
            'progress',
            'kpi',
            'followups',
            'sudahBayar',
            'belumBayar',

            // DATA TAMBAHAN
            'jumlahLunas',
            'totalLunas',
            'jumlahBelumBayar',
            'totalBelumBayar',

            'bulan',
            'tahun'
        ));
    }
}
