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
  protected $fillable = ['option_sub_class_id','abbreviation','input_type','number_format','order'];

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

  /*public function pivotOptions()
  {
    return $this->belongsToMany(
      Option::class,
      'autoban_category_option',
      'option_id',
      'option_id',
      'pivot.id',
      'id'
    );
  }*/

  public function AutobanCategory()
  {
    return $this->hasMany(AutobanCategory::class);
  }


  public function order($option_sub_class_id)
  {
    return Parent::where('option_sub_class_id',$option_sub_class_id)
      ->latest('order')
      ->get('order')
      ->first() ?: (object) ['order'=>-1];
  }
}
