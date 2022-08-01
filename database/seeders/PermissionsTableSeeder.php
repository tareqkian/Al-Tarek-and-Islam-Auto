<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::generateFor("admin_roles");
        Permission::generateFor("admin_menubuilder");
        Permission::generateFor("admin_dashboard");
        Permission::generateFor("dashboards_dashboard");
        Permission::generateFor("admin_users");
    }
}
