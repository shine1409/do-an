@extends('layouts.admin')

@section('title', 'Thêm kho')

@section('content')
    <div class="content-body">
        <div class="container">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thêm Kho</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ route('table-book.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="reservation_date" class="form-label">Ngày Nhập</label>
                                            <input type="date" name="reservation_date" class="form-control"
                                                id="reservation_date" value="{{ old('reservation_date') }}">
                                            @error('reservation_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="reservation_time" class="form-label">Giờ Nhập</label>
                                            <input type="time" name="reservation_time" class="form-control"
                                                id="reservation_time" value="{{ old('reservation_time') }}">
                                            @error('reservation_time')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Người Nhập</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Tên người đặt" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    
                                   
                                    

                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Ghi chú</label>
                                        <textarea name="note" class="form-control" placeholder="Ghi chú">{{ old('note') }}</textarea>
                                        @error('note')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <select name="status" class="default-select form-control wide">
                                            <option value="Đã thanh toán"
                                                {{ old('status') == 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán
                                            </option>
                                            <option value="Chưa thanh toán"
                                                {{ old('status') == 'Chưa thanh toán' ? 'selected' : '' }}>Chưa thanh toán
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">Đặt Lịch</button>
                                    </div>
                                </div>
                            </form>

                            {{-- @if ($errors->any())
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
                            @endif --}}
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
                                        .show(); // Đảm bảo rằng các bàn khả dụng sẽ hiển thị
                                }
                            });
                        }
                    });
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            const tableSelect = document.getElementById('table-select');
            const tableSeats = document.getElementById('table-seats');

            // Fetch table details when table is selected
            tableSelect.addEventListener('change', function() {
                const tableId = this.value;
                if (tableId) {
                    fetch(`/api/table-book/table-details/${tableId}`)
                        .then(response => response.json())
                        .then(data => {
                            tableSeats.value = data.seats;
                            tableSeats.removeAttribute('readonly');
                        })
                        .catch(error => console.error('Error fetching table details:', error));
                } else {
                    tableSeats.value = '';
                    tableSeats.setAttribute('readonly', 'readonly');
                }
            });

            // Trigger change event on page load to set initial seats value if a table is already selected
            if (tableSelect.value) {
                tableSelect.dispatchEvent(new Event('change'));
            }

            const selectedDishesContainer = document.getElementById('selected-dishes');

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
                            @error('quantities')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                        </div>
                    `;
                        selectedDishesContainer.appendChild(dishElement);

                        // Add event listener for remove button
                        dishElement.querySelector('.remove-dish').addEventListener('click',
                            function() {
                                dishElement.remove();
                            });
                    }
                });
            });

            // Get old quantities from server-side rendered inputs
            const oldQuantities = @json(old('quantities', []));

            // Trigger change event to initialize old quantities on page load
            if (Object.keys(oldQuantities).length > 0) {
                for (const [dishId, quantity] of Object.entries(oldQuantities)) {
                    const dishElement = selectedDishesContainer.querySelector(
                        `[data-dish-id="${dishId}"]`);
                    if (dishElement) {
                        dishElement.querySelector('input[type="number"]').value = quantity;
                    } else {
                        // Create a new dish element if it doesn't exist
                        const dishName = document.querySelector(`.select-dish[data-id="${dishId}"]`).getAttribute(
                            'data-name');
                        const dishImage = document.querySelector(`.select-dish[data-id="${dishId}"]`).getAttribute(
                            'data-image');

                        const newDishElement = document.createElement('div');
                        newDishElement.setAttribute('data-dish-id', dishId);
                        newDishElement.classList.add('dish-item');
                        newDishElement.innerHTML = `
                        <div class="position-relative mb-5 mt-5">
                            <button type="button" class="btn-close position-absolute top-0 end-0 remove-dish" aria-label="Close" data-dish-id="${dishId}"></button>
                            <label class="form-label">
                                <img src="${dishImage}" alt="${dishName}" width="150px" height="100px"> ${dishName} - Số lượng
                            </label>
                            <input type="hidden" name="dish_id[]" value="${dishId}">
                            <input type="number" name="quantities[${dishId}]" class="form-control" min="1" placeholder="Số lượng" value="${quantity}">
                        </div>
                    `;
                        selectedDishesContainer.appendChild(newDishElement);

                        // Add event listener for remove button
                        newDishElement.querySelector('.remove-dish').addEventListener('click', function() {
                            newDishElement.remove();
                        });
                    }
                }
            }
        });
    </script>
@endsection
