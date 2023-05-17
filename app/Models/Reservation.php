<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes;

    private const NEW_STATUS = 'new';

    protected $fillable = [
        'name',
        'phone',
        'guests',
        'date',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    protected $attributes = [
        'status' => self::NEW_STATUS,
    ];
}
