<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Thumbnail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_thumbnail_name'
    ];
}
