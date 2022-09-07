<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableAlias;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobanYear extends Model implements TranslatableAlias
{
  use HasFactory, Translatable;
  public $translatedAttributes = ['year_number'];

  public function autobans()
  {
    return $this->hasMany(Autoban::class,'autoban_year_id');
  }
}
