<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','mm_name','slug','parent_id','media_id'];

    protected $hidden = [
        'parent_id',
    ];

    protected $dates = ['deleted_at'];


    // protected function image(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => Storage::disk('public')->url($value),
    //     );
    // }

    public function media()
    {
        return $this->belongsTo(Media::class,'media_id');
    }

    public function subcategory()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
