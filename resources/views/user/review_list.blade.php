@extends('layout')
@section('title')
    <title>{{__('user.Review List')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Review List')}}">
@endsection

@section('public-content')


    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Review List')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="javascript:;">{{__('user.Review List')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->


        <!--=========================
        DASHBOARD START
    ==========================-->
    <section class="tf__dashboard mt_120 xs_mt_90 mb_100 xs_mb_70">
        <div class="container">
            <div class="tf__dashboard_area">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__dashboard_menu">
                            <div class="dasboard_header">
                                <div class="dasboard_header_img">

                                    @if ($personal_info->image)

                                    <img id="preview-user-avatar" src="{{ asset($personal_info->image) }}" alt="user" class="img-fluid w-100">
                                    @else
                                    <img id="preview-user-avatar" src="{{ asset($default_user_avatar) }}" alt="user" class="img-fluid w-100">
                                    @endif

                                    <label for="upload"><i class="far fa-camera"></i></label>
                                    <form id="upload_user_avatar_form" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <input type="file" name="image" id="upload" hidden onchange="previewThumnailImage(event)">
                                    </form>
                                </div>
                                <h2>{{ html_decode($personal_info->name) }}</h2>
                            </div>

                            @include('user.sidebar')

                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-8 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__dashboard_content">
                            <div class="tf_dashboard_body dashboard_review">
                                <h3>{{__('user.Review List')}}</h3>
                                <div class="tf__review_area">
                                    <div class="tf__comment pt-0 mt_20">
                                        @foreach ($reviews as $index => $review)
                                            <div class="tf__single_comment {{ $index == 0 ? 'm-0 border-0' : '' }} ">
                                                <img src="{{ asset($review->product->thumb_image) }}" alt="review" class="img-fluid">
                                                <div class="tf__single_comm_text">
                                                    <h3><a href="{{ route('show-product', $review->product->slug) }}">{{ $review->product->name }}</a> <span>{{ $review->created_at->format('d M Y') }} </span>
                                                    </h3>
                                                    <span class="rating">
                                                        @for ($i = 1; $i <=5; $i++)
                                                            @if ($i <= $review->rating)
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="fal fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </span>
                                                    <p>{{ $review->review }}</p>
                                                    @if ($review->status == 1)
                                                    <span class="status active">{{__('user.active')}}</span>
                                                    @else
                                                    <span class="status inactive">{{__('user.inactive')}}</span>
                                                    @endif

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
