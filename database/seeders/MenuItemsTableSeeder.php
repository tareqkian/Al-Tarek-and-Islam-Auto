<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $menu = Menu::where('name', '/')->firstOrFail();
    MenuItem::firstOrCreate([
      "menu_id" => $menu->id,
      "title" => "Dashboard",
      "target" => "_self",
      "icon_class" => "feather feather-home",
      "order" => 1,
      "route" => "dashboard",
      "importedComponent" => "/src/Views/Dashboards/Dashboard.vue",
    ]);

    $menu = Menu::where('name', '/admin')->firstOrFail();
    MenuItem::firstOrCreate([
      "menu_id" => $menu->id,
      "title" => "Admin Dashboard",
      "target" => "_self",
      "icon_class" => "feather feather-home",
      "order" => 1,
      "route" => "dashboard",
      "importedComponent" => "/src/Views/Admin/Dashboard.vue",
    ]);
    MenuItem::firstOrCreate([
      "menu_id" => $menu->id,
      "title" => "Menu Builder",
      "target" => "_self",
      "icon_class" => "feather feather-home",
      "order" => 2,
      "route" => "menuBuilder",
      "importedComponent" => "/src/Views/Admin/MenuBuilder.vue",
    ]);
    MenuItem::firstOrCreate([
      "menu_id" => $menu->id,
      "title" => "Roles",
      "target" => "_self",
      "icon_class" => "feather feather-lock",
      "order" => 3,
      "route" => "roles",
      "importedComponent" => "/src/Views/Admin/Roles.vue",
    ]);
    MenuItem::firstOrCreate([
      "menu_id" => $menu->id,
      "title" => "Users",
      "target" => "_self",
      "icon_class" => "fa fa-user-o",
      "order" => 4,
      "route" => "users",
      "importedComponent" => "/src/Views/Admin/Users.vue",
    ]);
    MenuItem::firstOrCreate([
      "menu_id" => $menu->id,
      "title" => "Settings",
      "target" => "_self",
      "icon_class" => "feather feather-sliders",
      "order" => 5,
      "route" => "settings",
      "importedComponent" => "/src/Views/Admin/Settings.vue",
    ]);
  }
}
