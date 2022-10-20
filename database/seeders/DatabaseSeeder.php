<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->call([
      MenuItemsTableSeeder::class,
      MenusTableSeeder::class,
      PermissionRoleTableSeeder::class,
      PermissionsTableSeeder::class,
      RolesTableSeeder::class,
      SettingsTableSeeder::class,
      UsersTableSeeder::class
    ]);
    $this->call(AutobansTableSeeder::class);
      $this->call(MenuItemsTableSeeder::class);
        $this->call(MenuItemTranslationsTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
    }
}
