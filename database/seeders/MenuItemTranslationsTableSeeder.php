<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuItemTranslationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_item_translations')->delete();
        
        \DB::table('menu_item_translations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'menu_item_id' => 13,
                'locale' => 'en',
                'title' => 'Admin Dashboard',
            ),
            1 => 
            array (
                'id' => 2,
                'menu_item_id' => 14,
                'locale' => 'en',
                'title' => 'Menu Builder',
            ),
            2 => 
            array (
                'id' => 3,
                'menu_item_id' => 79,
                'locale' => 'en',
                'title' => 'Roles',
            ),
            3 => 
            array (
                'id' => 4,
                'menu_item_id' => 91,
                'locale' => 'en',
                'title' => 'Users',
            ),
            4 => 
            array (
                'id' => 5,
                'menu_item_id' => 95,
                'locale' => 'en',
                'title' => 'Settings',
            ),
            5 => 
            array (
                'id' => 6,
                'menu_item_id' => 94,
                'locale' => 'en',
                'title' => 'Log',
            ),
            6 => 
            array (
                'id' => 7,
                'menu_item_id' => 13,
                'locale' => 'ar',
                'title' => 'لوحة التحكم',
            ),
            7 => 
            array (
                'id' => 8,
                'menu_item_id' => 14,
                'locale' => 'ar',
                'title' => 'منشئ القائمة',
            ),
            8 => 
            array (
                'id' => 9,
                'menu_item_id' => 79,
                'locale' => 'ar',
                'title' => 'قواعد',
            ),
            9 => 
            array (
                'id' => 10,
                'menu_item_id' => 91,
                'locale' => 'ar',
                'title' => 'مستخدمين',
            ),
            10 => 
            array (
                'id' => 11,
                'menu_item_id' => 95,
                'locale' => 'ar',
                'title' => 'إعدادات',
            ),
            11 => 
            array (
                'id' => 12,
                'menu_item_id' => 94,
                'locale' => 'ar',
                'title' => 'سجل',
            ),
            12 => 
            array (
                'id' => 13,
                'menu_item_id' => 11,
                'locale' => 'ar',
                'title' => 'لوحة تحكم',
            ),
            13 => 
            array (
                'id' => 14,
                'menu_item_id' => 11,
                'locale' => 'en',
                'title' => 'Dashboard',
            ),
            14 => 
            array (
                'id' => 17,
                'menu_item_id' => 100,
                'locale' => 'ar',
                'title' => 'سيارات',
            ),
            15 => 
            array (
                'id' => 18,
                'menu_item_id' => 100,
                'locale' => 'en',
                'title' => 'Autobans',
            ),
            16 => 
            array (
                'id' => 24,
                'menu_item_id' => 105,
                'locale' => 'ar',
                'title' => 'ماركات وموديلات السيارات',
            ),
            17 => 
            array (
                'id' => 25,
                'menu_item_id' => 105,
                'locale' => 'en',
                'title' => 'Autoban Brands Models',
            ),
            18 => 
            array (
                'id' => 26,
                'menu_item_id' => 106,
                'locale' => 'ar',
                'title' => 'انواع السيارات',
            ),
            19 => 
            array (
                'id' => 27,
                'menu_item_id' => 106,
                'locale' => 'en',
                'title' => 'Autoban Types',
            ),
            20 => 
            array (
                'id' => 28,
                'menu_item_id' => 107,
                'locale' => 'en',
                'title' => 'Autoban Years',
            ),
            21 => 
            array (
                'id' => 29,
                'menu_item_id' => 107,
                'locale' => 'ar',
                'title' => 'سنوات السيارات',
            ),
            22 => 
            array (
                'id' => 34,
                'menu_item_id' => 110,
                'locale' => 'en',
                'title' => 'Autoban',
            ),
            23 => 
            array (
                'id' => 35,
                'menu_item_id' => 110,
                'locale' => 'ar',
                'title' => 'السيارات',
            ),
            24 => 
            array (
                'id' => 36,
                'menu_item_id' => 111,
                'locale' => 'en',
                'title' => 'Pricelist',
            ),
            25 => 
            array (
                'id' => 37,
                'menu_item_id' => 111,
                'locale' => 'ar',
                'title' => 'قائمة الأسعار',
            ),
            26 => 
            array (
                'id' => 38,
                'menu_item_id' => 112,
                'locale' => 'en',
                'title' => 'Autoban Prices',
            ),
            27 => 
            array (
                'id' => 39,
                'menu_item_id' => 112,
                'locale' => 'ar',
                'title' => 'اسعار السيارات',
            ),
            28 => 
            array (
                'id' => 40,
                'menu_item_id' => 113,
                'locale' => 'en',
                'title' => 'Options',
            ),
            29 => 
            array (
                'id' => 41,
                'menu_item_id' => 113,
                'locale' => 'ar',
                'title' => 'كماليات',
            ),
            30 => 
            array (
                'id' => 46,
                'menu_item_id' => 116,
                'locale' => 'en',
                'title' => 'Options',
            ),
            31 => 
            array (
                'id' => 47,
                'menu_item_id' => 116,
                'locale' => 'ar',
                'title' => 'كماليات',
            ),
            32 => 
            array (
                'id' => 48,
                'menu_item_id' => 117,
                'locale' => 'en',
                'title' => 'Autoban Options',
            ),
            33 => 
            array (
                'id' => 49,
                'menu_item_id' => 117,
                'locale' => 'ar',
                'title' => 'كماليات سيارات',
            ),
        ));
        
        
    }
}