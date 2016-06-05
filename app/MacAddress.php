<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MacAddress extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $visible = [
        'mac_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
