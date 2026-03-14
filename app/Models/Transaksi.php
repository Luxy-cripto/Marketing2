<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'konsumen_id',
        'produk_id',
        'qty',
        'harga_satuan',
        'total'
    ];

    protected static function booted()
    {
        static::creating(function ($transaksi) {
            $transaksi->total = $transaksi->qty * $transaksi->harga_satuan;
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
}
