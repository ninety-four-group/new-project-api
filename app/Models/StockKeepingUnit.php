<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Variation;
use App\Models\Warehouse;
use App\Models\SkuVariation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockKeepingUnit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['product_id','quantity','price','status','last_updated_user_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variations()
    {
        return $this->hasMany(SkuVariation::class,'sku_id');
    }

    public function warehouses()
    {
        return $this->hasMany(SkuWarehouse::class,'sku_id');
    }

}
