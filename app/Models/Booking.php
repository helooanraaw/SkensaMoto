<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'workshop_schedule_id', 'service_id',
        'motorcycle_type', 'license_plate', 'complaint',
        'status', 'rejection_reason',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function schedule() { return $this->belongsTo(WorkshopSchedule::class, 'workshop_schedule_id'); }
    public function service()  { return $this->belongsTo(Service::class); }
    public function messages() { return $this->hasMany(Message::class)->orderBy('created_at'); }
}
