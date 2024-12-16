@extends('layouts.admin')
@section('title', 'Create Post')

@section('content')
    <div class="content-body">
        <div class="container">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thêm Bài viết</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Tiêu đề: <span class="text-danger">*</span> </label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            value="{{ old('title') }}" placeholder="Tiêu đề">
                                        @error('title')
                                            <span class="text-danger"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="formFile" class="form-label">Chọn hình ảnh</label>
                                        <input class="form-control" type="file" name="image" id="formFile">
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nội dung: <span class="text-danger">*</span></label>
                                        <textarea name="content" id="editor" cols="180" rows="3">{{ old('ticontenttle') }}</textarea>
                                        @error('content')
                                            <span class="text-danger"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">Thêm</button>
                                        <a href="{{ route('post.create') }}" class="btn btn-danger">Hủy bỏ</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                    'insertTable', 'tableColumn', 'tableRow', 'mergeTableCells', '|',
                    'undo', 'redo'
                ],
                table: {
                    contentToolbar: [
                        'tableColumn', 'tableRow', 'mergeTableCells'
                    ]
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush