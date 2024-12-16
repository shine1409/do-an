@extends('layouts.admin')
@section('title', 'List Post')

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
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <div class="card-header flex-wrap border-0">
                                        <div>
                                            <h3 class="card-title">Danh sách bài viết</h3>
                                        </div>
                                    </div>
                                    <table class="table table-responsive-md">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Tiêu đề</th>
                                                <th>Ảnh</th>
                                                {{-- <th>Nội dung</th> --}}
                                                <th>Hoạt động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($posts as $post)
                                                <tr>
                                                    <td><strong>{{ $post->id }}</strong></td>
                                                    <td>{{ $post->title }}</td>
                                                    <td><img src="{{ asset('storage/images/' . $post->image) }}"
                                                            alt="" width="150px" height="100px"></td>
                                                    {{-- <td>
                                                        {!! $post->content !!}
                                                    </td> --}}
                                                    <td>
                                                        <div class="dropdown">
                                                            <div class="btn-link" data-bs-toggle="dropdown">
                                                                <svg width="24" height="24" viewBox="0 0 24 24"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12Z"
                                                                        stroke="#737B8B" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                    </path>
                                                                    <path
                                                                        d="M18 12C18 12.5523 18.4477 13 19 13C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11C18.4477 11 18 11.4477 18 12Z"
                                                                        stroke="#737B8B" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                    </path>
                                                                    <path
                                                                        d="M4 12C4 12.5523 4.44772 13 5 13C5.55228 13 6 12.5523 6 12C6 11.4477 5.55228 11 5 11C4.44772 11 4 11.4477 4 12Z"
                                                                        stroke="#737B8B" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-menu-right" style="">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('post.edit', $post->id) }}">Chính sửa</a>
                                                                <form action="{{ route('post.destroy', $post->id) }}"
                                                                    method="POST" style="display: inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item"
                                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này ?')">Xóa</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $posts->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
