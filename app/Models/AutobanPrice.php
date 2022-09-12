<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableAlias;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobanPrice extends Model implements TranslatableAlias
{
    use HasFactory, Translatable;
    public $translatedAttributes = ["official", "commercial", "sale"];

    public function autoban()
    {
      return $this->belongsTo(Autoban::class,'id');
    }
}
