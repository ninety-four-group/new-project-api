<?php

namespace App\Models;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SkuWarehouse;

class SkuVariation extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['sku_id','variation_id','warehouse_id'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class,'variation_id');
    }

  
}
