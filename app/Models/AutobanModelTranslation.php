<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobanModelTranslation extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $fillable = ['model_title'];
}
