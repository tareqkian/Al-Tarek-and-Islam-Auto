<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable AS TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;

class MenuItem extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $translatedAttributes = ['title'];
    protected $fillable = [
        'icon_class',
        'menu_id',
        'order',
        'route',
        'url',
        'importedComponent'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class,'menu_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class,'parent_id')
        ->with("children")
        ->orderBy('order');
    }

    static function highestOrderMenuItem($data)
    {
        $order = 1;
        $item = MenuItem::where("menu_id",'=',$data['menu_id'])
            ->orderBy('order','DESC')
            ->first();

        if ( !is_null($item) ) {
            $order = intval($item->order) + 1;
        }
        return $order;
    }
}
