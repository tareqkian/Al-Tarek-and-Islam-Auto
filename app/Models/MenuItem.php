<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon_class',
        'menu_id',
        'order',
        'route',
        'title',
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

    public function highestOrderMenuItem($data)
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
