
@extends('layout')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
@endsection
@section('meta')
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{{ $seo_setting->seo_description }}">
@endsection
@section('public-content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Our Products')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="{{ route('products') }}">{{__('user.Our Products')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->


           <!--=============================
        MENU PAGE START
    ==============================-->
    <section class="tf__menu_page mt_100 xs_mt_70 mb_100 xs_mb_70">
        <div class="container">
            <form class="tf__menu_search_area" action="{{ route('products') }}">
                <div class="row">
                    <div class="col-lg-6 col-md-5">
                        <div class="tf__menu_search">
                            <input type="text" placeholder="{{__('user.Search here...')}}" name="search" value="{{ request()->get('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="tf__menu_search">
                            <div class="select_area">
                                <select class="select_js" name="category">
                                    <option value="">{{__('user.Select category')}}</option>
                                    @if (request()->has('category'))
                                    @foreach ($categories as $category)
                                    <option {{ request()->get('category') == $category->slug ? 'selected' : '' }} value="{{ $category->slug }}">{{ $category->name }}</option>
                                    @endforeach
                                    @else
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                    @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <div class="tf__menu_search">
                            <button class="common_btn" type="submit">{{__('user.search')}}</button>
                        </div>
                    </div>
                </div>
            </form>

            @if ($products->count() == 0)
                <div class="row">
                    <div class="col-12 text-center mt-5">
                        <h3 class="text-danger">{{__('user.Products not found!')}}</h3>
                    </div>
                </div>
            @else
            <div class="row">
                @foreach ($products as $index => $product )
                    <div class="col-xl-3 col-sm-6 col-lg-4 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__menu_item">
                            <div class="tf__menu_item_img">
                                <img src="{{ asset($product->thumb_image) }}" alt="menu" class="img-fluid w-100">
                            </div>
                            <div class="tf__menu_item_text">
                                <a class="category" href="{{ route('products',['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                                <a class="title" href="{{ route('show-product', $product->slug) }}">{{ $product->name }}</a>
                                @php
                                    if ($product->total_review > 0) {
                                        $average = $product->average_rating;

                                        $int_average = intval($average);

                                        $next_value = $int_average + 1;
                                        $review_point = $int_average;
                                        $half_review=false;
                                        if($int_average < $average && $average < $next_value){
                                            $review_point= $int_average + 0.5;
                                            $half_review=true;
                                        }
                                    }
                                @endphp
                                @if ($product->is_offer)
                                        <h5 class="price">{{ $currency_icon }}{{ $product->offer_price }} <del>{{ $currency_icon }}{{ $product->price  }}</del> </h5>
                                    @else
                                        <h5 class="price">{{ $currency_icon }}{{ $product->price }}</h5>
                                    @endif

                                <a class="tf__add_to_cart" href="javascript:;" onclick="load_product_model({{ $product->id }})">{{__('user.add to cart')}}</a>
                                <ul class="d-flex flex-wrap justify-content-end">

                                    @auth('web')
                                        <li><a href="javascript:;" onclick="add_to_wishlist({{ $product->id }})"><i class="fal fa-heart"></i></a></li>
                                        @else
                                        <li><a href="javascript:;" onclick="before_auth_wishlist({{ $product->id }})"><i class="fal fa-heart"></i></a></li>
                                        @endauth

                                    <li><a href="{{ route('show-product', $product->slug) }}"><i class="far fa-eye"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            <div class="tf__pagination mt_50">
                {{ $products->links('custom_paginator') }}
            </div>
        </div>
    </section>



@endsection
