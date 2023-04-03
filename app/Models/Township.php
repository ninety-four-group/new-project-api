<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Township extends Model
{
    use HasFactory;

    protected $fillable = ['name','mm_name','city_id'];

    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
}

