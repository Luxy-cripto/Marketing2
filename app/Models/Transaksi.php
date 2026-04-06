<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    public const STATUS_LUNAS = 'Lunas';
    public const STATUS_BELUM = 'Belum Bayar';

    protected $fillable = [
        'konsumen_id',
        'produk_id',
        'qty',
        'harga_satuan',
        'total',
        'tanggal_transaksi',
        'status'
    ];

    // 🔥 TARUH DI SINI
    protected static function booted()
    {
        static::creating(function ($transaksi) {
            $transaksi->total = $transaksi->qty * $transaksi->harga_satuan;

            // default status kalau kosong
            if (!$transaksi->status) {
                $transaksi->status = self::STATUS_BELUM;
            }
        });

        static::updating(function ($transaksi) {
            $transaksi->total = $transaksi->qty * $transaksi->harga_satuan;
        });
    }

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
        public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }
}
