<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AutobanCategory extends Pivot
{
  public function autoban()
  {
    return $this->belongsTo(Autoban::class,'autoban_id');
  }
  public function category()
  {
    return $this->belongsTo(OptionCategory::class,'option_category_id');
  }
  public function options()
  {
    return $this->belongsToMany(
      Option::class,
      'autoban_category_option',
      'autoban_category_id',
      'option_id');
  }
}
