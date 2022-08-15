<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableAlias;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobanBrand extends Model implements TranslatableAlias
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['brand_title'];
    protected $fillable = ['brand_image'];

    public function models()
    {
      return $this->hasMany(AutobanModel::class,'autoban_brand_id');
    }
}
