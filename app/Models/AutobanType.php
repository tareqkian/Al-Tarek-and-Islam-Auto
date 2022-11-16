<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobanType extends Model implements TranslatableContract
{
  use HasFactory, Translatable;
  public $translatedAttributes = ['type_title'];

  protected static function boot()
  {
    parent::boot(); // TODO: Change the autogenerated stub
    static::addGlobalScope("type_title",function (Builder $builder){
      $builder
        ->withAggregate('translations','type_title')
        ->orderBy('translations_type_title');
    });
  }


  public function autobans()
  {
    return $this->hasMany(Autoban::class,'autoban_type_id');
  }
}
