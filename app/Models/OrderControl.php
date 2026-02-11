<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderControl extends Model
{
    protected $table = 'order_control';

    protected $fillable = [
        'pickup_enabled',
        'pickup_disabled_message',
        'delivery_enabled',
        'delivery_disabled_message',
    ];

    protected $casts = [
        'pickup_enabled'  => 'boolean',
        'delivery_enabled' => 'boolean',
    ];
}
