<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'product_id',
        'voucher_id',
        'quantity',
        'total_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public static function getTotalPrice($userId)
    {
        $carts = self::where('user_id', $userId)->with(['product', 'voucher'])->get();

        $totalPrice = $carts->sum(function ($cart) {
            return $cart->quantity * $cart->product->price;
        });

        $discount = $carts->whereNotNull('voucher')->sum(function ($cart) {
            return $cart->voucher->discount ?? 0;
        });

        return [
            'totalPrice' => $totalPrice,
            'totalPriceAfterDiscount' => $totalPrice - $discount
        ];
    }

    public static function addToCart($userId, $productId, $quantity)
    {
        $product = Product::find($productId);
        if (!$product) {
            return ['error' => 'Sản phẩm không tồn tại.'];
        }
        if ($product->quantity <= 0) {
            return ['error' => 'Đã hết món vui lòng quay lại sau.'];
        }

        if ($product->quantity < $quantity) {
            return ['error' => 'Số lượng sản phẩm không đủ. Vui lòng chọn số lượng khác.'];
        }

        $cartItem = self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->total_price = $cartItem->quantity * $product->price;
            $cartItem->save();
        } else {
            self::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'total_price' => $product->price * $quantity,
            ]);
        }

        return ['success' => 'Sản phẩm đã được thêm vào giỏ hàng!'];
    }

    public static function applyDiscount($userId, $code)
    {
        $voucher = Voucher::where('code', $code)
            ->where('status', 'active')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        if (!$voucher || $voucher->number_use <= 0) {
            return ['error' => 'Mã giảm giá không hợp lệ hoặc đã hết số lần sử dụng.'];
        }

        $cartItems = self::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return ['error' => 'Giỏ hàng của bạn đang trống. Hãy chọn món trước khi áp dụng mã giảm giá.'];
        }

        $total = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });

        $total = max(0, $total - $voucher->discount);

        foreach ($cartItems as $cartItem) {
            $cartItem->voucher_id = $voucher->id;
            $cartItem->save();
        }

        return [
            'success' => 'Mã giảm giá đã được áp dụng!',
            'total' => $total,
            'discount' => $voucher->discount
        ];
    }

    public static function updateCart($userId, $cartData)
    {
        $total = 0;
        $voucherDiscount = 0;

        foreach ($cartData as $productId => $quantity) {
            $cartItem = self::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($quantity <= 0) {
                return ['error' => 'Số lượng món ăn phải lớn hơn 0.'];
            }

            if ($cartItem) {
                $product = $cartItem->product;
                if ($quantity > $product->quantity) {
                    return ['error' => 'Số lượng món ăn vượt quá số lượng có sẵn.'];
                }

                $cartItem->quantity = $quantity;
                $cartItem->total_price = $quantity * $product->price;
                $cartItem->save();

                $total += $cartItem->total_price;
            }
        }

        $cartItemWithvoucher = self::where('user_id', $userId)
            ->whereNotNull('voucher_id')
            ->first();

        if ($cartItemWithvoucher) {
            $voucher = $cartItemWithvoucher->voucher;

            if ($voucher) {
                $voucherDiscount = $voucher->discount;
                $total = max(0, $total - $voucherDiscount);
            }
        }

        return [
            'success' => 'Giỏ hàng đã được cập nhật!',
            'total' => $total,
            'discount' => $voucherDiscount
        ];
    }

    public static function clearCart($userId)
    {
        self::where('user_id', $userId)->delete();
        return ['success' => 'Giỏ hàng đã được làm sạch!'];
    }
}
