<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Konsumen;
use App\Models\Target;
use App\Models\Transaksi;
use App\Models\FollowUp;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        // =========================
        // TARGET
        // =========================
        $target = Target::when(!$isAdmin, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->first();

        $targetLead  = $target ? $target->target_lead : 0;
        $targetOmset = $target ? $target->target_omset : 0;

        // =========================
        // LEAD
        // =========================
        $totalLead = Konsumen::when(!$isAdmin, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // =========================
        // DEAL
        // =========================
        $deal = Konsumen::when(!$isAdmin, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'Deal')
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
        // CLOSING (bulan ini)
        // =========================
        $closing = Konsumen::when(!$isAdmin, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'Deal')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        // =========================
        // OMSET (LUNAS)
        // =========================
        $totalOmset = Transaksi::when(!$isAdmin, function ($q) use ($user) {
                $q->whereHas('konsumen', function ($qq) use ($user) {
                    $qq->where('user_id', $user->id);
                });
            })
            ->where('status', 'Lunas')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        // =========================
        // PROGRESS
        // =========================
        $progress = ($targetOmset > 0)
            ? min(($totalOmset / $targetOmset) * 100, 100)
            : 0;

        // =========================
        // KPI (PER USER)
        // =========================
        $kpi = Konsumen::selectRaw('user_id, count(*) as total')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('user_id')
            ->with('user')
            ->get();

        // =========================
        // FOLLOW UP HARI INI
        // =========================
        $followups = FollowUp::with('konsumen')
            ->when(!$isAdmin, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereDate('follow_up_date', now())
            ->orderBy('follow_up_date')
            ->get();

        // =========================
        // SUDAH BAYAR
        // =========================
        $sudahBayar = Konsumen::when(!$isAdmin, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereHas('transaksis', function ($q) {
                $q->where('status', 'Lunas');
            })
            ->with(['transaksis' => function ($q) {
                $q->where('status', 'Lunas')->with('produk');
            }])
            ->get();

        // =========================
        // BELUM BAYAR
        // =========================
        $belumBayar = Konsumen::when(!$isAdmin, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereHas('transaksis', function ($q) {
                $q->where('status', 'Belum Bayar');
            })
            ->with(['transaksis' => function ($q) {
                $q->where('status', 'Belum Bayar')->with('produk');
            }])
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
            'belumBayar'
        ));
    }
}
