<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkuVariation extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['sku_id','variation_id'];

    public function variation()
    {
        return $this->belongsTo(Variation::class,'variation_id');
    }
}
