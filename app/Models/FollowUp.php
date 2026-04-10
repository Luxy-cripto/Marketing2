<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FollowUp extends Model
{
    use HasFactory;

    // ❌ Ini yang kurang
    protected $table = 'follow_ups';

    protected $fillable = [
        'konsumen_id',
        'transaksi_id',
        'user_id',
        'status',
        'catatan',
        'follow_up_date',
    ];

    protected $casts = [
        'follow_up_date' => 'datetime',
    ];

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function transaksi()
        {
            return $this->belongsTo(Transaksi::class, 'transaksi_id');
        }
}
