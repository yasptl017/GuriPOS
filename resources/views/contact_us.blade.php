
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
                    <h1>{{__('user.Contact Us')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="{{ route('contact-us') }}">{{__('user.Contact Us')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->

        <!--=============================
        CONTACT PAGE START
    ==============================-->
    <section class="tf__contact mt_100 xs_mt_70 mb_100 xs_mb_70">
        <div class="container">
            <div class="tf__contact_form_area">
                <div class="row">
                    <div class="col-xl-5 col-md-6 col-lg-5 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__contact_info_area">
                            <div class="tf__contact_info">
                                <h3>{{__('user.call')}}</h3>
                                <p>{!! nl2br($contact->phone) !!}</p>
                            </div>
                            <div class="tf__contact_info">
                                <h3>{{__('user.Email')}}</h3>
                                <p>{!! nl2br($contact->email) !!}</p>
                            </div>
                            <div class="tf__contact_info border-0 p-0 m-0">
                                <h3>{{__('user.Location')}}</h3>
                                <p>{!! nl2br($contact->address) !!}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7 col-md-6 col-lg-7 wow fadeInUp" data-wow-duration="1s">
                        <form class="tf__contact_form" method="POST" action="{{ route('send-contact-us') }}">
                            @csrf
                            <h3>{{__('user.Contact us')}}</h3>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="tf__contact_form_input">
                                        <span><i class="fas fa-user"></i></span>
                                        <input type="text" name="name" placeholder="{{__('user.Name')}}">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="tf__contact_form_input">
                                        <span><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email"  placeholder="{{__('user.Email')}}">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="tf__contact_form_input">
                                        <span><i class="fas fa-phone-alt"></i></span>
                                        <input type="text" name="phone"  placeholder="{{__('user.Phone')}}">
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="tf__contact_form_input">
                                        <span><i class="fas fa-book"></i></span>
                                        <input type="text" placeholder="{{__('user.Subject')}}" name="subject">
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="tf__contact_form_input textarea">
                                        <span><i class="fas fa-pen"></i></span>
                                        <textarea rows="5" placeholder="{{__('user.Message')}}" name="message"></textarea>
                                    </div>
                                </div>

                                @if($recaptcha_setting->status==1)
                                    <div class="col-xl-12">
                                        <div class="tf__contact_form_input">
                                            <div class="g-recaptcha" data-sitekey="{{ $recaptcha_setting->site_key }}"></div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-xl-12">

                                    <button class="common_btn" type="submit">{{__('user.send message')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tf__contact_map_area">
                <div class="row mt_100 xs_mt_70">
                    <div class="col-12 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__contact_map">
                            {!! $contact->map !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        CONTACT PAGE END
    ==============================-->



@endsection
