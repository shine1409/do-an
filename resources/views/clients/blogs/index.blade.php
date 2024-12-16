@extends('layouts.clients')
@section('title','Bài viết')
@section('content')
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
                <nav aria-label="breadcrumb text-center">
                    <h2 class="page-title">Bài viết</h2>
                    <ol class="breadcrumb text-center">
                        <li class="breadcrumb-item"><a href="/">Trang chủ</a> / <a href="{{ route('blog') }}"> Bài
                                viết</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Blog Content Section -->
    <section class="blog-content-section padding-top-110 padding-bottom-120">
        <div class="blog-shapes">
            <span class="bbs-1"><img src="{{ asset('assets/client/images/shapes/13.png') }}" alt=""></span>
            <span class="bbs-2 item-bounce"><img src="{{ asset('assets/client/images/shapes/12.png') }}"
                    alt=""></span>
            <span class="bbs-3"><img src="{{ asset('assets/client/images/shapes/7.png') }}" alt=""></span>
            <span class="bbs-4 item-bounce"><img src="{{ asset('assets/client/images/shapes/26.png') }}"
                    alt=""></span>
            <span class="bbs-5 item-animateTwo"><img src="{{ asset('assets/client/images/shapes/26.png') }}"
                    alt=""></span>
            <span class="bbs-6"><img src="{{ asset('assets/client/images/shapes/16.png') }}" alt=""></span>
            <span class="bbs-7"><img src="{{ asset('assets/client/images/img/32.png') }}" alt=""></span>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="blog-posts">
                        @foreach($blogs as $blog)
                        <div class="blog-single-post wow fadeInUp">
                            <div class="blog-thumbnail">
                                <img src="{{ asset('storage/images/' . $blog->image) }}"
                                        alt="thumbnail" style="position: relative; width: 842px; height: 410px; object-fit: cover;">
                                <div class="meta-date">
                                    <span class="bold">{{ $blog->created_at ? $blog->created_at->format('d') : 'N/A' }}</span>
                                    <span>{{ $blog->created_at ? $blog->created_at->format('M') : 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="blog-content-part">
                                <div class="blog-content-top">
                                    <div class="blog-date margin-right-20">
                                        <a href="{{ route('blog.detail', $blog->id)}}"><i class="fas fa-tag"></i> {{ $blog->title }}</a>
                                    </div>
                                    <div class="blog-tag margin-right-20">
                                        <a href="{{ route('blog.detail', $blog->id)}}"> <i class="fas fa-user-circle"></i> {{ $blog->user->name }}</a>
                                    </div>
                                </div>
                                <div class="blog-title">
                                    <h4><a href="{{ route('blog.detail', $blog->id)}}"></a></h4>
                                </div>
                                <div class="blog-content">
                                    {!! $blog->content !!}
                                </div>
                                <div class="blog-excerpt-link">
                                    <a href="{{ route('blog.detail', $blog->id)}}" class="btn">đọc thêm</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        {{ $blogs->links() }}
                    </div>
                    
                </div>
                <div class="col-lg-3">
                    <div class="blog-sidebar margin-top-10">
                        <div class="single-widget wow fadeInUp author-widget">
                            <img src="{{ asset('assets/client/images/blog/user.jpg') }}" alt="image">
                            <div class="author-name margin-top-20">
                                <h5>Miyako Prince</h5>
                            </div>
                            <div class="author-content margin-top-10">
                                <p>CHÀO! Tôi là tác giả của bài viết này. Đọc bài viết của tôi, theo xu hướng.</p>
                            </div>
                            <div class="author-social-link margin-top-20">
                                <ul>
                                    <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="single-widget search-widget  wow fadeInUp ">
                            <div class="header-search">
                                <form action="#">
                                    <input type="text" placeholder="Search">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>

                        <div class="single-widget product-widget  wow fadeInUp ">
                            <h6 class="margin-bottom-10">Sản phẩm bán chạy</h6>
                            @foreach ($allDishes as $dish)
                            <div class="single-product d-flex align-items-center">
                                <div class="dish-img">
                                    <a href="single-dish.html"><img src="{{ asset('assets/client/images/menu-item/pd6.png' . $dish->image) }}" alt=""></a>
                                </div>
                                <div class="dish-content">
                                    <h6> <a href="{{ route('dishDetail', $dish->id) }}">{{ $dish->name }}</a></h6>
                                        <span class="price"> {{ number_format($dish->price, 0, ',', '.') }} VNĐ</span>
                                    <span>
                                        <a class="cart-select" href="shopping-cart.html"><i
                                                class="fas fa-shopping-basket"></i></a>
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                       
                        <div class="single-widget banner-widget  wow fadeInUp ">
                            <div class="gallery-img-4">
                                <h5>Super Combo
                                    Burger</h5>
                                <a href="{{ route('cart') }}" class="btn">đặt hàng ngay bây giờ</a>
                                <img src="{{ asset('assets/client/images/gallery/22.png') }}" alt="">
                                <span class="gprice-4"><img src="{{ asset('assets/client/images/gallery/25.png') }}"
                                        alt=""></span>
                                <span class="gshape-1"><img src="{{ asset('assets/client/images/shapes/35.png') }}"
                                        alt=""></span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
    .blog-content {
    max-height: 5.6rem; /* Giới hạn chiều cao tối đa */
    overflow: hidden; /* Ẩn phần nội dung vượt quá */
    text-overflow: ellipsis; /* Thêm dấu chấm ba chấm */
    display: -webkit-box;
    -webkit-line-clamp: 2; /* Số dòng hiển thị tối đa */
    -webkit-box-orient: vertical;
}
</style>
