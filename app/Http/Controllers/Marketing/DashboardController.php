<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Konsumen;
use App\Models\Target;
use App\Models\Transaksi;
use App\Models\FollowUp;
use App\Models\TargetDetail;
use App\Models\Produk;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        // =========================
        // TARGET (ANTI KOSONG)
        // =========================
        $target = Target::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->first();

        if (!$target) {
            $target = Target::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
                ->latest()
                ->first();
        }

        $targetLead = $target->target_lead ?? 0;
        $targetOmset = $target->target_omset ?? 0;

        // =========================
        // LEAD MASUK (ANTI 0 🔥)
        // =========================
        $totalLead = Konsumen::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        // fallback kalau bulan ini kosong
        if ($totalLead == 0) {
            $totalLead = Konsumen::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
                ->count();
        }

        // =========================
        // DEAL
        // =========================
        $deal = Konsumen::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->whereRaw('LOWER(status) = ?', ['deal'])
            ->count();

        // =========================
        // TIDAK TERTARIK
        // =========================
        $tidakTertarik = Konsumen::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->whereRaw('LOWER(status) = ?', ['tidak tertarik'])
            ->count();

        // =========================
        // BASE TRANSAKSI
        // =========================
        $transaksiQuery = Transaksi::when(!$isAdmin, function ($q) use ($user) {
            $q->whereHas('konsumen', fn($qq) => $qq->where('user_id', $user->id));
        });

        // =========================
        // CLOSING & OMSET
        // =========================
        $closing = (clone $transaksiQuery)
            ->whereRaw('LOWER(status) = ?', ['lunas'])
            ->count();

        $totalOmset = (clone $transaksiQuery)
            ->whereRaw('LOWER(status) = ?', ['lunas'])
            ->sum('total');

        // =========================
        // LUNAS
        // =========================
        $jumlahLunas = (clone $transaksiQuery)
            ->whereRaw('LOWER(status) = ?', ['lunas'])
            ->count();

        $totalLunas = (clone $transaksiQuery)
            ->whereRaw('LOWER(status) = ?', ['lunas'])
            ->sum('total');

        // =========================
        // BELUM BAYAR
        // =========================
        $jumlahBelumBayar = (clone $transaksiQuery)
            ->whereRaw('LOWER(status) = ?', ['belum bayar'])
            ->count();

        $totalBelumBayar = (clone $transaksiQuery)
            ->whereRaw('LOWER(status) = ?', ['belum bayar'])
            ->sum('total');

        // =========================
        // PROGRESS
        // =========================
        $progress = ($targetOmset > 0)
            ? min(($totalOmset / $targetOmset) * 100, 100)
            : 0;

        // =========================
        // KPI (ANTI KOSONG)
        // =========================
        if ($isAdmin) {
            $kpi = Konsumen::selectRaw('user_id, count(*) as total')
                ->groupBy('user_id')
                ->with('user')
                ->get();
        } else {
            $kpi = collect([
                (object) [
                    'user' => $user,
                    'total' => Konsumen::where('user_id', $user->id)->count()
                ]
            ]);
        }

        // =========================
        // FOLLOW UP
        // =========================
        $followups = FollowUp::with('konsumen')
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->whereDate('follow_up_date', now())
            ->get();

        // =========================
        // SUDAH BAYAR
        // =========================
        $sudahBayar = Konsumen::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->whereHas('transaksis', fn($q) => $q->whereRaw('LOWER(status) = ?', ['lunas']))
            ->with(['transaksis' => fn($q) => $q->whereRaw('LOWER(status) = ?', ['lunas'])->with('produk')])
            ->get();

        // =========================
// TARGET PER PRODUK (BULAN INI)
// =========================
        $targetDetails = TargetDetail::with('produk')
            ->whereHas('target', function ($q) use ($bulan, $tahun, $user, $isAdmin) {
                $q->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun);

                if (!$isAdmin) {
                    $q->where('user_id', $user->id);
                }
            })
            ->get();


        // =========================
// OMSET PER PRODUK (BULAN INI)
// =========================
        $omsetPerProduk = Transaksi::selectRaw('produk_id, SUM(total) as total')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->when(!$isAdmin, function ($q) use ($user) {
                $q->whereHas('konsumen', fn($qq) => $qq->where('user_id', $user->id));
            })
            ->groupBy('produk_id')
            ->pluck('total', 'produk_id');

        // =========================
        // BELUM BAYAR
        // =========================
        $belumBayar = Konsumen::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->whereHas('transaksis', fn($q) => $q->whereRaw('LOWER(status) = ?', ['belum bayar']))
            ->with(['transaksis' => fn($q) => $q->whereRaw('LOWER(status) = ?', ['belum bayar'])->with('produk')])
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
            'jumlahLunas',
            'totalLunas',
            'jumlahBelumBayar',
            'totalBelumBayar',
            'bulan',
            'tahun',
            'targetDetails',
            'omsetPerProduk',
        ));
    }
}
