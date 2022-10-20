<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 31,
                'key' => 'browse_admin_roles',
                'table_name' => 'admin_roles',
            ),
            1 => 
            array (
                'id' => 32,
                'key' => 'read_admin_roles',
                'table_name' => 'admin_roles',
            ),
            2 => 
            array (
                'id' => 33,
                'key' => 'edit_admin_roles',
                'table_name' => 'admin_roles',
            ),
            3 => 
            array (
                'id' => 34,
                'key' => 'add_admin_roles',
                'table_name' => 'admin_roles',
            ),
            4 => 
            array (
                'id' => 35,
                'key' => 'delete_admin_roles',
                'table_name' => 'admin_roles',
            ),
            5 => 
            array (
                'id' => 36,
                'key' => 'browse_admin_menubuilder',
                'table_name' => 'admin_menubuilder',
            ),
            6 => 
            array (
                'id' => 37,
                'key' => 'read_admin_menubuilder',
                'table_name' => 'admin_menubuilder',
            ),
            7 => 
            array (
                'id' => 38,
                'key' => 'edit_admin_menubuilder',
                'table_name' => 'admin_menubuilder',
            ),
            8 => 
            array (
                'id' => 39,
                'key' => 'add_admin_menubuilder',
                'table_name' => 'admin_menubuilder',
            ),
            9 => 
            array (
                'id' => 40,
                'key' => 'delete_admin_menubuilder',
                'table_name' => 'admin_menubuilder',
            ),
            10 => 
            array (
                'id' => 41,
                'key' => 'browse_admin_dashboard',
                'table_name' => 'admin_dashboard',
            ),
            11 => 
            array (
                'id' => 42,
                'key' => 'read_admin_dashboard',
                'table_name' => 'admin_dashboard',
            ),
            12 => 
            array (
                'id' => 43,
                'key' => 'edit_admin_dashboard',
                'table_name' => 'admin_dashboard',
            ),
            13 => 
            array (
                'id' => 44,
                'key' => 'add_admin_dashboard',
                'table_name' => 'admin_dashboard',
            ),
            14 => 
            array (
                'id' => 45,
                'key' => 'delete_admin_dashboard',
                'table_name' => 'admin_dashboard',
            ),
            15 => 
            array (
                'id' => 46,
                'key' => 'browse_dashboards_dashboard',
                'table_name' => 'dashboards_dashboard',
            ),
            16 => 
            array (
                'id' => 47,
                'key' => 'read_dashboards_dashboard',
                'table_name' => 'dashboards_dashboard',
            ),
            17 => 
            array (
                'id' => 48,
                'key' => 'edit_dashboards_dashboard',
                'table_name' => 'dashboards_dashboard',
            ),
            18 => 
            array (
                'id' => 49,
                'key' => 'add_dashboards_dashboard',
                'table_name' => 'dashboards_dashboard',
            ),
            19 => 
            array (
                'id' => 50,
                'key' => 'delete_dashboards_dashboard',
                'table_name' => 'dashboards_dashboard',
            ),
            20 => 
            array (
                'id' => 61,
                'key' => 'browse_admin_users',
                'table_name' => 'admin_users',
            ),
            21 => 
            array (
                'id' => 62,
                'key' => 'read_admin_users',
                'table_name' => 'admin_users',
            ),
            22 => 
            array (
                'id' => 63,
                'key' => 'edit_admin_users',
                'table_name' => 'admin_users',
            ),
            23 => 
            array (
                'id' => 64,
                'key' => 'add_admin_users',
                'table_name' => 'admin_users',
            ),
            24 => 
            array (
                'id' => 65,
                'key' => 'delete_admin_users',
                'table_name' => 'admin_users',
            ),
            25 => 
            array (
                'id' => 71,
                'key' => 'browse_admin_log',
                'table_name' => 'admin_log',
            ),
            26 => 
            array (
                'id' => 72,
                'key' => 'read_admin_log',
                'table_name' => 'admin_log',
            ),
            27 => 
            array (
                'id' => 73,
                'key' => 'edit_admin_log',
                'table_name' => 'admin_log',
            ),
            28 => 
            array (
                'id' => 74,
                'key' => 'add_admin_log',
                'table_name' => 'admin_log',
            ),
            29 => 
            array (
                'id' => 75,
                'key' => 'delete_admin_log',
                'table_name' => 'admin_log',
            ),
            30 => 
            array (
                'id' => 76,
                'key' => 'browse_admin_settings',
                'table_name' => 'admin_settings',
            ),
            31 => 
            array (
                'id' => 77,
                'key' => 'read_admin_settings',
                'table_name' => 'admin_settings',
            ),
            32 => 
            array (
                'id' => 78,
                'key' => 'edit_admin_settings',
                'table_name' => 'admin_settings',
            ),
            33 => 
            array (
                'id' => 79,
                'key' => 'add_admin_settings',
                'table_name' => 'admin_settings',
            ),
            34 => 
            array (
                'id' => 80,
                'key' => 'delete_admin_settings',
                'table_name' => 'admin_settings',
            ),
            35 => 
            array (
                'id' => 81,
                'key' => 'browse_autoban_brands',
                'table_name' => 'autoban_brands',
            ),
            36 => 
            array (
                'id' => 82,
                'key' => 'read_autoban_brands',
                'table_name' => 'autoban_brands',
            ),
            37 => 
            array (
                'id' => 83,
                'key' => 'edit_autoban_brands',
                'table_name' => 'autoban_brands',
            ),
            38 => 
            array (
                'id' => 84,
                'key' => 'add_autoban_brands',
                'table_name' => 'autoban_brands',
            ),
            39 => 
            array (
                'id' => 85,
                'key' => 'delete_autoban_brands',
                'table_name' => 'autoban_brands',
            ),
            40 => 
            array (
                'id' => 86,
                'key' => 'browse_autoban_types',
                'table_name' => 'autoban_types',
            ),
            41 => 
            array (
                'id' => 87,
                'key' => 'read_autoban_types',
                'table_name' => 'autoban_types',
            ),
            42 => 
            array (
                'id' => 88,
                'key' => 'edit_autoban_types',
                'table_name' => 'autoban_types',
            ),
            43 => 
            array (
                'id' => 89,
                'key' => 'add_autoban_types',
                'table_name' => 'autoban_types',
            ),
            44 => 
            array (
                'id' => 90,
                'key' => 'delete_autoban_types',
                'table_name' => 'autoban_types',
            ),
            45 => 
            array (
                'id' => 91,
                'key' => 'browse_autoban_years',
                'table_name' => 'autoban_years',
            ),
            46 => 
            array (
                'id' => 92,
                'key' => 'read_autoban_years',
                'table_name' => 'autoban_years',
            ),
            47 => 
            array (
                'id' => 93,
                'key' => 'edit_autoban_years',
                'table_name' => 'autoban_years',
            ),
            48 => 
            array (
                'id' => 94,
                'key' => 'add_autoban_years',
                'table_name' => 'autoban_years',
            ),
            49 => 
            array (
                'id' => 95,
                'key' => 'delete_autoban_years',
                'table_name' => 'autoban_years',
            ),
            50 => 
            array (
                'id' => 101,
                'key' => 'browse_autoban_autoban',
                'table_name' => 'autoban_autoban',
            ),
            51 => 
            array (
                'id' => 102,
                'key' => 'read_autoban_autoban',
                'table_name' => 'autoban_autoban',
            ),
            52 => 
            array (
                'id' => 103,
                'key' => 'edit_autoban_autoban',
                'table_name' => 'autoban_autoban',
            ),
            53 => 
            array (
                'id' => 104,
                'key' => 'add_autoban_autoban',
                'table_name' => 'autoban_autoban',
            ),
            54 => 
            array (
                'id' => 105,
                'key' => 'delete_autoban_autoban',
                'table_name' => 'autoban_autoban',
            ),
            55 => 
            array (
                'id' => 106,
                'key' => 'browse_pricelist_pricelist',
                'table_name' => 'pricelist_pricelist',
            ),
            56 => 
            array (
                'id' => 107,
                'key' => 'read_pricelist_pricelist',
                'table_name' => 'pricelist_pricelist',
            ),
            57 => 
            array (
                'id' => 108,
                'key' => 'edit_pricelist_pricelist',
                'table_name' => 'pricelist_pricelist',
            ),
            58 => 
            array (
                'id' => 109,
                'key' => 'add_pricelist_pricelist',
                'table_name' => 'pricelist_pricelist',
            ),
            59 => 
            array (
                'id' => 110,
                'key' => 'delete_pricelist_pricelist',
                'table_name' => 'pricelist_pricelist',
            ),
            60 => 
            array (
                'id' => 111,
                'key' => 'browse_autoban_prices',
                'table_name' => 'autoban_prices',
            ),
            61 => 
            array (
                'id' => 112,
                'key' => 'read_autoban_prices',
                'table_name' => 'autoban_prices',
            ),
            62 => 
            array (
                'id' => 113,
                'key' => 'edit_autoban_prices',
                'table_name' => 'autoban_prices',
            ),
            63 => 
            array (
                'id' => 114,
                'key' => 'add_autoban_prices',
                'table_name' => 'autoban_prices',
            ),
            64 => 
            array (
                'id' => 115,
                'key' => 'delete_autoban_prices',
                'table_name' => 'autoban_prices',
            ),
            65 => 
            array (
                'id' => 116,
                'key' => 'browse_options_classes',
                'table_name' => 'options_classes',
            ),
            66 => 
            array (
                'id' => 117,
                'key' => 'read_options_classes',
                'table_name' => 'options_classes',
            ),
            67 => 
            array (
                'id' => 118,
                'key' => 'edit_options_classes',
                'table_name' => 'options_classes',
            ),
            68 => 
            array (
                'id' => 119,
                'key' => 'add_options_classes',
                'table_name' => 'options_classes',
            ),
            69 => 
            array (
                'id' => 120,
                'key' => 'delete_options_classes',
                'table_name' => 'options_classes',
            ),
            70 => 
            array (
                'id' => 121,
                'key' => 'browse_options_categories',
                'table_name' => 'options_categories',
            ),
            71 => 
            array (
                'id' => 122,
                'key' => 'read_options_categories',
                'table_name' => 'options_categories',
            ),
            72 => 
            array (
                'id' => 123,
                'key' => 'edit_options_categories',
                'table_name' => 'options_categories',
            ),
            73 => 
            array (
                'id' => 124,
                'key' => 'add_options_categories',
                'table_name' => 'options_categories',
            ),
            74 => 
            array (
                'id' => 125,
                'key' => 'delete_options_categories',
                'table_name' => 'options_categories',
            ),
            75 => 
            array (
                'id' => 126,
                'key' => 'browse_options_options',
                'table_name' => 'options_options',
            ),
            76 => 
            array (
                'id' => 127,
                'key' => 'read_options_options',
                'table_name' => 'options_options',
            ),
            77 => 
            array (
                'id' => 128,
                'key' => 'edit_options_options',
                'table_name' => 'options_options',
            ),
            78 => 
            array (
                'id' => 129,
                'key' => 'add_options_options',
                'table_name' => 'options_options',
            ),
            79 => 
            array (
                'id' => 130,
                'key' => 'delete_options_options',
                'table_name' => 'options_options',
            ),
            80 => 
            array (
                'id' => 131,
                'key' => 'delete_options_sub_classes',
                'table_name' => 'options_sub_classes',
            ),
            81 => 
            array (
                'id' => 132,
                'key' => 'add_options_sub_classes',
                'table_name' => 'options_sub_classes',
            ),
            82 => 
            array (
                'id' => 133,
                'key' => 'edit_options_sub_classes',
                'table_name' => 'options_sub_classes',
            ),
            83 => 
            array (
                'id' => 134,
                'key' => 'read_options_sub_classes',
                'table_name' => 'options_sub_classes',
            ),
            84 => 
            array (
                'id' => 135,
                'key' => 'browse_options_sub_classes',
                'table_name' => 'options_sub_classes',
            ),
            85 => 
            array (
                'id' => 136,
                'key' => 'browse_options_autobanoption',
                'table_name' => 'options_autobanoption',
            ),
            86 => 
            array (
                'id' => 137,
                'key' => 'read_options_autobanoption',
                'table_name' => 'options_autobanoption',
            ),
            87 => 
            array (
                'id' => 138,
                'key' => 'edit_options_autobanoption',
                'table_name' => 'options_autobanoption',
            ),
            88 => 
            array (
                'id' => 139,
                'key' => 'add_options_autobanoption',
                'table_name' => 'options_autobanoption',
            ),
            89 => 
            array (
                'id' => 140,
                'key' => 'delete_options_autobanoption',
                'table_name' => 'options_autobanoption',
            ),
        ));
        
        
    }
}