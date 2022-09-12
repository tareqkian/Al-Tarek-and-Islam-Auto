<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobanBrandTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ["brand_title"];
}
