<?php

namespace Modules\POS\Models;

use Illuminate\Database\Eloquent\Model;

class DatabaseCart extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'instance',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];
}
