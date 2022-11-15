<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $role = Role::where("name","admin")->firstOrFail();
    User::firstOrCreate([
      "role_id" => $role->id,
      "name" => "Administrator",
      "email" => "admin@admin.com",
      "password" => bcrypt("admin"),
      "settings" => '{"devices":[{"device":"Desktop","count":10},{"device":"Mobile","count":10}]}'
    ]);
  }
}
