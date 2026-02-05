
@extends('layout')
@section('title')
    <title>{{__('Update Phone Number')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Register')}}">
@endsection

@section('public-content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('Update Phone Number')}}</h1>

                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->

    <!--=========================
SIGNIN START
==========================-->
    <section class="tf__signin pt_100 xs_pt_70 pb_100 xs_pb_70">
        <div class="container">
            <div class="row justify-content-center wow fadeInUp" data-wow-duration="1s">
                <div class="col-xl-5 col-sm-10 col-md-8 col-lg-6">
                    <div class="tf__login_area">
                        <h2>{{__('Update Your Phone Number')}}</h2>
                        <form action="{{ route('auth.store-phone') }}" method="POST">
                            @csrf
                            <div class="row">


                                <div class="col-xl-12">
                                    <div class="tf__login_imput">
                                        <input type="number" name="phone" placeholder="{{__('user.Phone')}}">
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="tf__login_imput">
                                        <button type="submit" class="common_btn">{{__('Update Phone')}}</button>
                                    </div>
                                </div>



                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=========================
        SIGNIN END
    ==========================-->

    <script src="https://apis.google.com/js/platform.js" async defer></script>



@endsection
