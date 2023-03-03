<?php

namespace App\Models;

use App\Models\Warehouse;
use App\Models\StockKeepingUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkuWarehouse extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = ['warehouse_id','sku_id','quantity'];

    public function sku()
    {
        return $this->belongsTo(StockKeepingUnit::class,'sku_id','id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }

}
