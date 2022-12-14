<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableAlias;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobanBrand extends Model implements TranslatableAlias
{
  use HasFactory, Translatable;

  public $translatedAttributes = ['brand_title'];
  protected $fillable = ['brand_image'];


  protected static function boot()
  {
    parent::boot(); // TODO: Change the autogenerated stub
    static::addGlobalScope("brand_title",function (Builder $builder){
      $builder
        ->withAggregate('translations','brand_title')
        ->orderBy('translations_brand_title');
    });
  }


  public function models()
  {
    return $this->hasMany(AutobanModel::class)->with('translations');
  }
  public function task()
  {
    return $this->belongsTo(AutobanPriceTask::class,'autoban_brand_id');
  }
}
