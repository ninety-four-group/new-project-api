<?php

namespace App\Models;

use App\Models\StockKeepingUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'brand_id','category_id','name','mm_name','video_url','description','mm_description','status','last_updated_user_id'
    ];

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }


    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }


    public function media()
    {
        return $this->belongsToMany(Media::class, 'product_media', 'product_id', 'media_id');
    }


    public function warehouse()
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouses', 'product_id', 'warehouse_id');
    }


    public function lastUpdatedUser()
    {
        return $this->hasOne(Admin::class, 'id', 'last_updated_user_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }

    public function sku()
    {
        return $this->hasMany(StockKeepingUnit::class, 'product_id', 'id');
    }

    public function stock()
    {
        return $this->hasMany(SkuWarehouse::class, 'product_id', 'id');
    }

}
