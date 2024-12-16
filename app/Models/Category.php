<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'image'
    ];

    public function productes()
    {
        return $this->hasMany(Product::class);
    }

    public static function getAllCategories()
    {
        return self::all();
    }

}