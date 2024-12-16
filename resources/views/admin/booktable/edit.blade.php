@extends('layouts.admin')

@section('title', 'Chỉnh sửa đặt kho')

@section('content')
    <div class="content-body">
        <div class="container">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Chỉnh sửa Kho</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ route('table-book.update', $reservation->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Ngày Nhập</label>
                                        <input type="date" name="order_date" class="form-control" id="reservation_date"
                                            value="{{ old('order_date', $reservation->reservation_date) }}">
                                        @error('order_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Giờ Nhập</label>
                                        <input type="time" name="order_time" class="form-control" id="reservation_time"
                                            value="{{ old('order_time', \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i')) }}">
                                        @error('order_time')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Người Nhập</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $reservation->name) }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Vị trí kho</label>
                                        <select id="table-select" name="table_id" class="form-control wide">
                                            <option value="" disabled selected>Chọn bàn</option>
                                            @foreach ($tables as $table)
                                                <option value="{{ $table->id }}" data-seats="{{ $table->seats }}"
                                                    {{ old('table_id', $reservation->table_id) == $table->id ? 'selected' : '' }}>
                                                    Bàn số {{ $table->number }}</option>
                                            @endforeach
                                        </select>
                                        @error('table_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Số Hàng</label>
                                        <input type="number" id="seats-input" name="seats" class="form-control"
                                            value="{{ old('seats', $reservation->table->seats) }}">
                                        @error('seats')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Chọn Hàng</label>
                                        <button type="button" class="btn form-control" data-bs-toggle="modal"
                                            data-bs-target="#dishModal ">
                                            Chọn Hàng
                                        </button>
                                        <div id="selected-dishes">
                                            @foreach (old('dish_id', $reservation->dishes->pluck('id')) as $index => $dishId)
                                                @php
                                                    $dish = $dishes->find($dishId);
                                                    $reservationDish = $reservation->dishes
                                                        ->where('id', $dishId)
                                                        ->first();
                                                    $quantity = old(
                                                        "quantities.$dishId",
                                                        $reservationDish ? $reservationDish->pivot->quantity : 1,
                                                    );
                                                @endphp
                                                <div class="dish-item mb-5 mt-5 position-relative"
                                                    data-dish-id="{{ $dishId }}">
                                                    <button type="button"
                                                        class="btn-close position-absolute top-0 end-0 remove-dish"
                                                        aria-label="Close" data-dish-id="{{ $dishId }}"></button>
                                                    <label class="form-label">
                                                        <img src="{{ asset('storage/images/' . $dish->image) }}"
                                                            alt="{{ $dish->name }}" width="150px" height="100px">
                                                        {{ $dish->name }} - Số lượng
                                                    </label>
                                                    <input type="hidden" name="dish_id[]" value="{{ $dishId }}">
                                                    <input type="number" name="quantities[{{ $dishId }}]"
                                                        class="form-control" min="1" placeholder="Số lượng"
                                                        value="{{ $quantity }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="modal fade" id="dishModal" tabindex="-1" aria-labelledby="dishModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="dishModalLabel">Chọn Hàng</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Tìm kiếm món ăn -->
                                                    <div class="row mb-4">
                                                        <div class="col-md-4">
                                                            <input type="text" id="search-dish-name"
                                                                class="form-control" placeholder="Tìm kiếm theo tên">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select id="filter-category" class="form-control">
                                                                <option value="">Chọn Loại đèn</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}">
                                                                        {{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="number" id="min-price" class="form-control"
                                                                placeholder="Giá thấp nhất">
                                                            <input type="number" id="max-price"
                                                                class="form-control mt-2" placeholder="Giá cao nhất">
                                                        </div>
                                                    </div>

                                                    <!-- Danh sách món ăn -->
                                                    <div id="dish-list">
                                                        @foreach ($categories as $category)
                                                            <div class="category-container"
                                                                data-category="{{ $category->id }}">
                                                                <h5 class="category-title">{{ $category->name }}</h5>
                                                                <div class="row">
                                                                    @foreach ($category->dishes as $dish)
                                                                        <div class="col-md-4 dish-item"
                                                                            data-name="{{ $dish->name }}"
                                                                            data-category="{{ $category->id }}"
                                                                            data-price="{{ $dish->price }}">
                                                                            <div class="card mb-5">
                                                                                <img src="{{ asset('storage/images/' . $dish->image) }}"
                                                                                    width="232px" height="174px"
                                                                                    class="card-img-top"
                                                                                    alt="{{ $dish->name }}">
                                                                                <div class="">
                                                                                    <h5 class="card-title mb-0">
                                                                                        {{ $dish->name }}</h5>
                                                                                    <span
                                                                                        class="mb-0">{{ number_format($dish->price, 0, ',', '.') }}
                                                                                        VNĐ</span>
                                                                                    <p>Còn lại: {{ $dish->quantity }} cái
                                                                                    </p>
                                                                                </div>
                                                                                <button type="button"
                                                                                    class="btn btn-primary select-dish"
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
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Ghi chú</label>
                                        <textarea name="note" class="form-control" placeholder="Ghi chú">{{ old('note', $reservation->note) }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <select name="status" class="default-select form-control wide">
                                            <option value="Đã thanh toán"
                                                {{ old('status', $reservation->status) == 'Đã thanh toán' ? 'selected' : '' }}>
                                                Đã thanh toán
                                            </option>
                                            <option value="Chưa thanh toán"
                                                {{ old('status', $reservation->status) == 'Chưa thanh toán' ? 'selected' : '' }}>
                                                Chưa thanh toán
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                                    </div>
                                </div>
                            </form>

                            @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul>
                                        @foreach ($errors->get('dish_id.*') as $key => $messages)
                                            @foreach ($messages as $message)
                                                <div class="text-danger">{{ $message }}</div>
                                            @endforeach
                                        @endforeach

                                        @foreach ($errors->get('quantities.*') as $key => $messages)
                                            @foreach ($messages as $message)
                                                <div class="text-danger">{{ $message }}</div>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        $(document).ready(function() {
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

                            $('#table-select option').each(function() {
                                var tableId = parseInt($(this).val());

                                if ($.inArray(tableId, response.unavailableTables) !== -
                                    1) {
                                    console.log('Bàn này đã được đặt, ID:', tableId);
                                    $(this).hide();
                                } else {
                                    console.log('Bàn này khả dụng, ID:', tableId);
                                    $(this)
                                        .show(); // Đảm bảo rằng các kho khả dụng sẽ hiển thị
                                }
                            });
                        }
                    });
                }
            });
        });



        document.addEventListener('DOMContentLoaded', function() {
            const tableSelect = document.getElementById('table-select');
            const seatsInput = document.getElementById('seats-input');
            const selectedDishesContainer = document.getElementById('selected-dishes');

            tableSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const seats = selectedOption.getAttribute('data-seats');
                seatsInput.value = seats;
            });

            document.querySelectorAll('.select-dish').forEach(button => {
                button.addEventListener('click', function() {
                    const dishId = this.getAttribute('data-id');
                    const dishName = this.getAttribute('data-name');
                    const dishImage = this.getAttribute('data-image');

                    const existingDish = selectedDishesContainer.querySelector(
                        `[data-dish-id="${dishId}"]`);
                    if (!existingDish) {
                        const dishElement = document.createElement('div');
                        dishElement.setAttribute('data-dish-id', dishId);
                        dishElement.classList.add('dish-item');
                        dishElement.innerHTML = `
                            <div class="position-relative mb-5 mt-5">
                                <button type="button" class="btn-close position-absolute top-0 end-0 remove-dish" aria-label="Close" data-dish-id="${dishId}"></button>
                                <label class="form-label">
                                    <img src="${dishImage}" alt="${dishName}" width="150px" height="100px"> ${dishName} - Số lượng
                                </label>
                                <input type="hidden" name="dish_id[]" value="${dishId}">
                            <input type="number" name="quantities[${dishId}]" class="form-control" min="1" placeholder="Số lượng">
                        `;
                        selectedDishesContainer.appendChild(dishElement);
                    }
                });
            });

            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-dish')) {
                    const dishId = event.target.getAttribute('data-dish-id');
                    const dishElement = selectedDishesContainer.querySelector(`[data-dish-id="${dishId}"]`);
                    if (dishElement) {
                        dishElement.remove();
                    }
                }
            });

            // Function to add a dish to the selected list
            function addDishToSelected(dishId, dishName, dishImage, quantity = 1) {
                const existingDish = selectedDishesContainer.querySelector(`[data-dish-id="${dishId}"]`);
                if (!existingDish) {
                    const dishElement = document.createElement('div');
                    dishElement.setAttribute('data-dish-id', dishId);
                    dishElement.classList.add('dish-item', 'mb-5', 'mt-5', 'position-relative');
                    dishElement.innerHTML = `
                        <button type="button" class="btn-close position-absolute top-0 end-0 remove-dish" aria-label="Close" data-dish-id="${dishId}"></button>
                        <label class="form-label">
                            <img src="${dishImage}" alt="${dishName}" width="150px" height="100px"> ${dishName} - Số lượng
                        </label>
                        <input type="hidden" name="dish_id[]" value="${dishId}">
                        <input type="number" name="quantities[${dishId}]" class="form-control" min="1" placeholder="Số lượng" value="${quantity}">
                    `;
                    selectedDishesContainer.appendChild(dishElement);
                }
            }

            // Handle existing dishes on page load
            const reservationDishes = @json($reservation->dishes->pluck('pivot.quantity', 'id')->toArray());
            for (const [dishId, quantity] of Object.entries(reservationDishes)) {
                const dishName = document.querySelector(`.select-dish[data-id="${dishId}"]`).getAttribute(
                    'data-name');
                const dishImage = document.querySelector(`.select-dish[data-id="${dishId}"]`).getAttribute(
                    'data-image');
                addDishToSelected(dishId, dishName, dishImage, quantity);
            }

            tableSelect.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
