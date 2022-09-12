<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionClassTranslation extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $fillable = ['option_class_title'];
}
