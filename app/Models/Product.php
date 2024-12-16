<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'quantity',
        'image',
        'status'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orderProductes()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public static function createNewProduct($validatedData)
    {
        $imagePath = $validatedData['image']->store('images', 'public');
        $imageName = basename($imagePath);

        return self::create([
            'category_id' => $validatedData['category_id'],
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
            'image' => $imageName,
            'status' => $validatedData['status'],
        ]);
    }

    public function updateProduct($validatedData)
    {
        if (isset($validatedData['image'])) {
            $imagePath = $validatedData['image']->store('images', 'public');
            $validatedData['image'] = basename($imagePath);
        }
        $validatedData['slug'] = Str::slug($validatedData['name']);
        return $this->update($validatedData);
    }

    public function decrementQuantity($amount)
    {
        if ($this->quantity >= $amount) {
            $this->quantity -= $amount;
            $this->save();
            return true;
        }
        return false;
    }
}
