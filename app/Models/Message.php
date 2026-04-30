<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['booking_id', 'sender_id', 'is_admin', 'message', 'is_read'];

    public function sender()   { return $this->belongsTo(User::class, 'sender_id'); }
    public function booking()  { return $this->belongsTo(Booking::class); }
}
