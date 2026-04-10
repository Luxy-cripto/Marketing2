<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    public const STATUS_LUNAS = 'Lunas';
    public const STATUS_BELUM = 'Belum Bayar';

    protected $fillable = [
        'konsumen_id',
        'total',
        'tanggal_transaksi',
        'status'
    ];

    protected static function booted()
    {
        // total otomatis dihitung lewat store/update di controller, jadi ini optional
        static::creating(function ($transaksi) {
            if (!$transaksi->status) {
                $transaksi->status = self::STATUS_BELUM;
            }
        });
    }

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class);
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    // akses langsung ke produk lewat details
    public function produks()
    {
        return $this->hasManyThrough(
            Produk::class,
            DetailTransaksi::class,
            'transaksi_id', // FK detail ke transaksi
            'id',           // PK produk
            'id',           // PK transaksi
            'produk_id'     // FK detail ke produk
        );
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

}
