<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariationType extends Model
{
    use HasFactory;

    protected $fillable= ['product_id','type','sort'];


    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    // protected function type(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value) => json_decode($value)
    //     );
    // }

}
