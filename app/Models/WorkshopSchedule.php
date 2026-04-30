<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopSchedule extends Model
{
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'quota',
        'status'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'workshop_schedule_id');
    }
}
