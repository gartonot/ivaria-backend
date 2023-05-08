<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'username',
        'phone',
        'total_amount',
    ];

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dishes::class, 'order_dish', 'order_id', 'dish_id')->withPivot('count', 'price');
    }

    public function totalPrice(): float
    {
        return $this->dishes->reduce(function ($carry, $item) {
            return $carry + $item->pivot->count * $item->pivot->price;
        }, 0);
    }
}
