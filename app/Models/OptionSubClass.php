<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableAlias;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionSubClass extends Model implements TranslatableAlias
{
  use HasFactory, Translatable;
  public $translatedAttributes = ['option_sub_class_title'];
  protected $fillable = ['option_class_id'];


  public function option_class()
  {
    return $this->belongsTo(OptionClass::class,'option_class_id');
  }

  public function option_categories()
  {
    return $this->hasMany(OptionCategory::class);
  }
  public function children()
  {
    return $this->hasMany(OptionCategory::class);
  }
}