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
        // FILTER TANGGAL
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
        $totalTransaksi = Transaksi::whereBetween(
            'created_at',
            [$start, $end]
        )->count();

        // =========================
        // TOTAL OMZET
        // =========================
        $totalOmzet = Transaksi::whereBetween(
            'created_at',
            [$start, $end]
        )
        ->where('status', 'Lunas')
        ->sum('total');

        // =========================
        // PRODUK TERLARIS
        // =========================
        $produkTerlaris = DB::table('detail_transaksis')
            ->join(
                'produks',
                'detail_transaksis.produk_id',
                '=',
                'produks.id'
            )
            ->join(
                'transaksis',
                'detail_transaksis.transaksi_id',
                '=',
                'transaksis.id'
            )
            ->select(
                'produks.nama',
                DB::raw('COUNT(detail_transaksis.id) as total')
            )
            ->whereBetween(
                'transaksis.created_at',
                [$start, $end]
            )
            ->groupBy(
                'produks.id',
                'produks.nama'
            )
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // =========================
        // TRANSAKSI TERBARU
        // =========================
        $transaksi = Transaksi::with([
                'konsumen',
                'produks'
            ])
            ->whereBetween(
                'created_at',
                [$start, $end]
            )
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
