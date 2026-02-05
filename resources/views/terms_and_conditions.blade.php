
@extends('layout')
@section('title')
    <title>{{__('user.Terms And Conditions')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Terms And Conditions')}}">
@endsection


@section('public-content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Terms And Conditions')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="{{ route('terms-and-condition') }}">{{__('user.Terms And Conditions')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->


        <!--================================
        PRIVACY POLICY START
    =================================-->
    <section class="tf__terms_condition mt_90 xs_mt_60 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 wow fadeInUp" data-wow-duration="1s">
                    <div class="tf__career_det_text">
                        @if ($terms_conditions)
                            {!! clean($terms_conditions->terms_and_condition) !!}
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!--================================
        PRIVACY POLICY END
    =================================-->

@endsection
