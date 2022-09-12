<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableAlias;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionClass extends Model implements TranslatableAlias
{
  use HasFactory, Translatable;
  public $translatedAttributes = ['option_class_title'];

  public function sub_classes()
  {
    return $this->hasMany(OptionSubClass::class,'option_class_id');
  }
  public function children()
  {
    return $this->hasMany(OptionSubClass::class,'option_class_id');
  }
}
