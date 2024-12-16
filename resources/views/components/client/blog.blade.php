<!-- Blog Section -->
<section class="blog-area blog-area-about padding-top-110 padding-bottom-120 wow fadeInUp">
    <div class="container">
        <div class="common-title-area text-center padding-bottom-60">
            <h3>food khan</h3>
            <h2>Tin tức & <span>bài viết</span></h2>
        </div>
        <div class="row blog-slider-active d-flex flex-wrap">
            @foreach($blogs as $blog)
            <div class="single-blog flex-item col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="blog-img">
                        <a href="blog-single.html">
                            <img src="{{ asset('storage/images/' . $blog->image) }}" alt="" class="img-fluid blog-image" style="position: relative; width: 412px; height: 310px; object-fit: cover;">
                        </a>
                        <div class="b-badge">
                            <span class="date">
                                <a href="#">
                                    {{ $blog->created_at ? $blog->created_at->format('d') : 'N/A' }}
                                </a>
                            </span><br>
                            <span class="month">
                                <a href="#">
                                    {{ $blog->created_at ? $blog->created_at->format('m') : 'N/A' }}
                                </a>
                            </span>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        <div class="blog-meta d-flex justify-content-between">
                            <h5 class="card-title"><a href="{{ route('blog.detail', $blog->id)}}"> {{ $blog->title }}</a></h5>
                            <span><a href="{{ route('blog.detail', $blog->id)}}"><i class="fas fa-user-circle"></i> {{ $blog->user->name }}</a></span>
                        </div>
                        <span class=" blog-title"><a href="{{ route('blog.detail', $blog->id)}}  " class="text-dark">{!! $blog->content !!}</a></span>
                        <a href="" class="read-more">xem thêm</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<style>
    .card-body {
        position: relative;
        overflow: hidden;
    }

    .blog-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 3.1em; /* 1.5em * 2 lines */
        margin: 0;
    }

    .read-more {
        display: none;
        color: #020303;
        text-decoration: underline;
        margin-top: 5px;
        cursor: pointer;
    }

    .card-body.expanded .blog-title {
        height: auto;
        -webkit-line-clamp: unset;
    }

    .card-body.expanded .read-more {
        display: inline-block;
    }

    .card-body .read-more::before {
        content: '...xem thêm';
        display: inline;
    }
</style>

