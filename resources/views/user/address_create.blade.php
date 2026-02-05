@extends('layout')
@section('title')
    <title>{{__('user.Create Address')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Address')}}">
@endsection

@section('public-content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Create Address')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="javascript:">{{__('user.Create Address')}}</a></li>
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

                                        <img id="preview-user-avatar" src="{{ asset($personal_info->image) }}"
                                             alt="user" class="img-fluid w-100">
                                    @else
                                        <img id="preview-user-avatar" src="{{ asset($default_user_avatar) }}" alt="user"
                                             class="img-fluid w-100">
                                    @endif

                                    <label for="upload"><i class="far fa-camera"></i></label>
                                    <form id="upload_user_avatar_form" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <input type="file" name="image" id="upload" hidden
                                               onchange="previewThumnailImage(event)">
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
                                <h3>{{__('user.Create Address')}}

                                    <a class="dash_add_new_address"
                                       href="{{ route('address.index') }}">{{__('user.Go Back')}}</a>
                                </h3>

                                <div class="tf_dashboard_address">
                                    <div class="tf_dashboard_new_address ">
                                        <x-address-create-form/>
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
