<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Variation;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockKeepingUnit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['product_id','variation_id','warehouse_id','quantity','price','status','last_updated_user_id'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }
}
