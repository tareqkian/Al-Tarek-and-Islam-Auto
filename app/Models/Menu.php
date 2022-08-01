<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MenuItem;

class Menu extends Model
{
    use HasFactory;
    public $fillable = ['name', 'importedComponent'];

    public function items()
    {
        return $this->hasMany(MenuItem::class,'menu_id')->orderBy('order');
    }

    public function parentItems()
    {
        return $this->hasMany(MenuItem::class,'menu_id')
            ->whereNull("parent_id")
            ->orderBy('order');
    }
}
