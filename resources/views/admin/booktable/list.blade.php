@extends('layouts.admin')
@section('content')
    <div class="content-body">
        <div class="container">
            <div class="col-xl-12">
                <div class="card dz-card" id="bootstrap-table1">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Preview" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <div class="card-header flex-wrap bitem-0">
                                        <div>
                                            <h3 class="card-title">Danh Sách Kho Hàng</h3>
                                        </div>
                                        <div>
                                            <a href="{{ route('table-book.add') }}" class="btn btn-primary me-1">Thêm Kho Hàng
                                                </a>
                                        </div>
                                    </div>
                                    <table class="table table-responsive-md">
                                        <thead>
                                            <tr>
                                                <th style="width:80px;"><strong>#</strong></th>
                                                <th><strong>Người Nhập Kho</strong></th>
                                                <th><strong>Kho số</strong></th>
                                                <th><strong>Số Hàng</strong></th>
                                                <th><strong>Hàng</strong></th>
                                                <th><strong>Giờ và ngày Nhập</strong></th>
                                                <th><strong>Tổng tiền</strong></th>
                                                <th><strong>Trạng thái</strong></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reservation as $item)
                                                <tr>
                                                    <td><strong>{{ $loop->iteration }}</strong></td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>Kho {{ $item->table->number }}</td>
                                                    <td>{{ $item->seats }} Hàng</td>
                                                    <td>
                                                        @foreach ($item->productes as $product)
                                                            {{ $product->name }} ({{ $product->pivot->quantity }}),
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $item->reservation_time }} | {{ $item->reservation_date }}</td>
                                                    <td>{{ number_format($item->calculateTotalPrice(), 0, ',', '.') }} VND
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $item->status == 'Đã thanh toán' ? 'badge-success' : 'badge-danger' }}">
                                                            {{ $item->status == 'Đã thanh toán' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-success light sharp"
                                                                data-bs-toggle="dropdown">
                                                                <svg width="20px" height="20px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <circle fill="#000000" cx="5" cy="12"
                                                                            r="2" />
                                                                        <circle fill="#000000" cx="12" cy="12"
                                                                            r="2" />
                                                                        <circle fill="#000000" cx="19" cy="12"
                                                                            r="2" />
                                                                    </g>
                                                                </svg>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('table-book.edit', $item->id) }}">Chỉnh
                                                                    sửa</a>
                                                                @if ($item->status != 'Đã thanh toán')
                                                                    <a class="dropdown-item" data-bs-toggle="modal"
                                                                        data-bs-target="#paymentModal{{ $item->id }}">Thanh
                                                                        toán</a>
                                                                @endif
                                                                <a class="dropdown-item" data-bs-toggle="modal"
                                                                    data-bs-target="#deleteModal{{ $item->id }}">Xóa</a>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Payment Modal -->
                                                    <div class="modal fade" id="paymentModal{{ $item->id }}"
                                                        tabindex="-1"
                                                        aria-labelledby="paymentModalLabel{{ $item->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="paymentModalLabel{{ $item->id }}">Thanh
                                                                        toán đơn hàng</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="paymentForm{{ $item->id }}"
                                                                        method="POST"
                                                                        action="{{ route('payment.store') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="reservation_id"
                                                                            value="{{ $item->id }}">
                                                                        <input type="hidden" name="order_id"
                                                                            value="{{ $item->order ? $item->order->id : '' }}">

                                                                        <div class="mb-3">
                                                                            <label for="amountDue" class="form-label">Số
                                                                                tiền cần trả</label>
                                                                            <input type="text" class="form-control"
                                                                                name="total_amount"
                                                                                id="amountDue{{ $item->id }}" readonly
                                                                                value="{{ $item->calculateTotalPrice() }}">
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="amountGiven" class="form-label">Số
                                                                                tiền khách đưa</label>
                                                                            <input type="number" class="form-control"
                                                                                name="amount_given"
                                                                                id="amountGiven{{ $item->id }}"
                                                                                required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="amountChange" class="form-label">Số
                                                                                tiền trả lại</label>
                                                                            <input type="text" class="form-control"
                                                                                id="amountChange{{ $item->id }}"
                                                                                readonly>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="paymentMethod"
                                                                                class="form-label">Phương thức thanh
                                                                                toán</label>
                                                                            <select class="form-control"
                                                                                name="payment_method" required>
                                                                                <option value="Tiền mặt">Tiền mặt</option>
                                                                            </select>
                                                                        </div>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Thanh toán</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="deleteModal{{ $item->id }}"
                                                        tabindex="-1"
                                                        aria-labelledby="deleteModalLabel{{ $item->id }}"
                                                        aria-hidden="                                                        true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="deleteModalLabel{{ $item->id }}">Xác nhận
                                                                        xóa</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Bạn có chắc chắn muốn xóa đơn hàng này không?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Hủy</button>
                                                                    <form
                                                                        action="{{ route('table-book.destroy', $item->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Xóa</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Phân trang -->
                            <div class="d-flex justify-content-center">
                                {{ $reservation->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal In Hóa Đơn -->
    <!-- Modal In Hóa Đơn -->
    @if (session('print_receipt'))
        @php
            $order = session('print_receipt');
            $payment = $order->payment;
        @endphp
        <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="receiptModalLabel">Hóa Đơn Thanh Toán</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body-order">
                        <!-- Thông tin cửa hàng -->
                        <div style="text-align: center;">
                            <h4>AnKor</h4>
                            <p>HÓA ĐƠN THANH TOÁN</p>
                            <p>Số: {{ $order->code_order }}</p>
                            <p>Ngày: {{ $order->order_date->format('d/m/Y H:i') }}</p>
                            <p>Thu ngân: Minh Hoa</p>
                        </div>

                        <!-- Danh sách món ăn -->
                        <div class="container">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th style="border: 1px solid black;">#</th>
                                        <th style="border: 1px solid black;">Tên hàng</th>
                                        <th style="border: 1px solid black;">SL</th>
                                        <th style="border: 1px solid black;">Đơn giá</th>
                                        <th style="border: 1px solid black;">TT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->order_product as $index => $orderProduct)
                                        <tr style="text-align: center;">
                                            <td style="border: 1px solid black;">{{ $index + 1 }}</td>
                                            <td style="border: 1px solid black;">{{ $orderProduct->product->name }}</td>
                                            <td style="border: 1px solid black;">
                                                {{ $orderProduct->quantity }}
                                            </td>
                                            <td style="border: 1px solid black;">
                                                {{ number_format($orderProduct->product->price, 0, ',', '.') }} VND</td>
                                            <td style="border: 1px solid black;">
                                                {{ number_format($orderProduct->quantity * $orderProduct->product->price, 0, ',', '.') }}
                                                VND</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Tổng tiền -->
                            <div style="margin-top: 20px;">
                                <p><strong>Tổng TT:</strong>
                                    {{ number_format($order->payment->total_amount, 0, ',', '.') }}
                                    VND</p>
                            </div>
                        </div>

                        <div style="text-align: center;">
                            <p>Trân trọng cảm ơn!</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" onclick="printReceipt()">In Hóa Đơn</button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <script>
        // Tự động mở modal in hóa đơn nếu tồn tại session print_receipt
        document.addEventListener('DOMContentLoaded', function() {
            if ("{{ session('print_receipt') }}") {
                new bootstrap.Modal(document.getElementById('receiptModal')).show();
            }
        });

        function printReceipt() {
            var content = document.querySelector('.modal-body-order').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = content;

            window.print();

            document.body.innerHTML = originalContents;
            location.reload(); // Reload the page to restore the original state
        }


        // Sự kiện lắng nghe để tính tiền thừa khi người dùng nhập số tiền
        document.querySelectorAll('[id^=amountGiven]').forEach(input => {
            input.addEventListener('input', function() {
                let itemId = this.id.replace('amountGiven', ''); // Lấy ID của phần tử hiện tại
                let amountGiven = parseFloat(this.value.replace(/[^0-9.-]+/g, '')) ||
                    0; // Lấy giá trị người dùng nhập
                let amountDue = parseFloat(document.getElementById('amountDue' + itemId).value.replace(
                    /[^0-9.-]+/g, '')) || 0; // Lấy số tiền cần trả
                let amountChange = amountGiven - amountDue; // Tính số tiền thừa

                // Định dạng số với dấu phân cách hàng nghìn
                function formatNumber(num) {
                    return num.toLocaleString('vi-VN', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }

                // Cập nhật giá trị của số tiền thừa
                document.getElementById('amountChange' + itemId).value = amountChange >= 0 ?
                    formatNumber(amountChange) + ' VNĐ' :
                    'Số tiền không đủ'; // Hiển thị thông báo nếu tiền không đủ
            });
        });
    </script>
@endsection
