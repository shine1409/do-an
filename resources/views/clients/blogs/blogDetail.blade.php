@extends('layouts.clients')
@section('title', $blogs->title )
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
                    <h2 class="page-title">Chi tiết bài viết</h2>
                    <ol class="breadcrumb text-center">
                        <li class="breadcrumb-item"><a href="index.html">Trang chủ </a>/<a href="index.html"> Chi tiết bài viết</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Blog Content Section -->
    <section class="blog-content-section blog-details-page padding-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="blog-posts">
                        <div class="blog-single-post">
                            <div class="blog-thumbnail wow fadeInUp">
                                <img src="{{ asset('storage/images/' . $blogs->image) }}" style="position: relative; width: 842px; height: 420px; object-fit: cover;" alt="thumbnail">
                            </div>
                            <div class="blog-content-part">
                                <div class="blog-content-top">
                                    <div class="blog-date margin-right-20">
                                        <a href="#"><i class="fas fa-tag"></i> {{ $blogs->title }}</a>
                                    </div>
                                    <div class="blog-tag margin-right-20">
                                        <a href="#"><i class="fas fa-user-circle"></i>{{ $blogs->user->name }}</a>
                                    </div>
                                </div>
                                {!! $blogs->content !!}
                                <div class="content-bottom margin-top-30">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="content-bottom-tag">
                                                <ul>
                                                    <li>tags:</li>
                                                    <li><a href="#">bbq</a></li>
                                                    <li><a href="#">tikka</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="blog-social-icons">
                                                <ul>
                                                    <li><span>share:</span></li>
                                                    <li><a href="#"><img
                                                                src="{{ asset('assets/client/images/icons/facebook.png') }}"
                                                                alt=""></a>
                                                    </li>
                                                    <li><a href="#"><img
                                                                src="{{ asset('assets/client/images/icons/twitter.png') }}"
                                                                alt=""></a>
                                                    </li>

                                                    <li><a href="#"><img
                                                                src="{{ asset('assets/client/images/icons/inst.png') }}"
                                                                alt=""></a>
                                                    </li>
                                                    <li><a href="#"><img
                                                                src="{{ asset('assets/client/images/icons/linkdin.png') }}"
                                                                alt=""></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- divider -->
                        <div class="divider"></div>
                        
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="blog-sidebar">
                        <div class="single-widget author-widget  wow fadeInUp">
                            <img src="{{ asset('assets/client/images/blog/user.jpg') }}" alt="image">
                            <div class="author-name margin-top-20">
                                <h5>{{ $blogs->user->name }}</h5>
                            </div>
                            <div class="author-content margin-top-10">
                                <p> CHÀO! Tôi là tác giả của bài viết này. Đọc bài viết của tôi, theo xu hướng.</p>
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
                        <div class="single-widget search-widget  wow fadeInUp">
                            <div class="header-search">
                                <form action="#">
                                    <input type="text" placeholder="Search">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>

                        <div class="single-widget product-widget  wow fadeInUp">
                            <h6 class="margin-bottom-10">Sản phẩm mới nhất</h6>
                            @foreach ($allDishes as $dish)
                            <div class="single-product d-flex align-items-center">
                                <div class="dish-img">
                                    <img src="{{ asset('assets/client/images/menu-item/pd6.png' . $dish->image) }}" alt="">
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
                        
                        <div class="single-widget banner-widget  wow fadeInUp">
                            <div class="gallery-img-4">
                                <h5>Siêu Combo
                                    Burger</h5>
                                <a href="shopping-cart.html" class="btn">đặt hàng ngay</a>
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
