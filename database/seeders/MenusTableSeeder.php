<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::firstOrCreate(['name' => '/','importedComponent' => '/src/components/Layouts/DefaultLayout.vue']);
        Menu::firstOrCreate(['name' => '/admin','importedComponent' => '/src/components/Layouts/DefaultLayout.vue']);
    }
}
