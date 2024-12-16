@extends('layouts.admin')
@section('title', 'Edit Post')

@section('content')
    <div class="content-body">
        <div class="container">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sửa người dùng</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Tiêu đề: <span class="text-danger">*</span> </label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            value="{{ old('title') ?? $post->title }}" placeholder="Tiêu đề">
                                        @error('title')
                                            <span class="text-danger"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="formFile" class="form-label">Chọn hình ảnh</label>
                                        <input class="form-control" type="file" name="image" id="formFile">
                                        @if ($post->image)
                                            <div id="imagePreview" class="mt-3 position-relative">
                                                <button type="button" id="removeImage"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                    style="z-index: 1;">X</button>
                                                <img src="{{ asset('storage/images/' . $post->image) }}"
                                                    alt="{{ $post->name }}" width="100" class="img-thumbnail">
                                            </div>
                                        @endif
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="content" class="form-label">Nội dung: <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="content" id="editor">{{ old('content') ?? $post->content }}</textarea>
                                        @error('content')
                                            <span class="text-danger"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
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
    <script>
        document.getElementById('formFile').addEventListener('change', function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.innerHTML = '<img src="' + reader.result +
                    '" width="100" class="mt-3"><button type="button" id="removeImage" class="btn btn-danger btn-sm">X</button>';
                document.getElementById('removeImage').addEventListener('click', function() {
                    output.innerHTML = '';
                    document.getElementById('formFile').value = '';
                });
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        if (document.getElementById('removeImage')) {
            document.getElementById('removeImage').addEventListener('click', function() {
                document.getElementById('imagePreview').innerHTML = '';
                document.getElementById('formFile').value = '';
            });
        }
    </script>
@endpush
