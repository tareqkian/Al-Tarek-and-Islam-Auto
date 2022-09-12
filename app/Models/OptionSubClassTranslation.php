<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionSubClassTranslation extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $fillable = ['option_sub_class_title'];
}
