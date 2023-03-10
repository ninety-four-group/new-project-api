<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['name','slug','media_id'];

    public function media()
    {
        return $this->belongsTo(Media::class,'media_id');
    }
    // protected function image(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => Storage::disk('public')->url($value),
    //     );
    // }
}
