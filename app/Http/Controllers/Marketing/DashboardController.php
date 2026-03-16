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

        // Filter bulan/tahun dari query param (default bulan ini)
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        // ===== TARGET BULANAN =====
        $target = Target::where('user_id', $user->id)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->first();

        $targetLead = $target->target_lead ?? 0;
        $targetOmset = $target->target_omset ?? 0;

        // ===== TOTAL LEAD =====
        $totalLead = Konsumen::where('user_id', $user->id)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        // ===== DEAL & TIDAK TERTARIK =====
        $deal = Konsumen::where('user_id', $user->id)
            ->whereRaw('LOWER(TRIM(status))="deal"')
            ->count();

        $tidakTertarik = Konsumen::where('user_id', $user->id)
            ->whereRaw('LOWER(TRIM(status))="tidak tertarik"')
            ->count();

        // ===== CLOSING BULAN INI =====
        $closing = Konsumen::where('user_id', $user->id)
            ->whereRaw('LOWER(TRIM(status))="deal"')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        // ===== OMSET BULAN INI =====
        $totalOmset = Transaksi::whereHas('konsumen', fn($q) => $q->where('user_id', $user->id))
            ->whereRaw('LOWER(TRIM(status))="sudah bayar"')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->sum('total');

        // ===== PROGRESS TARGET =====
        $progress = $targetOmset > 0 ? min(($totalOmset / $targetOmset) * 100, 100) : 0;

        // ===== KPI LEAD PER MARKETING =====
        $kpi = Konsumen::selectRaw('user_id, count(*) as total')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->groupBy('user_id')
            ->with('user')
            ->get();

        // ===== FOLLOW UP HARI INI =====
        $followups = FollowUp::with('konsumen')
            ->where('user_id', $user->id)
            ->whereDate('follow_up_date', now())
            ->orderBy('follow_up_date')
            ->get();

        // ===== DEAL SUDAH BAYAR =====
        $sudahBayar = Konsumen::where('user_id', $user->id)
            ->whereHas('transaksis', fn($q) => $q->whereRaw('LOWER(TRIM(status))="sudah bayar"'))
            ->with(['transaksis' => fn($q) => $q->whereRaw('LOWER(TRIM(status))="sudah bayar"')])
            ->get();

        // ===== DEAL BELUM BAYAR =====
        $belumBayar = Konsumen::where('user_id', $user->id)
            ->whereRaw('LOWER(TRIM(status))="deal"')
            ->whereDoesntHave('transaksis', fn($q) => $q->whereRaw('LOWER(TRIM(status))="sudah bayar"'))
            ->with('transaksis')
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

    // API follow-up hari ini
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
