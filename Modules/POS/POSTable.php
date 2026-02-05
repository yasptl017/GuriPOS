<?php

namespace Modules\POS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POSTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'meta',
        'cart',
        'resolved_order',
    ];

    protected $casts = [
        'meta' => 'array',
        'cart' => 'array',
        'resolved_order' => 'array',
    ];

    public function getOccupiedAttribute()
    {
        return !!$this->cart;
    }

}
