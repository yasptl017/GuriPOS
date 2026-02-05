@extends('layout')
@section('title')
    <title>{{__('user.Edit Profile')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Edit Profile')}}">
@endsection

@section('public-content')


    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Edit Profile')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="{{ route('dashboard') }}">{{__('user.My Profile')}}</a></li>
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
                            <div class="tf_dashboard_body">
                                <h3>{{__('user.Edit Profile')}}<a class="dash_add_new_address" href="{{ route('dashboard') }}">{{__('user.cancel')}}</a>
                                </h3>

                                <div class="tf_dash_personal_info">
                                    <div class="tf_dash_personal_info_edit comment_input p-0 mb_0">
                                        <form action="{{ route('update-profile') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="tf__comment_imput_single">
                                                        <label>{{__('user.Name')}}</label>
                                                        <input type="text" name="name" value="{{ html_decode($personal_info->name) }}" >
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6">
                                                    <div class="tf__comment_imput_single">
                                                        <label>{{__('user.Email')}}</label>
                                                        <input type="email" name="email" value="{{ html_decode($personal_info->email) }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6">
                                                    <div class="tf__comment_imput_single">
                                                        <label>{{__('user.Phone')}}</label>
                                                                <input type="text" placeholder="{{__('user.Phone')}}" name="phone" value="{{ html_decode($personal_info->phone) }}">
                                                    </div>
                                                </div>
                                                <div class="col-xl-12">
                                                <!--    
                                                <div class="tf__comment_imput_single">
                                                        <label>{{__('user.Address')}}</label>

                                                        <input type="text" placeholder="{{__('user.Address')}}" name="address" value="{{ html_decode($personal_info->address) }}">
                                                    </div>
-->
                                                    <button type="submit" class="common_btn">{{__('user.Update')}}</button>
                                                </div>
                                            </div>
                                        </form>
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
