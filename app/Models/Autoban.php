<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autoban extends Model
{
  use HasFactory;
  protected $fillable = [
    'autoban_model_id',
    'autoban_type_id',
    'autoban_year_id',
    'autoban_price_id',
    'price_list_appearance',
    'market_availability',
    'order',
  ];

  public function model()
  {
    return $this->belongsTo(AutobanModel::class,'autoban_model_id')->with('translations','brand');
  }
  public function type()
  {
    return $this->belongsTo(AutobanType::class,'autoban_type_id')->with('translations');
  }
  public function year()
  {
    return $this->belongsTo(AutobanYear::class,'autoban_year_id')->with('translations');
  }
  public function price()
  {
    return $this->belongsTo(AutobanPrice::class,'autoban_price_id')->with('translations');
  }
  public function pivots()
  {
    return $this->hasMany(AutobanCategory::class);
  }
  public function autobanCateory()
  {
    return $this->belongsToMany(
      OptionCategory::class,
      'autoban_category'
    );
  }
  public function latestOrder($autoban_model_id)
  {
    return (Parent::where('autoban_model_id',$autoban_model_id)
      ->latest('order')
      ->first() ?: (Object) ['order'=>0])->order;
  }
}
