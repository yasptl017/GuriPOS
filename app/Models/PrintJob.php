<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'printer', 'content', 'status', 'printed_at', 'error'];

    // status constants
    const STATUS_PENDING = 0;
    const STATUS_PRINTED = 1;
    const STATUS_FAILED  = 2;
}
