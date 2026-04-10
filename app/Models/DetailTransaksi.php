<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksis';

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'qty',
        'harga',
        'harga_satuan',
        'subtotal' // 🔥 WAJIB biar tidak error
    ];

    // =========================
    // RELASI
    // =========================

    // ke transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // ke produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // =========================
    // AUTO HITUNG SUBTOTAL
    // =========================
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->subtotal = $model->qty * $model->harga_satuan; // ✅ FIX
        });

        static::updating(function ($model) {
            $model->subtotal = $model->qty * $model->harga_satuan; // ✅ FIX
        });
    }
}
