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

        // =============================
        // TARGET BULANAN
        // =============================
        $target = Target::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->first();

        $targetLead = $target ? $target->target_lead : 0;
        $targetOmset = $target ? $target->target_omset : 0;

        // =============================
        // LEAD MASUK
        // =============================
        $totalLead = Konsumen::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // =============================
        // DEAL
        // =============================
        $deal = Konsumen::where('user_id', $user->id)
            ->where('status', 'Deal')
            ->count();

        // =============================
        // TIDAK TERTARIK
        // =============================
        $tidakTertarik = Konsumen::where('user_id', $user->id)
            ->where('status', 'Tidak Tertarik')
            ->count();

        // =============================
        // CLOSING BULAN INI
        // =============================
        $closing = Konsumen::where('user_id', $user->id)
            ->where('status', 'Deal')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        // =============================
        // OMSET BULAN INI
        // =============================
        $totalOmset = Transaksi::whereHas('konsumen', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        // =============================
        // PROGRESS TARGET OMSET
        // =============================
        $progress = ($targetOmset > 0)
            ? min(($totalOmset / $targetOmset) * 100, 100)
            : 0;

        // =============================
        // KPI LEAD PER MARKETING
        // =============================
        $kpi = Konsumen::selectRaw('user_id, count(*) as total')
            ->whereMonth('created_at', now()->month)
            ->groupBy('user_id')
            ->with('user')
            ->get();

        // =============================
        // FOLLOW UP HARI INI
        // =============================
        $followups = FollowUp::with('konsumen')
            ->where('user_id', $user->id)
            ->whereDate('follow_up_date', now())
            ->orderBy('follow_up_date')
            ->get();

        // =============================
        // DEAL SUDAH BAYAR
        // =============================
        $sudahBayar = Konsumen::where('user_id', $user->id)
            ->whereHas('transaksis')
            ->with('transaksis')
            ->get();

        // =============================
        // DEAL BELUM BAYAR
        // =============================
        $belumBayar = Konsumen::where('user_id', $user->id)
            ->where('status', 'Deal')
            ->whereDoesntHave('transaksis')
            ->get();

        return view('marketing.dashboard', compact(
            'target',
            'targetLead',
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

    public function followupsToday()
    {
        $user = Auth::user();

        $followups = FollowUp::with('konsumen')
            ->where('user_id', $user->id)
            ->whereDate('follow_up_date', now())
            ->get();

        return response()->json($followups);
    }
}
