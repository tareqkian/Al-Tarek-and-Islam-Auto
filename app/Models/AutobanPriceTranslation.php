<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobanPriceTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ["official", "commercial", "sale"];
}
