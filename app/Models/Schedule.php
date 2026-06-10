<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'title',
        'type',
        'description',
        'start_date',
        'end_date',
        'user_id'
    ];
}
