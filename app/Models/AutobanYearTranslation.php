<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobanYearTranslation extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $fillable = ['year_number'];
}
