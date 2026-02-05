@extends('layout')
@section('title')
<title>Reserve Table</title>
@endsection
@section('meta')
<meta name="description" content="Reserve Table">
<!-- Bottom Navigation Bar -->
<nav class="bottom-nav">
    <a href="{{ route('home') }}" class="bottom-nav-item">
        <i class="fas fa-utensils"></i>
        <span>Menu</span>
    </a>
    <a href="{{ route('reserve-table') }}" class="bottom-nav-item">
        <i class="far fa-calendar-alt"></i>
        <span>Reservation</span>
    </a>
    <a href="{{ route('offers') }}" class="bottom-nav-item">
        <i class="fas fa-percent"></i>
        <span>Offers</span>
    </a>
</nav>
<style>
    /* ... (existing styles) ... */

    /* Offers Section Styles */
    .tf__offers {
        background-color: #f8f9fa;
        padding: 40px 0;
    }

    .tf__section_heading {
        text-align: center;
        margin-bottom: 30px;
    }

    .tf__section_heading h4 {
        color: #ff7c08;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .tf__section_heading h2 {
        font-size: 36px;
        font-weight: 700;
        color: #333;
    }

    .tf__offer_item {
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .tf__offer_item:hover {
        transform: translateY(-5px);
    }

    .tf__offer_item h3 {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }

    .tf__offer_item p {
        font-size: 16px;
        color: var(--colorPrimary);
        font-weight: 700;
    }
</style>
<style>
    .accordion-button {
        background-color: #f8f9fa;
        color: #333;
        font-weight: bold;
    }
    .accordion-button:not(.collapsed) {
        background-color: #e9ecef;
        color: #0056b3;
    }
    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23333'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
    .tf__menu_item {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .tf__menu_item_text {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .tf__add_to_cart {
        margin-top: auto;
    }

    /* Bottom Navigation Styles */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #fff;
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .bottom-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #333;
        text-decoration: none;
    }

    .bottom-nav-item i {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .bottom-nav-item span {
        font-size: 12px;
    }

    /* Adjust main content to account for bottom nav */
    body {
        padding-bottom: 70px;
    }
</style>
@endsection
@section('public-content')


<section class="tf__offers mt_120">
    <div class="container">
     <!--
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="tf__section_heading mb_25">
                    <h4>Special Offers</h4>
                    <h2>Use Coupon: PARADISE10</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="tf__offer_image">
                    <img src="{{ asset('user/images/offer.jpg') }}" alt="Special Offers" class="img-fluid">
                </div>
            </div>
        </div>
        -->
    </div>
</section>

<!--=============================
        BANNER START
    ==============================-->
<!--
<section class="tf__banner">
    <div class="tf__banner_overlay">
        <div class="col-12">
            <div class="tf__banner_slider" style="background: url({{ asset($setting->slider_background) }});">
                <div class="tf__banner_slider_overlay">
                    <div class=" container">
                        <div class="row justify-content-center">
                            <div class="col-xxl-6 col-xl-6 col-md-10 col-lg-6">
                                <div class="tf__banner_text wow fadeInLeft" data-wow-duration="1s">
                                    <h3>{{ $setting->slider_header_one }}</h3>
                                    <h1>{{ $setting->slider_header_two }}</h1>
                                    <p>{{ $setting->slider_description }}</p>
                                   <form action="{{ route('products') }}">
                                        <input type="text" placeholder="{{('user.Type here..')}}" name="search">
                                        <button type="submit" class="common_btn">{{('user.search')}}</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-xxl-5 col-xl-6 col-sm-10 col-md-9 col-lg-6">
                                <div class="tf__banner_img wow fadeInRight" data-wow-duration="1s">
                                    <div class="img">
                                        <img src="{{ asset($setting->slider_offer_image) }}" alt="food item" class="img-fluid w-100">
                                        <span>
                                            {{ $setting->slider_offer_text }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
-->
<!--=============================
        BANNER END
    ==============================-->

    <!--=============================
        RESERVATION START
    ==============================-->
    <section class="tf__reservation mt_100 xs_mt_30" style="margin-bottom:30px;">
        <div class="container">
            <div class="tf__reservation_bg" style="background: url({{ asset($setting->appointment_bg) }});">
                <div class="row">
                    <div class="col-xl-6 ms-auto">
                        <div class="tf__reservation_form wow fadeInRight" data-wow-duration="1s">
                            <h2>Book a Table</h2>
                            <form method="POST" action="{{ route('store-reservation') }}">
                                @csrf
                                @auth('web')
                                    @php
                                        $auth_user = Auth::guard('web')->user();
                                    @endphp
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="tf__reservation_input_single">
                                                <label for="name">Name</label>
                                                <input type="text" id="name" placeholder="Name" name="name" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="tf__reservation_input_single">
                                                <label for="email">Email</label>
                                                <input type="email" id="email" placeholder="Email" name="email" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="tf__reservation_input_single">
                                                <label for="phone">Phone</label>
                                                <input type="text" id="phone" placeholder="Phone" name="phone" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="tf__reservation_input_single">
                                                <label for="date">Select Date</label>
                                                <input type="date" id="date" name="reserve_date" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="tf__reservation_input_single">
                                                <label for="time">Select Time</label>
                                                <input type="time" id="time" name="reserve_time" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="tf__reservation_input_single">
                                                <label for="Person">Person</label>
                                                <input type="number" id="Person" placeholder="Person" name="person" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <button type="submit" class="common_btn">Submit</button>
                                        </div>
                                    </div>
                                @else
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="tf__reservation_input_single">
                                            <label for="name">{{('user.Name')}}</label>
                                            <input type="text" id="name" placeholder="{{('user.Name')}}" name="name">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="tf__reservation_input_single">
                                            <label for="email">{{('user.Email')}}</label>
                                            <input type="email" id="email" placeholder="{{('user.Email')}}" name="email">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="tf__reservation_input_single">
                                            <label for="phone">{{('user.Phone')}}</label>
                                            <input type="text" id="phone" placeholder="{{('user.Phone')}}" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="tf__reservation_input_single">
                                            <label for="date">{{('user.Select date')}}</label>
                                            <input type="date" id="date" name="reserve_date">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="tf__reservation_input_single">
                                            <label for="time">{{('user.Select Time')}}</label>
                                            <input type="time" id="time" name="reserve_time">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="tf__reservation_input_single">
                                            <label for="Person">{{('user.Person')}}</label>
                                            <input type="number" id="Person" placeholder="{{('user.Person')}}" name="person">
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <button type="submit" class="common_btn">Submit</button>
                                    </div>
                                </div>
                                @endauth
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        RESERVATION END
    ==============================-->
@endsection
