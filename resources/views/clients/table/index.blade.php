@extends('layouts.clients')
@section('title', 'Đặt bàn')
@section('content')
    @push('css')
        <style>
            .banghe {
                background-color: white;
                border: 1px solid #DC3545;
                color: #DC3545;
                transition: background-color 0.3s, color 0.3s;
            }

            .banghe.booked {
                background-color: white;
                color: #DC3545;
                pointer-events: none;
                opacity: 0.5;
                /* Tùy chọn: Làm mờ các bàn bị ẩn */
            }

            .banghe.selected {
                background-color: #DC3545;
                color: white;
            }
        </style>
    @endpush
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
                    <h2 class="page-title">Trang đặt bàn</h2>
                    <ol class="breadcrumb text-center">
                        <li class="breadcrumb-item"><a href="/">Trang chủ</a> / <a
                                href="{{ route('tableClient') }}">Đặt bàn</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- contact-form-area -->
    <section class="about-area about-area2 writeto-us login-area signup-area padding-top-120 padding-bottom-120">
        <div class="container">
            {{-- @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}
            <form action="{{ route('tableClient.store') }}" method="POST">
                @csrf
                <h4 class="mb-3 text-center text-danger text-bold">Đặt bàn giữ chỗ</h4>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Ngày đặt</label>
                        <input type="date" name="reservation_date" class="form-control" id="reservation_date"
                            value="{{ old('reservation_date') }}">
                        @error('reservation_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Giờ đặt</label>
                        <input type="time" name="reservation_time" class="form-control" id="reservation_time"
                            value="{{ old('reservation_time') }}">
                        @error('reservation_time')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">Tên người đặt bàn</label>
                        <input type="text" name="name" class="form-control" id="name"
                            value="{{ old('name') }}" placeholder="Nhập tên người đặt bàn">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" id="phone"
                            value="{{ old('phone') }}" placeholder="Nhập tên người đặt bàn">
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Chọn bàn</label>
                    <div class="row g-2">
                        @foreach ($tables as $table)
                            <div class="col-4">
                                <button type="button" class="banghe btn btn-outline-danger w-100 table-select-btn"
                                    data-table-id="{{ $table->id }}" data-table-seats="{{ $table->seats }}">Bàn
                                    {{ $table->number }} - {{ $table->seats }} ghế</button>
                            </div>
                        @endforeach
                        @error('table_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <input type="hidden" name="table_id" id="selected-table-id">
                </div>
                <div class="mb-3">
                    <label class="form-label">Chọn món</label>
                    <button type="button" class="btn form-control" data-bs-toggle="modal" data-bs-target="#dishModal">
                        Chọn món ăn
                    </button>
                    <div id="selected-dishes" class="mt-3"></div>
                </div>
                <div class="modal fade" id="dishModal" tabindex="-1" aria-labelledby="dishModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dishModalLabel">Chọn món ăn</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Tìm kiếm món ăn -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <input type="text" id="search-dish-name" class="form-control"
                                            placeholder="Tìm kiếm theo tên">
                                    </div>
                                    <div class="col-md-4">
                                        <select id="filter-category" class="form-control">
                                            <option value="">Chọn danh mục</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" id="min-price" class="form-control"
                                            placeholder="Giá thấp nhất">
                                        <input type="number" id="max-price" class="form-control mt-2"
                                            placeholder="Giá cao nhất">
                                    </div>
                                </div>

                                <!-- Danh sách món ăn -->
                                <div id="dish-list">
                                    @foreach ($categories as $category)
                                        <div class="category-container" data-category="{{ $category->id }}">
                                            <h5 class="category-title">{{ $category->name }}</h5>
                                            <div class="row">
                                                @foreach ($category->dishes as $dish)
                                                    <div class="col-md-4 dish-item" data-name="{{ $dish->name }}"
                                                        data-category="{{ $category->id }}"
                                                        data-price="{{ $dish->price }}">
                                                        <div class="card mb-5">
                                                            <img src="{{ asset('storage/images/' . $dish->image) }}"
                                                                width="232px" height="174px" class="card-img-top"
                                                                alt="{{ $dish->name }}">
                                                            <div class="p-2">
                                                                <h5 class="card-title mb-0">
                                                                    {{ $dish->name }}</h5>
                                                                <span
                                                                    class="mb-0">{{ number_format($dish->price, 0, ',', '.') }}
                                                                    VNĐ</span>
                                                                <p>Còn lại: {{ $dish->quantity }} món
                                                                </p>
                                                            </div>
                                                            <button type="button" class="btn btn-primary select-dish"
                                                                data-id="{{ $dish->id }}"
                                                                data-name="{{ $dish->name }}"
                                                                data-image="{{ asset('storage/images/' . $dish->image) }}">
                                                                Chọn
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="dish-quantity-container">
                    @foreach (old('dish_id', []) as $dishId)
                        @php
                            $dish = $dishes->find($dishId);
                        @endphp
                        @if ($dish)
                            <div class="row selected-dish-item mb-3" id="dish-{{ $dishId }}">
                                <div class="col-md-4">
                                    <img src="{{ asset('storage/images/' . $dish->image) }}" alt="{{ $dish->name }}"
                                        class="img-thumbnail" style="width: 150px;">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" name="dish_id[]" value="{{ $dishId }}">
                                    <span>{{ $dish->name }}</span>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="quantities[{{ $dishId }}]" class="form-control"
                                        min="1" value="{{ old('quantities.' . $dishId) }}"
                                        placeholder="Số lượng">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger remove-dish p-0"
                                        aria-label="Remove dish">
                                        &times;
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="mb-3 col-md-12">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="note" class="form-control" placeholder="Ghi chú">{{ old('note') }}</textarea>
                    @error('note')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-book-now">Đặt chỗ ngay</button>
            </form>
        </div>
    </section>
    @push('script')
        <script>
            $(document).ready(function() {
                function filterDishes() {
                    var searchName = $('#search-dish-name').val().toLowerCase();
                    var category = $('#filter-category').val();
                    var minPrice = parseInt($('#min-price').val()) || 0;
                    var maxPrice = parseInt($('#max-price').val()) || Infinity;

                    $('.category-container').each(function() {
                        var hasVisibleDish = false;
                        var categoryId = $(this).data('category');

                        $(this).find('.dish-item').each(function() {
                            var dishName = $(this).data('name').toLowerCase();
                            var dishCategory = $(this).data('category');
                            var dishPrice = parseInt($(this).data('price'));

                            if (
                                (searchName === "" || dishName.includes(searchName)) &&
                                (category === "" || dishCategory == category) &&
                                dishPrice >= minPrice &&
                                dishPrice <= maxPrice
                            ) {
                                $(this).show();
                                hasVisibleDish = true;
                            } else {
                                $(this).hide();
                            }
                        });

                        if (hasVisibleDish) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }

                $('#search-dish-name, #filter-category, #min-price, #max-price').on('input change', filterDishes);
            });

            $('#reservation_date, #reservation_time').on('change', function() {
                var date = $('#reservation_date').val();
                var time = $('#reservation_time').val();

                if (date && time) {
                    $.ajax({
                        url: '{{ route('check.table.availability') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            reservation_date: date,
                            reservation_time: time
                        },
                        success: function(response) {
                            console.log('Response từ server:', response);

                            $('.table-select-btn').each(function() {
                                var tableId = parseInt($(this).data('table-id'));

                                if ($.inArray(tableId, response.unavailableTables) !== -1) {
                                    console.log('Bàn này đã được đặt, ID:', tableId);
                                    $(this).addClass('booked').hide(); // Ẩn bàn đã bị đặt
                                } else {
                                    console.log('Bàn này khả dụng, ID:', tableId);
                                    $(this).removeClass('booked')
                                        .show(); // Hiển thị các bàn khả dụng
                                }
                            });
                        }
                    });
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                const selectedDishesContainer = document.getElementById('selected-dishes');
                const selectDishButtons = document.querySelectorAll('.select-dish');

                function addRemoveButtonListener(dishRow) {
                    dishRow.querySelector('.remove-dish').addEventListener('click', function() {
                        dishRow.remove();
                    });
                }

                // Attach event listeners to any pre-existing selected dish items
                document.querySelectorAll('.selected-dish-item').forEach(dishRow => {
                    addRemoveButtonListener(dishRow);
                });

                selectDishButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const dishId = this.getAttribute('data-id');
                        const dishName = this.getAttribute('data-name');
                        const dishImage = this.getAttribute('data-image');

                        // Check if the dish is already selected
                        if (!document.querySelector(`#dish-${dishId}`)) {
                            // Add dish to the selected dishes list
                            const dishRow = document.createElement('div');
                            dishRow.className = 'row selected-dish-item mb-3';
                            dishRow.id = `dish-${dishId}`;
                            dishRow.innerHTML = `
                    <div class="col-md-4">
                        <img src="${dishImage}" alt="${dishName}" class="img-thumbnail" style="width: 150px;">
                    </div>
                    <div class="col-md-4">
                        <input type="hidden" name="dish_id[]" value="${dishId}">
                        <span>${dishName}</span>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="quantities[${dishId}]" class="form-control" min="1" value="1" placeholder="Số lượng">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-dish p-0" aria-label="Remove dish">
                            &times;
                        </button>
                    </div>
                `;
                            selectedDishesContainer.appendChild(dishRow);
                            addRemoveButtonListener(
                                dishRow);
                        }
                    });
                });

                const tableSelectButtons = document.querySelectorAll('.table-select-btn');
                const selectedTableIdInput = document.getElementById('selected-table-id');

                tableSelectButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        tableSelectButtons.forEach(btn => btn.classList.remove('selected'));
                        this.classList.add('selected');
                        selectedTableIdInput.value = this.getAttribute('data-table-id');
                    });
                });
            });
        </script>
    @endpush


@endsection
