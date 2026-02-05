
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
                    <h1>{{__('user.About Us')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="{{ route('about-us') }}">{{__('user.About Us')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->


    <!--=============================
        ABOUT PAGE START
    ==============================-->
    <section class="tf__about_us mt_100 xs_mt_70">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 wow fadeInLeft" data-wow-duration="1s">
                    <div class="tf__about_us_img">
                        <div class="img">
                            <img src="{{ asset($about_us->about_us_image) }}" alt="about us" class="img-fluid w-100">
                        </div>
                        <h3>{{ $about_us->experience_year }} <span>{{ $about_us->experience_text }}</span></h3>
                        <p>{{ $about_us->author_comment }}
                            <span>{{ $about_us->author_name }}</span>
                        </p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 wow fadeInRight" data-wow-duration="1s">
                    <div class="tf__section_heading mb_25">
                        <h4>{{ $about_us->about_us_short_title }}</h4>
                        <h2>{{ $about_us->about_us_long_title }}</h2>
                    </div>
                    <div class="tf__about_us_text">
                        <p>{{ $about_us->about_us }}</p>
                        <ul>
                            <li>
                                <h4>{{ $about_us->item1_title }}</h4>
                                <p>{{ $about_us->item1_description }}</p>
                            </li>

                            <li>
                                <h4>{{ $about_us->item2_title }}</h4>
                                <p>{{ $about_us->item2_description }}</p>
                            </li>

                            <li>
                                <h4>{{ $about_us->item3_title }}</h4>
                                <p>{{ $about_us->item3_description }}</p>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tf__mission mt_100 xs_mt_70" style="background: url({{ asset($about_us->vision_bg) }});">
        <div class="tf__mission_overlay pt_70 xs_pt_40 pb_100 xs_pb_70">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-md-10 col-lg-7">
                        <div class="tf__mission_text">
                            <ul>
                                <li>
                                    <div class="icon">
                                        <i class="far fa-bullseye-arrow"></i>
                                    </div>
                                    <div class="text">
                                        <h4>{{ $about_us->vision_title }}</h4>
                                        <p>{{ $about_us->vision_description }}</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <i class="fas fa-lightbulb-on"></i>
                                    </div>
                                    <div class="text">
                                        <h4>{{ $about_us->mission_title }}</h4>
                                        <p>{{ $about_us->mission_description }}</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <i class="far fa-gem"></i>
                                    </div>
                                    <div class="text">
                                        <h4>{{ $about_us->goal_title }}</h4>
                                        <p>{{ $about_us->goal_description }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tf__about_choose mt_100 xs_mt_70">
        <div class="container">
            <div class="row">
                <div class="col-xxl-8 col-lg-7 wow fadeInLeft" data-wow-duration="1s">
                    <div class="tf__section_heading mb_25">
                        <h4>{{ $about_us->why_choose_us_short_title }}</h4>
                        <h2>{{ $about_us->why_choose_us_long_title }}</h2>
                    </div>
                    <div class="tf__about_choose_text">
                        <p>{{ $about_us->why_choose_us_description }}</p>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="tf__about_choose_text_box">
                                    <span><i class="fas fa-burger-soda"></i></span>
                                    <h4>{{ $about_us->title_one }}</h4>
                                    <p>{{ $about_us->description_one }}</p>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="tf__about_choose_text_box">
                                    <span><i class="fal fa-truck"></i></span>
                                    <h4>{{ $about_us->title_two }}</h4>
                                    <p>{{ $about_us->description_two }}</p>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="tf__about_choose_text_box">
                                    <span><i class="fas fa-file-certificate"></i></span>
                                    <h4>{{ $about_us->title_three }}</h4>
                                    <p>{{ $about_us->description_three }}</p>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="tf__about_choose_text_box">
                                    <span><i class="fas fa-headset"></i></span>
                                    <h4>{{ $about_us->title_four }}</h4>
                                    <p>{{ $about_us->description_four }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-4 col-lg-5 wow fadeInRight" data-wow-duration="1s">
                    <div class="tf__about_choose_img">
                        <img src="{{ asset($about_us->why_choose_us_background) }}" alt="about us" class="img-fluid w-100">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tf__counter mt_100 xs_mt_70" style="background: url({{ asset($counter->background_image) }});">
        <div class="tf__counter_overlay pt_120 xs_pt_90 pb_100 xs_pb_0">
            <div class="container">
                <div class="row">
                    @foreach ($counter->counters as $index => $single_counter )
                        <div class="col-xl-3 col-sm-6 col-lg-3 wow fadeInUp" data-wow-duration="1s">
                            <div class="tf__single_counter">
                                <div class="text">
                                    <h2 class="counter">{{ $single_counter->quantity }}</h2>
                                    <span><i class="{{ $single_counter->icon }}"></i></span>
                                </div>
                                <p>{{ $single_counter->title }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="tf__testimonial pt_95 xs_pt_65 mb_100 xs_mb_70">
        <div class="container">
            <div class="row wow fadeInUp" data-wow-duration="1s">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <div class="tf__section_heading mb_20">
                        <h4>{{__('user.testimonial')}}</h4>
                        <h2>{{__('user.our customar feedbacks')}}</h2>
                    </div>
                </div>
            </div>

            <div class="row testi_slider">
                @foreach ($testimonial->testimonials as $index => $single_testimonial)
                    <div class="col-xl-6 wow fadeInUp" data-wow-duration="1s">
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
        ABOUT PAGE END
    ==============================-->

@endsection
