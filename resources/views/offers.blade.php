@extends('layout')

@section('title')
<title>Reserve Table</title>
@endsection

@section('meta')
<meta name="description" content="Reserve Table">
@endsection

@section('public-content')
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
    :root {
        --primary-color: #e74c3c;
        --secondary-color: #333;
        --background-color: #f8f9fa;
        --text-color: #333;
        --border-color: #e0e0e0;
    }

    /* ... (previous styles remain unchanged) ... */

    /* Updated Coupon Styles */
    .coupon-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        padding: 20px;
    }
    .coupon {
        background: linear-gradient(135deg, #f6f8fa 0%, #ffffff 100%);
        border: 1px solid var(--border-color);
        padding: 30px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .coupon:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    .coupon h3 {
        margin: 10px 0;
        font-size: 24px;
        color: var(--text-color);
        font-weight: bold;
    }
    .coupon .code {
        font-weight: bold;
        color: var(--primary-color);
        font-size: 28px;
        padding: 15px;
        border: 3px dashed var(--primary-color);
        border-radius: 15px;
        display: inline-block;
        margin: 15px 0;
        letter-spacing: 2px;
    }
    .coupon .discount-badge {
        background-color: var(--primary-color);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 24px;  /* Increased from 16px */
        margin-bottom: 15px;
        display: inline-block;
    }
    .coupon .details {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }
    .coupon .details p {
        margin: 10px 0;
        font-size: 16px;
        color: var(--secondary-color);
    }
    .coupon .details strong {
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .tf__section_heading h2 {
            font-size: 28px;
        }
        .coupon {
            padding: 20px;
        }
        .coupon .discount-badge {
            font-size: 22px;
            padding: 10px 7px;
        }
        .coupon .code {
            font-size: 22px;
        }
        .bottom-nav-item i {
            font-size: 20px;
        }
        .bottom-nav-item span {
            font-size: 10px;
        }
    }
    
</style>

<!-- Bottom Navigation Bar -->
<nav class="bottom-nav" aria-label="Main Navigation">
    <a href="{{ route('home') }}" class="bottom-nav-item" aria-label="Menu">
        <i class="fas fa-utensils" aria-hidden="true"></i>
        <span>Menu</span>
    </a>
    <a href="{{ route('reserve-table') }}" class="bottom-nav-item" aria-label="Reservation">
        <i class="far fa-calendar-alt" aria-hidden="true"></i>
        <span>Reservation</span>
    </a>
    <a href="{{ route('offers') }}" class="bottom-nav-item" aria-label="Offers">
        <i class="fas fa-percent" aria-hidden="true"></i>
        <span>Offers</span>
    </a>
</nav>

<!--=============================
        OFFERS SECTION START
    ==============================-->
    <section class="tf__offers mt_120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="tf__section_heading mb_25">
                    <h4>Special Offers</h4>
                    <h2>Stay connected for more exciting deals soon!<h2>
                       
                </div>
            </div>
        </div>
        <!--
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
    OFFER ITEM START
==============================-->
<!--
<section class="tf__offer_item pt_95 pb_100 xs_pt_65 xs_pb_70 mt_130 mb_60">
    <div class="container">
        <div class="row wow fadeInUp" data-wow-duration="1s">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <div class="tf__section_heading mb_25">
                    <h4 class="text-uppercase text-primary">{{__('user.daily offer')}}</h4>
                    <h2 class="display-4 fw-bold">{{__('user.up to 15% off on all orders')}}</h2>
                </div>
            </div>
        </div>
        <div class="coupon-container wow fadeInUp" data-wow-duration="1s">
            @foreach ($coupons as $coupon)
            <div class="coupon">
                <div class="discount-badge">{{ $coupon->discount }}% {{__('user.off')}}</div>
                <h3>{{ $coupon->name }}</h3>
                <p class="code">{{ $coupon->code }}</p>
                <div class="details">
                    <p><strong>Valid until:</strong> {{ $coupon->expired_date }}</p>
                    <p><strong>Minimum Purchase:</strong> ${{ number_format($coupon->min_purchase_price, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
-->

<!--=============================
    OFFER ITEM END
==============================-->
<!-- Bottom Navigation Bar -->
<nav class="bottom-nav">
    <a href="{{ route('home') }}" class="bottom-nav-item">
        <i class="fas fa-utensils"></i>
        <span><strong>Menu</strong></span>
    </a>
    <a href="{{ route('reserve-table') }}" class="bottom-nav-item">
        <i class="far fa-calendar-alt"></i>
        <span><strong>Reservation</strong></span>
    </a>
    <a href="{{ route('offers') }}" class="bottom-nav-item">
        <i class="fas fa-percent"></i>
        <span><strong>Offers</strong></span>
    </a>
</nav>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    var myCollapsible = document.getElementById('menuAccordion')
    myCollapsible.addEventListener('show.bs.collapse', function (event) {
        // Close other open accordions
        var openAccordion = myCollapsible.querySelector('.collapse.show')
        if (openAccordion && openAccordion !== event.target) {
            new bootstrap.Collapse(openAccordion).hide()
        }
    })
})
</script>
@endsection