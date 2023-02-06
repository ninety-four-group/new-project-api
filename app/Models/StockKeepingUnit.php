<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockKeepingUnit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['product_id','variation_id','warehouse_id','quantity','price','status','last_updated_user_id'];
}
