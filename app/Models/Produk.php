<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    // Tabel yang digunakan
    protected $table = 'produks';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'stok',
    ];

    // Relasi ke Transaksi
    public function transaksis()
    {
        return $this->hasMany(\App\Models\Transaksi::class);
    }

    public function konsumens()
    {
        return $this->belongsToMany(Konsumen::class, 'konsumen_produk');
    }
}
