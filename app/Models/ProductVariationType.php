<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariationType extends Model
{
    use HasFactory;

    protected $fillable= ['product_id','variation_id','sort'];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    
}
