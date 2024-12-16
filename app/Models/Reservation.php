<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'table_id',
        'name',
        'phone',
        'note',
        'seats',
        'status',
        'reservation_date',
        'reservation_time',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public static function createReservation($data)
    {
        return self::create($data);
    }

    public function productes()
    {
        return $this->belongsToMany(Product::class, 'reservation_product')->withPivot('quantity');
    }

    public function calculateTotalPrice()
    {
        $total = 0;
        foreach ($this->productes as $product) {
            $total += $product->pivot->quantity * $product->price;
        }
        return $total;
    }

    public static function createNewBookTable($validatedData)
    {
        $order = self::create([
            'user_id' => $validatedData['user_id'],
            'name' => $validatedData['name'],
            'table_id' => $validatedData['table_id'],
            'note' => $validatedData['note'],
            'status' => $validatedData['status'],
            'reservation_date' => $validatedData['reservation_date'],
            'reservation_time' => $validatedData['reservation_time'],
            'seats' => $validatedData['seats'],
        ]);

        if (isset($validatedData['product_id'])) {
            $productIds = $validatedData['product_id'];
            $quantities = $validatedData['quantities'] ?? [];

            foreach ($productIds as $productId) {
                if (isset($quantities[$productId]) && $quantities[$productId] > 0) {
                    $product = Product::find($productId);

                    // Check if the quantity ordered exceeds available quantity
                    if ($product->quantity < $quantities[$productId]) {
                        throw new \Exception("Số lượng món \"{$product->name}\" đã vượt quá số lượng có sẵn.");
                    }

                    // Deduct the quantity from available stock
                    $product->quantity -= $quantities[$productId];
                    $product->save();

                    $order->productes()->attach($productId, ['quantity' => $quantities[$productId]]);
                }
            }
        }

        return $order;
    }

    public function updateNewBookTable($validatedData)
    {
        $this->update([
            'user_id' => $validatedData['user_id'],
            'name' => $validatedData['name'],
            'table_id' => $validatedData['table_id'],
            'note' => $validatedData['note'],
            'status' => $validatedData['status'],
            'reservation_date' => $validatedData['order_date'],
            'reservation_time' => $validatedData['order_time'],
            'seats' => $validatedData['seats'],
        ]);

        if (isset($validatedData['product_id'])) {
            $productIds = $validatedData['product_id'];
            $quantities = $validatedData['quantities'] ?? [];

            foreach ($productIds as $productId) {
                $newQuantity = $quantities[$productId];
                $existingproduct = $this->productes->find($productId);

                if ($existingproduct) {
                    $originalQuantity = $existingproduct->pivot->quantity;

                    // Tính toán sự khác biệt giữa số lượng mới và số lượng ban đầu
                    $difference = $newQuantity - $originalQuantity;

                    // Cập nhật số lượng món ăn có sẵn
                    $product = Product::find($productId);
                    $product->quantity -= $difference;
                    if ($product->quantity < 0) {
                        throw new \Exception("Số lượng món \"{$product->name}\" đã vượt quá số lượng có sẵn.");
                    }
                    $product->save();

                    // Cập nhật số lượng trong pivot table
                    $this->productes()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
                } else {
                    // Thêm món mới nếu nó chưa tồn tại trong đơn đặt hàng
                    $product = Product::find($productId);
                    if ($product->quantity < $newQuantity) {
                        throw new \Exception("Số lượng món \"{$product->name}\" đã vượt quá số lượng có sẵn.");
                    }
                    $product->quantity -= $newQuantity;
                    $product->save();

                    $this->productes()->attach($productId, ['quantity' => $newQuantity]);
                }
            }
        }

        return $this;
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($reservation) {
            $reservation->productes()->detach();
        });
    }

    public static function createNewBookTableClient($validatedData)
    {
        $order = self::create([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'table_id' => $validatedData['table_id'],
            'note' => $validatedData['note'],
            'reservation_date' => $validatedData['reservation_date'],
            'reservation_time' => $validatedData['reservation_time'],
        ]);

        if (isset($validatedData['product_id'])) {
            $productIds = $validatedData['product_id'];
            $quantities = $validatedData['quantities'] ?? [];

            foreach ($productIds as $productId) {
                if (isset($quantities[$productId]) && $quantities[$productId] > 0) {
                    $product = Product::find($productId);

                    // Check if the quantity ordered exceeds available quantity
                    if ($product->quantity < $quantities[$productId]) {
                        throw new \Exception("Số lượng món \"{$product->name}\" đã vượt quá số lượng có sẵn.");
                    }

                    // Deduct the quantity from available stock
                    $product->quantity -= $quantities[$productId];
                    $product->save();

                    $order->productes()->attach($productId, ['quantity' => $quantities[$productId]]);
                }
            }
        }

        return $order;
    }
}
