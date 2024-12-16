@extends('layouts.clients')
@section('title', 'Thanh toán')

@section('content')
    <!-- breadcrumb-area -->
    <div class="banner-area breadcrumb-area padding-top-120 padding-bottom-90">
        <div class="bread-shapes">
            <span class="b-shape-1 item-bounce"><img src="{{ asset('assets/client/images/img/5.png') }}" alt=""></span>
            <span class="b-shape-2"><img src="{{ asset('assets/client/images/img/6.png') }}" alt=""></span>
            <span class="b-shape-3"><img src="{{ asset('assets/client/images/img/7.png') }}" alt=""></span>
            <span class="b-shape-4"><img src="{{ asset('assets/client/images/img/9.png') }}" alt=""></span>
            <span class="b-shape-5"><img src="{{ asset('assets/client/images/shapes/18.png') }}" alt=""></span>
            <span class="b-shape-6 item-animateOne"><img src="{{ asset('assets/client/images/img/7.png') }}"
                    alt=""></span>
        </div>
        <div class="container padding-top-120">
            <div class="row justify-content-center">
                <nav aria-label="breadcrumb">
                    <h2 class="page-title">Thanh toán</h2>
                    <ol class="breadcrumb text-center">
                        <li class="breadcrumb-item"><a href="/">Trang chủ / </a> <a href="{{ route('checkout') }}">
                                Thanh toán</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- checkout-area -->
    <div class="checkout-area padding-top-120 padding-bottom-120">
        <div class="cshapes">
            <span class="cs-1"><img src="{{ asset('assets/client/images/img/6.png') }}" alt=""></span>
            <span class="cs-2 item-bounce"><img src="{{ asset('assets/client/images/shapes/12.png') }}"
                    alt=""></span>
            <span class="cs-3"><img src="{{ asset('assets/client/images/shapes/13.png') }}" alt=""></span>
            <span class="cs-4"><img src="{{ asset('assets/client/images/shapes/14.png') }}" alt=""></span>
            <span class="cs-5"><img src="{{ asset('assets/client/images/img/32.png') }}" alt=""></span>
            <span class="cs-6"><img src="{{ asset('assets/client/images/shapes/16.png') }}" alt=""></span>
        </div>
        <div class="container mt-5">
            <div class="row">
                <!-- Order Information -->
                <form action="{{ route('payment.process') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">Thực đơn đặt trước</h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>STT</th>
                                                <th>Món ăn</th>
                                                <th>Giá bán</th>
                                                <th>Số lượng</th>
                                                <th>Tổng phụ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($carts as $key => $cart)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td style="width: 150px">
                                                        <img src="{{ asset('storage/images/' . $cart->dish->image) }}"
                                                            alt="{{ $cart->dish->name }}" width="100">
                                                        {{ $cart->dish->name }}
                                                    </td>
                                                    <td>{{ number_format($cart->dish->price, 0, ',', '.') }}₫</td>
                                                    <td>{{ $cart->quantity }}</td>
                                                    <td><strong>{{ number_format($cart->total_price, 0, ',', '.') }}₫</strong>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 me-1">
                            <div class="card mb-2">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">Thông tin đơn đặt bàn</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Tên người đặt bàn:</strong>
                                        <span>{{ $lastReservation->name }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Điện thoại:</strong>
                                        <span>{{ $lastReservation->phone }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Thời gian dùng bữa dự kiến:</strong>
                                        <span>{{ $lastReservation->reservation_date }}
                                            ({{ $lastReservation->reservation_time }})</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Số bàn:</strong>
                                        <span>{{ $lastReservationTable->number }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Số khách:</strong>
                                        <span>{{ $lastReservation->seats }}</span>
                                    </div>
                                    {{-- <div class="mb-2">
                                        <strong>ghi chú về món ăn:</strong>
                                        <span>{{ $lastReservation->note }}</span>
                                    </div> --}}
                                    <div class="mb-2">
                                        <strong>Ghi chú về món ăn:</strong>
                                        <span>{{ \Illuminate\Support\Str::limit($lastReservation->note, 50) }}</span>
                                        @if (strlen($lastReservation->note) > 50)
                                            <a href="#"
                                                onclick="alert('{{ $lastReservation->note }}'); return false;">Xem thêm</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0 ">THANH TOÁN</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Tạm tính:</strong>
                                        <span class="float-end">{{ number_format($totalPrice, 0, ',', '.') }}₫</span>
                                    </div>
                                    @if ($carts->whereNotNull('promotion')->isNotEmpty())
                                        <div class="mb-2">
                                            <strong>Khuyến mãi:</strong>
                                            <span
                                                class="float-end">{{ $carts->whereNotNull('promotion')->first()->promotion->discount }}₫</span>
                                        </div>
                                    @endif
                                    <div class="mb-2">
                                        <strong>Tổng cộng (VAT):</strong>
                                        <span
                                            class="float-end text-danger">{{ number_format($totalPriceAfterDiscount ? $totalPriceAfterDiscount : $totalPrice, 0, ',', '.') }}₫</span>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Phương thức thanh toán:</strong>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paymentMethod"
                                                id="paymentRestaurant" value="restaurant" checked>
                                            <label class="form-check-label" for="paymentRestaurant">Thanh toán tại nhà
                                                hàng</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paymentMethod"
                                                id="paymentVNPay" value="vnpay">
                                            <label class="form-check-label" for="paymentVNPay">Thanh toán qua ví
                                                VNPAY</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paymentMethod"
                                                id="paymentmomo" value="momo">
                                            <label class="form-check-label" for="paymentmomo">Thanh toán qua Momo</label>
                                        </div>
                                        {{-- <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paymentMethod"
                                                id="paymentBankTransfer" value="bank_transfer">
                                            <label class="form-check-label" for="paymentBankTransfer">Chuyển khoản ngân
                                                hàng</label>
                                        </div> --}}
                                    </div>
                                    <button class="btn btn-success w-100">THANH TOÁN</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
