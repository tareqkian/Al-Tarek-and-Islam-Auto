<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleTranslation;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $role = Role::firstOrCreate(["name" => "admin","display_name" => "Administrator"]);
    RoleTranslation::firstOrCreate(["role_id" => $role->id, "locale" => "en", "display_name" => "Administrator"]);
    RoleTranslation::firstOrCreate(["role_id" => $role->id, "locale" => "ar", "display_name" => "مدير"]);
  }
}
