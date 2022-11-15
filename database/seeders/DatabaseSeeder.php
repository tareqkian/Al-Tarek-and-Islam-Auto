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
      /*MenusTableSeeder::class,
      MenuItemsTableSeeder::class,
      MenuItemTranslationsTableSeeder::class,
      PermissionsTableSeeder::class,
      RolesTableSeeder::class,
      PermissionRoleTableSeeder::class,
      SettingsTableSeeder::class,
      UsersTableSeeder::class,
      AutobanModelSeeder::class,
      AutobanTypeTableSeeder::class,*/
      OptionsTableSeeder::class,
    ]);
  }
}
