<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableAlias;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobanModel extends Model implements TranslatableAlias
{
  use HasFactory, Translatable;
  public $translatedAttributes = ['model_title'];
  protected $fillable = ['model_image'];

  public function brand()
  {
    return $this->belongsTo(AutobanBrand::class,'autoban_brand_id')
      ->with('translations');
  }
  public function autobans()
  {
    return $this->hasMany(Autoban::class,'autoban_model_id');
  }

  public function gallery()
  {
    return $this->hasMany(NewCarGallery::class,'autoban_model_id');
  }
}
