<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Konsumen extends Model
{
    use HasFactory;

    protected $table = 'konsumens'; // pastikan ini sesuai nama tabel di DB
    protected $fillable = ['nama','no_hp','email','alamat','sumber_lead','status','user_id'];

    // Relasi ke user
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function followUps() {
        return $this->hasMany(FollowUp::class);
    }

    public function transaksis() {
        return $this->hasMany(Transaksi::class);
    }

    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'konsumen_produk');
    }

}
