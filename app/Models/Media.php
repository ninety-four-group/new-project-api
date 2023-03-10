<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['title','caption','alt_text','description','file'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_media', 'media_id', 'product_id');
    }
}
