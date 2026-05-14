<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'nama_bengkel',
        'alamat',
        'nomor_telepon',
        'email_bengkel',
        'logo_path',
    ];
}
