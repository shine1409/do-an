@extends('layouts.admin')


@section('title', 'Danh sách Kho')


@section('content')
    <div class="content-body">
        @if (session('success'))
            <script>
                toastr.success("{{ session('success') }}");
            </script>
        @endif
        <div class="container">
            <div class="col-xl-12">
                <div class="card dz-card" id="bootstrap-table1">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Preview" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card-header flex-wrap border-0">
                                <div>
                                    <h2 class="mt-4 card-title">Danh sách Kho</h2>
                                </div>
                                <div>
                                    <a href="{{ route('table.add') }}" class="btn btn-primary me-1">Thêm Kho</a>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-responsive-md">
                                        <thead>
                                            <tr>
                                                <th style="width:200px;"><strong>#</strong></th>
                                                <th><strong>Tên Kho</strong></th>
                                                <th><strong>Số lượng Hàng</strong></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($table as $index => $item)
                                                <tr>

                                                    <td><strong>{{ $table->firstItem() + $index }}</strong></td>
                                                    <td>{{ $item->number }}</td>
                                                    <td>{{ $item->seats }}</td>
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
                                                                    href="{{ route('table.edit', $item->id) }}">Chỉnh
                                                                    sửa</a>
                                                                <a class="dropdown-item" data-bs-toggle="modal"
                                                                    data-bs-target="#deleteModal{{ $item->id }}">Xóa</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <!-- Form ẩn để gửi DELETE request -->
                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('table.delete', $item->id) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </tr>
                                                <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel">Xóa danh mục
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Bạn có chắc chắn muốn xóa danh mục này không?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Hủy</button>
                                                                <form action="{{ route('table.delete', $item->id) }}"
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
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $table->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
