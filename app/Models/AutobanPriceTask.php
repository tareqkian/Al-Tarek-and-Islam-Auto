<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobanPriceTask extends Model
{
  use HasFactory;
  protected $fillable = ['autoban_brand_id','duration'];

  public function brand()
  {
    return $this->belongsTo(AutobanBrand::class,'autoban_brand_id');
  }
}
