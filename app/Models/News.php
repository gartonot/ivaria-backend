<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    public function news_categories(){
        return $this->belongsTo(NewsCategories::class, 'categories_id');
    }

}
