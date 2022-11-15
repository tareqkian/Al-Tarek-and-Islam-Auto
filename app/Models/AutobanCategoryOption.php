<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AutobanCategoryOption extends pivot
{
  public function autoban_category()
  {
    return $this->belongsTo(
      AutobanCategory::class,
      'autoban_category_id',
      'id',
    );
  }

/*  public function autoban()
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
  }*/
}
