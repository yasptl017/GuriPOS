
@extends('layout')
@section('title')
    <title>{{__('user.Testimonial')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Testimonial')}}">
@endsection

@section('public-content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Testimonial')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="{{ route('testimonial') }}">{{__('user.Testimonial')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->

        <!--=============================
        TESTIMONIAL PAGE START
    ==============================-->
    <section class="tf__testimonial_page mt_75 xs_mt_45 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">

                @foreach ($testimonials as $index => $single_testimonial)
                <div class="col-xl-6 col-lg-6 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__single_testimonial">
                            <div class="tf__single_testimonial_img">
                                <img src="{{ asset($single_testimonial->image) }}" alt="testimonial" class="img-fluid w-100">
                            </div>
                            <div class="tf__single_testimonial_text">
                                <h4>{{ $single_testimonial->name }}</h4>
                                <p class="designation">{{ $single_testimonial->designation }}</p>
                                <p class="feedback">{{ $single_testimonial->comment }}</p>
                                <span class="rating">
                                    @for ($i = 1; $i <=5 ; $i++)
                                            @if ($i <= $single_testimonial->rating )
                                            <i class="fas fa-star"></i>
                                            @else
                                            <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
    <!--=============================
        TESTIMONIAL PAGE END
    ==============================-->

@endsection
