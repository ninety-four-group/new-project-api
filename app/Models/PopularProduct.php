<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularProduct extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','is_enabled'];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

}
