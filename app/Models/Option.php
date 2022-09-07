<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableAlias;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Parent_;

class Option extends Model implements TranslatableAlias
{
  use HasFactory, Translatable;
  public $translatedAttributes = ['option_title'];
  protected $fillable = ['option_category_id','abbreviation','order'];

  public function option_category()
  {
    return $this->belongsTo(OptionCategory::class,'option_category_id')
      ->with('translations','option_sub_class');
  }

  public function autobans()
  {
    return $this->belongsToMany(Option::class);
  }

  public function order($option_category_id)
  {
    return Parent::where('option_category_id',$option_category_id)
      ->latest('order')
      ->get('order')
      ->first() ?: (object) ['order'=>-1];
  }
}