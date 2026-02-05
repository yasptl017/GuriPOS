@extends('layout')
@section('title')
    <title>{{__('user.Change Password')}}</title>
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
                    <h1>{{__('user.Change Password')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="{{ route('change-password') }}">{{__('user.Change Password')}}</a></li>
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
                                <h3>{{__('user.Change Password')}}
                                </h3>

                                <div class="tf_dash_personal_info">
                                    <div class="tf_dash_personal_info_edit comment_input p-0 mb_0">
                                        <form action="{{ route('update-password') }}" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="tf__comment_imput_single">
                                                        <label>{{__('user.Current Password')}}</label>
                                                        <input type="password" placeholder="{{__('user.Current Password')}}" name="current_password">
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="tf__comment_imput_single">
                                                        <label>{{__('user.New Password')}}</label>
                                                        <input type="password" placeholder="{{__('user.New Password')}}" name="password">
                                                    </div>
                                                </div>
                                                <div class="col-xl-12">
                                                    <div class="tf__comment_imput_single">
                                                        <label>{{__('user.Confirm Password')}}</label>
                                                        <input type="password" name="password_confirmation" placeholder="{{__('user.Confirm Password')}}">
                                                    </div>
                                                    <button type="submit"
                                                        class="common_btn mt_20">{{__('user.Change Password')}}</button>
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
