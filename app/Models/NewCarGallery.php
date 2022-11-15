<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewCarGallery extends Model
{
  use HasFactory;
  protected $fillable = ['image'];

  public function models()
  {
    return $this->belongsTo(AutobanModel::class,'autoban_model_id');
  }
}
