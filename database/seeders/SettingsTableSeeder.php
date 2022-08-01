<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::firstOrCreate([
          "key" => "cms.title",
          "display_name" => "CMS Title",
          "value" => "CMS Title",
          "type" => "text",
          "group" => "CMS",
        ]);
        Setting::firstOrCreate([
          "key" => "cms.logo",
          "display_name" => "CMS Logo",
          "value" => Null,
          "type" => "image",
          "group" => "CMS",
        ]);
        Setting::firstOrCreate([
          "key" => "cms.mini.logo",
          "display_name" => "CMS Mini Logo",
          "value" => Null,
          "type" => "image",
          "group" => "CMS",
        ]);
        Setting::firstOrCreate([
          "key" => "cms.footer",
          "display_name" => "CMS Footer",
          "value" => "Copyright © 2022 All rights reserved.",
          "type" => "text",
          "group" => "CMS",
        ]);
        Setting::firstOrCreate([
          "key" => "admin.title",
          "display_name" => "Admin Title",
          "value" => "CMS Title",
          "type" => "text",
          "group" => "CMS",
        ]);
        Setting::firstOrCreate([
          "key" => "admin.logo",
          "display_name" => "Admin Logo",
          "value" => Null,
          "type" => "image",
          "group" => "CMS",
        ]);
        Setting::firstOrCreate([
          "key" => "admin.mini.logo",
          "display_name" => "Admin Mini Logo",
          "value" => Null,
          "type" => "image",
          "group" => "CMS",
        ]);
        Setting::firstOrCreate([
          "key" => "admin.footer",
          "display_name" => "Admin Footer",
          "value" => "Copyright © 2022 All rights reserved.",
          "type" => "text",
          "group" => "CMS",
        ]);
    }
}
