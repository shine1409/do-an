<?php

namespace App\Http\Controllers\Client\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\CreateCheckoutRequest;
use App\Models\Cart;
use App\Models\Dish;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Mail\PaymentSuccessMail;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $lastReservationId = session('last_reservation_id', null);

        // Get reservations and last reservation
        $reservations = Reservation::where('user_id', $userId)->with('table')->get();
        $lastReservation = $reservations->firstWhere('id', $lastReservationId);
        $lastReservationTable = $lastReservation ? $lastReservation->table : null;
        session()->put('lastReservation', $lastReservation);

        // Get carts and calculate totals
        $carts = Cart::where('user_id', $userId)->with(['dish', 'promotion'])->get();
        $totalPrices = Cart::getTotalPrice($userId);

        return view('clients.checkout.index', [
            'lastReservationTable' => $lastReservationTable,
            'lastReservation' => $lastReservation,
            'carts' => $carts,
            'totalPrice' => $totalPrices['totalPrice'],
            'totalPriceAfterDiscount' => $totalPrices['totalPriceAfterDiscount']
        ]);
    }

    public function checkout(CreateCheckoutRequest $request)
    {
        $users = auth()->user();

        $reservationData = [
            'user_id' => $users->id,
            'table_id' => $request->table_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'seats' => $request->seats,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'note' => $request->note,
        ];

        $reservation = Reservation::createReservation($reservationData);

        session(['last_reservation_id' => $reservation->id]);

        return redirect()->route('checkout');
    }

    public function processPayment(Request $request)
    {
        $user_id = auth()->id();
        $user = \App\Models\User::find($user_id);
        // Xác định phương thức thanh toán mà người dùng chọn
        $paymentMethod = $request->input('paymentMethod');

        // Lấy danh sách sản phẩm trong giỏ hàng của người dùng hiện tại
        $cartItems = Cart::where('user_id', $user_id)->get();
        // Tính toán tổng tiền
        $totalPrice = $cartItems->sum('total_price');
        // Nếu có khuyến mãi, áp dụng mã giảm giá
        $promotion = $cartItems->first()->promotion ?? null;;


        if ($promotion) {
            // Trừ số lần sử dụng của mã giảm giá
            if ($promotion->number_use > 0) {
                $totalPrice -= $promotion->discount;

                // Cập nhật số lần sử dụng của mã giảm giá
                $promotion->number_use -= 1;
                $promotion->save();
            }
        }

        // Lấy thông tin lastReservation từ session
        $lastReservation = session()->get('lastReservation');
        $orderCode = 'DH-' . strtoupper(str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT));

        switch ($paymentMethod) {
            case 'restaurant':
                // Tạo đơn hàng mới
                $order = Order::create([
                    'user_id' => $user_id,
                    'table_id' => $lastReservation ? $lastReservation->table_id : null,
                    'name' => $lastReservation ? $lastReservation->name : auth()->user()->name,
                    'note' => $lastReservation ? $lastReservation->note : null,
                    'code_order' => $orderCode,
                    'status' => 'đang sử lý',
                    'order_date' => $lastReservation ? $lastReservation->reservation_date : null,
                    'order_time' => $lastReservation ? $lastReservation->reservation_time : null,
                ]);

                foreach ($cartItems as $cartItem) {
                    $order->dishes()->attach($cartItem->dish_id, ['quantity' => $cartItem->quantity]);
                    $dish = Dish::find($cartItem->dish_id);
                    if ($dish) {
                        $dish->quantity -= $cartItem->quantity;
                        $dish->save();
                    }
                }

                // Tạo bản ghi thanh toán
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'user_id' => $user_id,
                    'payment_date' => $lastReservation ? $lastReservation->reservation_date : now(),
                    'payment_method' => $paymentMethod,
                    'total_amount' => $totalPrice,
                ]);

                // Xóa giỏ hàng sau khi thanh toán thành công
                Cart::where('user_id', $user_id)->delete();
                // Gửi email thông báo thanh toán thành công
                Mail::to($user->email)->send(new PaymentSuccessMail($user, $order));
                // Chuyển hướng người dùng tới trang thành công hoặc trang đơn hàng
                flash()->success('Thanh toán thành công!');
                return redirect()->route('home');
                break;
            case 'vnpay':
                $vnp_TmnCode = "K352G5ON"; // Mã website tại VNPay
                $vnp_HashSecret = "F1GCU0VW1JEOZZNDZ80D3C6MHW8N7M8Y"; // Chuỗi bí mật
                $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; // URL thanh toán của VNPay
                $vnp_Returnurl = route('home'); // URL trả về khi thanh toán thành công
                $vnp_TxnRef = $orderCode; // Mã đơn hàng
                $vnp_OrderInfo = 'Thanh toán đơn hàng ' . $orderCode;
                $vnp_Amount = $totalPrice * 100; // VNPay yêu cầu giá trị phải nhân 100
                $vnp_Locale = 'vn'; // Ngôn ngữ
                $vnp_BankCode = ''; // Mã ngân hàng
                $vnp_IpAddr = request()->ip(); // Địa chỉ IP của người dùng
                $vnp_CreateDate = now()->format('YmdHis'); // Đảm bảo định dạng đúng

                // Tạo đơn hàng mới và lưu vào database
                $order = Order::create([
                    'user_id' => $user_id,
                    'table_id' => $lastReservation ? $lastReservation->table_id : null,
                    'name' => $lastReservation ? $lastReservation->name : auth()->user()->name,
                    'note' => $lastReservation ? $lastReservation->note : null,
                    'code_order' => $orderCode,
                    'status' => 'đã thanh toán',
                    'order_date' => $lastReservation ? $lastReservation->reservation_date : null,
                    'order_time' => $lastReservation ? $lastReservation->reservation_time : null,
                ]);

                foreach ($cartItems as $cartItem) {
                    $order->dishes()->attach($cartItem->dish_id, ['quantity' => $cartItem->quantity]);
                    $dish = Dish::find($cartItem->dish_id);
                    if ($dish) {
                        $dish->quantity -= $cartItem->quantity;
                        $dish->save();
                    }
                }

                // Tạo bản ghi thanh toán (chưa hoàn thành)
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'user_id' => $user_id,
                    'payment_date' => now(),
                    'payment_method' => $paymentMethod,
                    'total_amount' => $totalPrice,
                ]);

                // Xóa giỏ hàng sau khi thanh toán thành công
                Cart::where('user_id', $user_id)->delete();
                Mail::to($user->email)->send(new PaymentSuccessMail($user, $order));
                // Xây dựng URL thanh toán
                $vnp_Params = [
                    "vnp_Version" => "2.1.0",
                    "vnp_Command" => "pay",
                    "vnp_TmnCode" => $vnp_TmnCode,
                    "vnp_Amount" => $vnp_Amount,
                    "vnp_CurrCode" => "VND",
                    "vnp_TxnRef" => $vnp_TxnRef,
                    "vnp_OrderInfo" => $vnp_OrderInfo,
                    "vnp_OrderType" => "billpayment",
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_ReturnUrl" => $vnp_Returnurl,
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_CreateDate" => now()->format('YmdHis'),
                ];

                ksort($vnp_Params);
                $hashdata = http_build_query($vnp_Params, '', '&');
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

                $vnp_Url .= "?" . $hashdata . '&vnp_SecureHash=' . $vnpSecureHash;

                flash()->success('Thanh toán thành công!');
                return redirect($vnp_Url);
                break;
            case 'momo':
                $partnerCode = env('MOMO_PARTNER_CODE');
                $accessKey = env('MOMO_ACCESS_KEY');
                $secretKey = env('MOMO_SECRET_KEY');
                $endpoint = env('MOMO_ENDPOINT');

                $orderId = $orderCode;
                $orderInfo = 'Thanh toán đơn hàng';
                $amount = $totalPrice * 100; // MoMo yêu cầu số tiền phải nhân 100
                $returnUrl = route('momo.return'); // URL trả về sau khi thanh toán

                $requestId = Str::uuid()->toString();
                $requestData = [
                    'partnerCode' => $partnerCode,
                    'accessKey' => $accessKey,
                    'requestId' => $requestId,
                    'amount' => $amount,
                    'orderId' => $orderId,
                    'orderInfo' => $orderInfo,
                    'returnUrl' => $returnUrl,
                    'notifyUrl' => route('home'), // URL nhận thông báo từ MoMo
                    'requestType' => 'captureMoMoWallet',
                    'extraData' => ''
                ];

                // Tạo chữ ký
                $signature = hash_hmac('sha256', json_encode($requestData), $secretKey);
                $requestData['signature'] = $signature;

                // Gửi yêu cầu thanh toán
                $response = Http::post($endpoint, $requestData);
                $order = Order::create([
                    'user_id' => $user_id,
                    'table_id' => $lastReservation ? $lastReservation->table_id : null,
                    'name' => $lastReservation ? $lastReservation->name : auth()->user()->name,
                    'note' => $lastReservation ? $lastReservation->note : null,
                    'code_order' => $orderCode,
                    'status' => 'đang sử lý',
                    'order_date' => $lastReservation ? $lastReservation->reservation_date : null,
                    'order_time' => $lastReservation ? $lastReservation->reservation_time : null,
                ]);

                foreach ($cartItems as $cartItem) {
                    $order->dishes()->attach($cartItem->dish_id, ['quantity' => $cartItem->quantity]);
                    $dish = Dish::find($cartItem->dish_id);
                    if ($dish) {
                        $dish->quantity -= $cartItem->quantity;
                        $dish->save();
                    }
                }

                $payment = Payment::create([
                    'order_id' => $order->id,
                    'user_id' => $user_id,
                    'payment_date' => $lastReservation ? $lastReservation->reservation_date : now(),
                    'payment_method' => 'momo',
                    'total_amount' => $totalPrice,
                ]);

                $responseData = $response->json();
                if ($responseData['resultCode'] == '00') {
                    // Chuyển hướng người dùng tới trang thanh toán của MoMo
                    return redirect($responseData['payUrl']);
                } else {
                    flash()->error('Có lỗi xảy ra khi tạo yêu cầu thanh toán.');
                    return redirect()->back();
                }
                break;
            default:
                flash()->error('Vui lòng chọn phương thức thanh toán.');
                return redirect()->back();
        }

        // Xóa giỏ hàng sau khi thanh toán thành công
        Cart::where('user_id', auth()->id())->delete();
        Mail::to($user->email)->send(new PaymentSuccessMail($user, $order));
        // Chuyển hướng người dùng tới trang thành công hoặc trang đơn hàng
        flash()->success('Thanh toán thành công!');
        return redirect()->route('home');
    }

    public function momoReturn(Request $request)
    {
        $responseCode = $request->input('responseCode');
        $orderId = $request->input('orderId');
        $amount = $request->input('amount');
        $signature = $request->input('signature');

        $secretKey = env('MOMO_SECRET_KEY');
        $validSignature = hash_hmac('sha256', $orderId . $amount . $responseCode, $secretKey);

        if ($signature === $validSignature) {
            if ($responseCode == '00') {
                // Cập nhật trạng thái đơn hàng và thanh toán
                $order = Order::where('code_order', $orderId)->first();
                if ($order) {
                    $order->update([
                        'status' => 'Đã thanh toán'
                    ]);

                    $payment = Payment::where('order_id', $order->id)->first();
                    if ($payment) {
                        $payment->update([
                            'payment_status' => 'completed'
                        ]);
                    }

                    // Xóa giỏ hàng
                    Cart::where('user_id', $order->user_id)->delete();

                    flash()->success('Thanh toán thành công!');
                    return redirect()->route('home');
                }
            } else {
                flash()->error('Thanh toán không thành công.');
                return redirect()->route('home');
            }
        } else {
            flash()->error('Chữ ký không hợp lệ.');
            return redirect()->route('home');
        }
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "F1GCU0VW1JEOZZNDZ80D3C6MHW8N7M8Y";
        $inputData = $request->all();

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = http_build_query($inputData, '', '&');

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            // Xử lý trạng thái thanh toán
            if ($inputData['vnp_ResponseCode'] == '00') {
                // Cập nhật trạng thái thanh toán thành công
                $order = Order::where('code_order', $inputData['vnp_TxnRef'])->first();
                if ($order) {
                    $order->status = 'đã thanh toán';
                    $order->save();

                    $payment = $order->payments()->where('payment_method', 'vnpay')->first();
                    if ($payment) {
                        $payment->payment_status = 'success';
                        $payment->save();
                    }

                    // Xóa giỏ hàng sau khi thanh toán thành công
                    Cart::where('user_id', $order->user_id)->delete();

                    flash()->success('Thanh toán thành công!');
                    return redirect()->route('home');
                }
            } else {
                flash()->error('Thanh toán không thành công.');
                return redirect()->route('home');
            }
        } else {
            flash()->error('Sai chữ ký!');
            return redirect()->route('home');
        }
    }
}
