<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleTranslation extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $fillable = ["display_name"];
}
