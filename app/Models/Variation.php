<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','variation_category_id','image'];

    public function variationCategory()
    {
        return $this->hasOne(VariationCategory::class, 'id');
    }
}
