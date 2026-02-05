
@extends('layout')
@section('title')
    <title>{{__('user.Our Chef')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Our Chef')}}">
@endsection

@section('public-content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Our Chef')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="{{ route('our-chef') }}">{{__('user.Our Chef')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->
    <!--=============================
        TEAM PAGE START
    ==============================-->
    <section class="tf__team_page pt_75 xs_pt_45 pb_100 xs_pb_70">
        <div class="container">
            <div class="row">
                @foreach ($our_chefs as $index => $single_chef )
                <div class="col-xxl-3 col-sm-6 col-lg-4 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__single_team">
                            <div class="tf__single_team_img">
                                <img src="{{ asset($single_chef->image) }}" alt="team" class="img-fluid w-100">
                            </div>
                            <div class="tf__single_team_text">
                                <h4>{{ $single_chef->name }}</h4>
                                <p>{{ $single_chef->designation }}</p>
                                <ul class="d-flex flex-wrap">
                                    @if ($single_chef->facebook)
                                        <li><a href="{{ $single_chef->facebook }}"><i class="fab fa-facebook-f"></i></a></li>
                                    @endif

                                    @if ($single_chef->linkedin)
                                        <li><a href="{{ $single_chef->linkedin }}"><i class="fab fa-linkedin-in"></i></a></li>
                                    @endif

                                    @if ($single_chef->twitter)
                                        <li><a href="{{ $single_chef->twitter }}"><i class="fab fa-twitter"></i></a></li>
                                    @endif

                                    @if ($single_chef->instagram)
                                        <li><a href="{{ $single_chef->instagram }}"><i class="fab fa-instagram"></i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <!--=============================
        TEAM PAGE END
    ==============================-->
@endsection
