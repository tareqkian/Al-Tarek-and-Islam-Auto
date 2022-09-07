<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableAlias;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionCategory extends Model implements TranslatableAlias
{
  use HasFactory, Translatable;
  public $translatedAttributes = ['option_category_title'];
  protected $fillable = ['option_sub_class_id','abbreviation','input_type','number_format'];

  public function option_sub_class()
  {
    return $this->belongsTo(OptionSubClass::class,'option_sub_class_id')
      ->with('option_class');
  }

  public function options()
  {
    return $this->hasMany(Option::class);
  }
  public function children()
  {
    return $this->hasMany(Option::class);
  }
}