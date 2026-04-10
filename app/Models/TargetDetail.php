<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetDetail extends Model
{
    protected $fillable = [
        'target_id',
        'produk_id',
        'target_omset_produk',
        'target_qty',
    ];

    public function target()
    {
        return $this->belongsTo(Target::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

}
