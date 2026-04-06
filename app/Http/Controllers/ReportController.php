<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // =========================
        // FILTER TANGGAL (FIX)
        // =========================
        $start = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $end = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth();

        // =========================
        // TOTAL TRANSAKSI
        // =========================
        $totalTransaksi = Transaksi::whereBetween('created_at', [$start, $end])
                            ->count();

        // =========================
        // TOTAL OMZET (FIX STATUS)
        // =========================
        $totalOmzet = Transaksi::whereBetween('created_at', [$start, $end])
                        ->whereIn('status', [
                            'success','berhasil','lunas','paid','selesai'
                        ])
                        ->sum('total');

        // =========================
        // PRODUK TERLARIS
        // =========================
        $produkTerlaris = DB::table('transaksis')
            ->join('produks', 'transaksis.produk_id', '=', 'produks.id')
            ->select(
                'produks.nama',
                DB::raw('COUNT(transaksis.id) as total')
            )
            ->whereBetween('transaksis.created_at', [$start, $end])
            ->groupBy('produks.nama')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // =========================
        // TRANSAKSI TERBARU
        // =========================
        $transaksi = Transaksi::with('konsumen','produk')
                        ->whereBetween('created_at', [$start, $end])
                        ->latest()
                        ->limit(10)
                        ->get();

        return view('reports.index', compact(
            'totalTransaksi',
            'totalOmzet',
            'produkTerlaris',
            'transaksi',
            'start',
            'end'
        ));
    }
}
