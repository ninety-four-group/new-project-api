<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','mm_name','start_date','end_date'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_collections', 'collection_id', 'product_id');
    }

}
