<?php

namespace App\Http\Controllers\Client\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('dish')->get();
        $users = auth()->user();
        $tables = Table::all();

        $total = session()->get('discounted_total', $cartItems->sum('total_price'));
        $discount = session()->get('discount_value', 0);

        return view('clients.cart.index', compact('cartItems', 'total', 'discount', 'users', 'tables'));
    }

    public function applyDiscountCode(Request $request)
    {
        $result = Cart::applyDiscount(auth()->id(), $request->code);

        if (isset($result['error'])) {
            flash()->error($result['error']);
        } else {
            session(['discounted_total' => $result['total']]);
            session(['discount_value' => $result['discount']]);
            flash()->success($result['success']);
        }

        return redirect()->route('cart');
    }

    public function addToCart(Request $request)
    {
        $result = Cart::addToCart(auth()->id(), $request->product_id, $request->quantity);

        if (isset($result['error'])) {
            flash()->error($result['error']);
        } else {
            flash()->success($result['success']);
        }

        return redirect()->route('cart');
    }

    public function remove($id)
    {
        Cart::findOrFail($id)->delete();

        flash()->success('Sản phẩm đã được xóa khỏi giỏ hàng!');
        return redirect()->route('cart');
    }

    public function clear()
    {
        $result = Cart::clearCart(auth()->id());

        flash()->success($result['success']);
        return redirect()->route('home');
    }

    public function update(Request $request)
    {
        $result = Cart::updateCart(auth()->id(), $request->input('cart', []));

        if (isset($result['error'])) {
            flash()->error($result['error']);
        } else {
            session(['discounted_total' => $result['total']]);
            session(['discount_value' => $result['discount']]);
            flash()->success($result['success']);
        }

        return redirect()->route('cart');
    }

    public function checkTableAvailability(Request $request)
    {
        $date = $request->input('reservation_date');
        $time = $request->input('reservation_time');

        // Tính toán khoảng thời gian bắt đầu và kết thúc
        $startTime = date('H:i:s', strtotime($time . ' -3 hours'));
        $endTime = date('H:i:s', strtotime($time . ' +3 hours'));

        $unavailableTables = Reservation::where('reservation_date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    // Kiểm tra thời gian đặt bàn nằm trong khoảng thời gian từ startTime đến endTime
                    $q->whereBetween('reservation_time', [$startTime, $endTime]);
                })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        // Kiểm tra thời gian kết thúc của đặt bàn nằm trong khoảng thời gian từ startTime đến endTime
                        $q->where(DB::raw("DATE_ADD(reservation_time, INTERVAL 3 HOUR)"), '>=', $startTime)
                            ->where('reservation_time', '<=', $endTime);
                    });
            })
            ->pluck('table_id')
            ->toArray();

        return response()->json(['unavailableTables' => $unavailableTables]);
    }
}
