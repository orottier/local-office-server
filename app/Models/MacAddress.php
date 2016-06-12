<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MacAddress extends Model
{
    protected $fillable = [
        'mac_address',
    ];

    protected $visible = [
        'id', 'mac_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
