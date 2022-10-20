<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('menu_items')->delete();

        \DB::table('menu_items')->insert(array (
            0 =>
            array (
                'id' => 11,
                'menu_id' => 1,
                'url' => '',
                'target' => '_self',
                'icon_class' => 'feather feather-home',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 2,
                'route' => 'dashboard',
                'parameters' => 'null',
                'importedComponent' => '/src/Views/Dashboards/Dashboard.vue',
            ),
            1 =>
            array (
                'id' => 13,
                'menu_id' => 2,
                'url' => '',
                'target' => '_self',
                'icon_class' => 'feather feather-home',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 1,
                'route' => 'dashboard',
                'parameters' => 'null',
                'importedComponent' => '/src/Views/Admin/Dashboard.vue',
            ),
            2 =>
            array (
                'id' => 14,
                'menu_id' => 2,
                'url' => '',
                'target' => '_self',
                'icon_class' => 'feather feather-home',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 2,
                'route' => 'menuBuilder',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Admin/MenuBuilder.vue',
            ),
            3 =>
            array (
                'id' => 79,
                'menu_id' => 2,
                'url' => '',
                'target' => '_self',
                'icon_class' => 'feather feather-lock',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 3,
                'route' => 'roles',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Admin/Roles.vue',
            ),
            4 =>
            array (
                'id' => 91,
                'menu_id' => 2,
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa fa-user-o',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 4,
                'route' => 'users',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Admin/Users.vue',
            ),
            5 =>
            array (
                'id' => 94,
                'menu_id' => 2,
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fe fe-clipboard',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 6,
                'route' => 'log',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Admin/Log.vue',
            ),
            6 =>
            array (
                'id' => 95,
                'menu_id' => 2,
                'url' => '',
                'target' => '_self',
                'icon_class' => 'feather feather-sliders',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 5,
                'route' => 'settings',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Admin/Settings.vue',
            ),
            7 =>
            array (
                'id' => 100,
                'menu_id' => 1,
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa fa-car',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 3,
                'route' => '',
                'parameters' => NULL,
                'importedComponent' => NULL,
            ),
            8 =>
            array (
                'id' => 105,
                'menu_id' => 1,
                'url' => '',
                'target' => '_self',
                'icon_class' => NULL,
                'color' => NULL,
                'parent_id' => 100,
                'order' => 2,
                'route' => 'brands-models',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Autoban/Brands.vue',
            ),
            9 =>
            array (
                'id' => 106,
                'menu_id' => 1,
                'url' => '',
                'target' => '_self',
                'icon_class' => NULL,
                'color' => NULL,
                'parent_id' => 100,
                'order' => 3,
                'route' => 'types',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Autoban/Types.vue',
            ),
            10 =>
            array (
                'id' => 107,
                'menu_id' => 1,
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => 100,
                'order' => 4,
                'route' => 'years',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Autoban/Years.vue',
            ),
            11 =>
            array (
                'id' => 110,
                'menu_id' => 1,
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => 100,
                'order' => 1,
                'route' => 'autoban',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Autoban/Autoban.vue',
            ),
            12 =>
            array (
                'id' => 111,
                'menu_id' => 1,
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa fa-car',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 1,
                'route' => 'pricelist',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Pricelist/Pricelist.vue',
            ),
            13 =>
            array (
                'id' => 112,
                'menu_id' => 1,
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => 100,
                'order' => 5,
                'route' => 'prices',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Autoban/Prices.vue',
            ),
            14 =>
            array (
                'id' => 113,
                'menu_id' => 1,
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa fa-car',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 4,
                'route' => '',
                'parameters' => NULL,
                'importedComponent' => NULL,
            ),
            15 =>
            array (
                'id' => 116,
                'menu_id' => 1,
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => 113,
                'order' => 1,
                'route' => 'options',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Options/Options.vue',
            ),
            16 =>
            array (
                'id' => 117,
                'menu_id' => 1,
                'url' => '',
                'target' => '_self',
                'icon_class' => '',
                'color' => NULL,
                'parent_id' => 113,
                'order' => 2,
                'route' => 'autobanOptions',
                'parameters' => NULL,
                'importedComponent' => '/src/Views/Options/AutobanOption.vue',
            ),
        ));


    }
}
