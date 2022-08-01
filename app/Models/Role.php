<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  use HasFactory, Translatable;

  protected $translatedAttributes = ["display_name"];
  protected $fillable = ["name", "display_name"];

  public function users()
  {
    return $this->belongsToMany(User::class,"user_roles")
      ->select(app(User::class)->getTable().".*")
      ->union($this->hasMany(User::class))->getQuery();
  }

  public function permissions()
  {
    return $this->belongsToMany(Permission::class,'permission_role');
  }

}
