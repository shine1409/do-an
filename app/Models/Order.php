<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'user_id',
        'table_id',
        'name',
        'note',
        'code_order',
        'status',
        'order_date',
        'order_time'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productes(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity')->withTimestamps();
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }


    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function calculateTotalPrice()
    {
        $total = 0;
        foreach ($this->productes as $product) {
            $total += $product->pivot->quantity * $product->price;
        }
        return $total;
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function order_product()
    {
        return $this->hasMany(Orderproduct::class);
    }
}
