@extends('layouts.admin')

@section('title', 'Chỉnh sửa bàn')

@section('content')
    <div class="content-body">
        <div class="container">
            <div class="col-xl-12">
                <div class="card dz-card" id="bootstrap-table1">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Preview" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card-header flex-wrap border-0">
                                <div>
                                    <h2 class="card-title">Cập nhật Kho</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <form action="{{ route('table.update', $table->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <!-- Tên danh mục -->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="number" class="form-label">Tên Kho</label>
                                                <input type="number" class="form-control" name="number" id="number"
                                                    placeholder="Nhập tên danh mục"
                                                    value="{{ old('number', $table->number) }}" />
                                                @error('number')
                                                    <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- Số lượng ghế -->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="seats" class="form-label">Số lượng Hàng</label>
                                                <input type="number" class="form-control" name="seats" id="seats"
                                                    value="{{ old('number', $table->seats) }}"
                                                    placeholder="Vui lòng chọn số" />
                                                @error('seats')
                                                    <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Nút cập nhật -->
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
