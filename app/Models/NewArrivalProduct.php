<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewArrivalProduct extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','is_enabled'];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
