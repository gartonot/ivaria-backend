<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dishes extends Model
{
    use HasFactory;

    public function dish_categories(){
        return $this->belongsTo(DishCategories::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_dish', 'dish_id', 'order_id')->withPivot('count', 'price');
    }
}
