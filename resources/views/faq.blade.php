
@extends('layout')
@section('title')
    <title>{{__('user.FAQ')}}</title>
@endsection
@section('meta')
    <meta name="description" content="cart">
@endsection

@section('public-content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.FAQ')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="{{ route('faq') }}">{{__('user.FAQ')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->

        <!--=============================
        FAQ PAGE START
    ==============================-->
    <section class="tf__faq pt_75 xs_pt_45 pb_100 xs_pb_70">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-7 col-md-8 col-lg-7 wow fadeInLeft" data-wow-duration="1s">
                    <div class="tf__faq_area">
                        <div class="accordion" id="accordionExample">
                            @foreach ($faqs as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne-{{ $faq->id }}">
                                    <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne-{{ $faq->id }}" aria-expanded="true" aria-controls="collapseOne-{{ $faq->id }}">
                                        {{ ++ $index }}. {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapseOne-{{ $faq->id }}" class="accordion-collapse collapse {{ $index == 1 ? 'show' : '' }}"
                                    aria-labelledby="headingOne-{{ $faq->id }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        {!! clean($faq->answer) !!}
                                    </div>
                                </div>
                            </div>

                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-md-8 col-lg-5 wow fadeInRight" data-wow-duration="1s">
                    <div class="tf__faq_area_img">
                        <!-- <img src="{{ asset('user/images/faq_img.jpg') }}" alt="faq" class="img-fluid w-100"> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        FAQ PAGE END
    ==============================-->

@endsection
