@extends('layouts.admin')


@section('title', 'Thêm Kho')


@section('content')
    <div class="content-body">
        <div class="container">
            <div class="col-xl-12">
                <div class="card dz-card" id="bootstrap-table1">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Preview" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card-header flex-wrap border-0">
                                <div>
                                    <h2 class="card-title">Thêm Kho</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <form action="{{ route('table.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">mã Kho</label>
                                            <input type="number" class="form-control" name="number" id="number" value="{{ old('number') }}"
                                                placeholder="Vui lòng chọn số" />
                                                @error('number')
                                                <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Số lượng Hàng</label>
                                            <input type="number" class="form-control" name="seats" id="seats" value="{{ old('seats') }}"
                                                placeholder="Vui lòng chọn số" />
                                                @error('seats')
                                                <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
								<button class="btn btn-primary">Thêm</button>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
