<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresServis extends Model
{
    protected $table = 'progres_servis';

    protected $fillable = [
        'booking_id',
        'status_log',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
