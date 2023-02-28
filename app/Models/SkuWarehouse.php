<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkuWarehouse extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = ['warehouse_id','sku_id','quantity'];
}
