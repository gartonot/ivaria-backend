<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishCategories extends Model
{
    use HasFactory;

    public function dishes(){
        return $this->hasMany(Dishes::class);
    }


}
