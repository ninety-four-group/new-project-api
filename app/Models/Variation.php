<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','media_id','type','type_value'];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
