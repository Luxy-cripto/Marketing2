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

        // --- KPI Tim Marketing (lead per user bulan ini) ---
        $kpi = Konsumen::selectRaw('user_id, count(*) as total')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('user_id')
            ->with('user')
            ->get();

        // --- Lead Masuk bulan ini (semua) ---
        $leadMasuk = Konsumen::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // --- Closing bulan ini khusus marketing login ---
        $closing = Konsumen::where('user_id', $user->id)
            ->where('status', 'Deal')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        // --- Target Bulanan (ambil dari created_at jika bulan/tahun tidak disimpan) ---
        $target = Target::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->first();

        // --- Total Omset: SUM transaksi yang konsumen Deal bulan ini ---
        $totalOmset = Transaksi::whereHas('konsumen', function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('status', 'Deal');
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        // --- Hitung progress target (cek target_omset > 0) ---
        $progress = ($target && $target->target_omset > 0)
            ? min(($totalOmset / $target->target_omset) * 100, 100)
            : 0;

        // --- Follow-up hari ini untuk marketing login ---
        $followups = FollowUp::with('konsumen')
            ->where('user_id', $user->id)
            ->whereDate('follow_up_date', now())
            ->orderBy('follow_up_date')
            ->get();

        // --- Semua konsumen untuk modal tambah follow-up ---
        $konsumens = Konsumen::orderBy('nama')->get();

        // --- Optional: count follow-up pending hari ini ---
        $followupPendingCount = FollowUp::where('user_id', $user->id)
            ->whereIn('status', ['Belum Dihubungi','Follow-Up'])
            ->whereDate('follow_up_date', now())
            ->count();

        return view('marketing.dashboard', compact(
            'kpi',
            'leadMasuk',
            'closing',
            'target',
            'totalOmset',
            'progress',
            'followups',
            'konsumens',
            'followupPendingCount'
        ));
    }

    /**
     * API untuk follow-ups hari ini (dipakai live update JS)
     */
    public function followupsToday()
    {
        $user = Auth::user();

        $followups = FollowUp::with('konsumen')
            ->where('user_id', $user->id)
            ->whereDate('follow_up_date', now())
            ->orderBy('follow_up_date')
            ->get();

        return response()->json($followups);
    }
}
