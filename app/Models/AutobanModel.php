<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableAlias;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobanModel extends Model implements TranslatableAlias
{
  use HasFactory, Translatable;
  protected $table = 'autoban_brands';
  protected $translationForeignKey = 'autoban_brand_id';
  public $translatedAttributes = ['model_title'];
  protected $fillable = ['model_image'];

  public function brand(){
    return $this->belongsTo(AutobanBrand::class,'autoban_brand_id');
  }
}
