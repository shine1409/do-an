@extends('layouts.admin')
@section('title', 'detail order')
@section('content')
    <div class="content-body">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-12 col-md-6">
                            <div class="card">
                                <div class="card-header border-0 pb-0">
                                    <h4 class="h-title">Mặt hàng</h4>
                                    <div class="dropdown custom-dropdown mb-0">
                                        <div class="btn sharp tp-btn dark-btn" data-bs-toggle="dropdown">
                                            <svg width="5" height="18" viewBox="0 0 5 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="2.25748" cy="2.19083" r="1.92398" fill="#1921FA" />
                                                <circle cx="2.25748" cy="8.92471" r="1.92398" fill="#1921FA" />
                                                <circle cx="2.25748" cy="15.6585" r="1.92398" fill="#1921FA" />
                                            </svg>

                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item"
                                                href="{{ route('order.pdf', ['id' => $order->id]) }}">In hóa đơn</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-2">
                                    @foreach ($order->dishes as $dish)
                                        <div class="food-items-bx">
                                            <div class="food-items-media">
                                                <img src="{{ asset('storage/images/' . $dish->image) }}" alt="">
                                            </div>
                                            <div class="d-flex align-items-end">
                                                <div class="food-items-info">
                                                    <h6>{{ $dish->name }}</h6>
                                                    <span>{{ $dish->pivot->quantity }}x</span>
                                                </div>
                                                <div class="d-inline-flex text-nowrap">
                                                    <span
                                                        class="me-2">{{ number_format($dish->price, 0, ',', '.') }}đ</span>
                                                    <h6 class="mb-0 text-primary">
                                                        {{ number_format($dish->price * $dish->pivot->quantity, 0, ',', '.') }}đ
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <hr>
                                    <div class="food-totle">
                                        <ul class="d-flex align-items-center justify-content-between">
                                            <li><span>
                                                    Số bàn</span></li>
                                            <li>
                                                <h6>{{ $order->table->number }}</h6>
                                        </ul>
                                        <ul class="d-flex align-items-center justify-content-between">
                                            <li><span>
                                                    Ngày và giờ thanh toán</span></li>
                                            <li>
                                                <h6>{{ $order->order_date }} ({{ $order->order_time }})</h6>
                                        </ul>
                                        <ul class="d-flex align-items-center justify-content-between">
                                            <li><span>
                                                    Khuyễn mãi</span></li>
                                            <li>
                                                <h6>-{{ number_format($order->promotion->discount ?? 0, 0, ',', '.') }}đ
                                                </h6>
                                        </ul>
                                        @php
                                            $totalAmount = $order->dishes->sum(function ($dish) {
                                                return $dish->price * $dish->pivot->quantity;
                                            });
                                        @endphp
                                        <ul class="d-flex align-items-center justify-content-between">
                                            <li><span>
                                                    Tổng cộng</span></li>
                                            <li>
                                                <h6>{{ number_format($totalAmount, 0, ',', '.') }}đ</h6>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
