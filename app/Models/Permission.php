<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
  use HasFactory;

  protected $fillable = ["key","table_name"];

  public function roles()
  {
    return $this->belongsToMany(Role::class,"permission_role");
  }


  public static function generateFor($table_name)
  {
    $data = [];
    $data[] = self::firstOrCreate(['key' => 'browse_'.$table_name, 'table_name' => $table_name]);
    $data[] = self::firstOrCreate(['key' => 'read_'.$table_name, 'table_name' => $table_name]);
    $data[] = self::firstOrCreate(['key' => 'edit_'.$table_name, 'table_name' => $table_name]);
    $data[] = self::firstOrCreate(['key' => 'add_'.$table_name, 'table_name' => $table_name]);
    $data[] = self::firstOrCreate(['key' => 'delete_'.$table_name, 'table_name' => $table_name]);
    return $data;
  }

}
