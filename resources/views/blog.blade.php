
@extends('layout')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
@endsection
@section('meta')
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
                    <h1>{{__('user.Our Blogs')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="{{ route('blogs') }}">{{__('user.Our Blogs')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->


        <!--=============================
        BLOG PAGE START
    ==============================-->
    <section class="tf__blog_page mt_75 xs_mt_45 mb_100 xs_mb_70">
        <div class="container">

            @if ($blogs->count() == 0)
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="text-danger text-center">{{__('user.Blog Not Found')}}</h2>
                    </div>
                </div>
            @else
                <div class="row">
                    @foreach ($blogs as $index => $single_blog)
                        <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1s">
                            <div class="tf__single_blog">
                                <div class="tf__single_blog_img">
                                    <img src="{{ asset($single_blog->image) }}" alt="author" class="img-fluid w-100">
                                </div>
                                <div class="tf__single_blog_author">
                                    <div class="img">
                                        <img src="{{ asset($single_blog->admin->image) }}" alt="author" class="img-fluid w-100">
                                    </div>
                                    <div class="text">
                                        <h5>{{ $single_blog->admin->name }}</h5>
                                        <p>{{ $single_blog->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="tf__single_blog_text">
                                    <a class="category" href="{{ route('blogs', ['category' => $single_blog->category->slug ]) }}">{{ $single_blog->category->name }}</a>
                                    <a class="title" href="{{ route('show-blog', $single_blog->slug) }}">{{ $single_blog->title }}</a>
                                    <p>{{ $single_blog->short_description }}</p>
                                    <div class="tf__single_blog_footer">
                                        <a class="read_btn" href="{{ route('show-blog', $single_blog->slug) }}">{{__('user.read more')}} <i
                                                class="far fa-long-arrow-right"></i></a>
                                        <span><i class="far fa-comments"></i> {{ $single_blog->total_comment }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="tf__pagination mt_50">
                {{ $blogs->links('custom_paginator') }}
            </div>
        </div>
    </section>
    <!--=============================
        BLOG PAGE END
    ==============================-->



@endsection
