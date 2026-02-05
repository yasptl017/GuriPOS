@extends('layout')

@section('title')
<title>Location - Punjabi Paradise Indian Restaurant</title>
@endsection

@section('meta')
<meta name="description" content="Find the location and contact details for Punjabi Paradise Indian Restaurant in Penrith, NSW.">
@endsection

@section('public-content')
<style>
    :root {
        --primary-color: #ff7c08;
        --secondary-color: #333;
        --background-color: #f8f9fa;
        --text-color: #333;
        --border-color: #e0e0e0;
    }

    .tf__location {
        background-color: var(--background-color);
        padding: 60px 0;
    }

    .tf__section_heading {
        text-align: center;
        margin-bottom: 40px;
    }

    .tf__section_heading h4 {
        color: var(--primary-color);
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .tf__section_heading h2 {
        font-size: 40px;
        font-weight: 700;
        color: var(--secondary-color);
    }

    .tf__location_item {
        background-color: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 30px;
    }

    .tf__location_item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .tf__location_item h3 {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 20px;
    }

    .tf__location_item p {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 10px;
    }

    .tf__location_item i {
        color: var(--primary-color);
        margin-right: 10px;
        font-size: 20px;
    }

    .map-container {
        width: 100%;
        height: 450px;
        border-radius: 15px;
        overflow: hidden;
        margin-top: 20px;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        padding: 10px 20px;
        font-size: 18px;
        font-weight: 600;
        border-radius: 30px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #e66f00;
        border-color: #e66f00;
        transform: translateY(-2px);
    }

    .phone-link {
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.3s ease;
        font-weight: 700;
    }

    .phone-link:hover {
        color: #e66f00;
    }

    .phone-button {
        display: inline-block;
        background-color: var(--primary-color);
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .phone-button:hover {
        background-color: #e66f00;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .tf__section_heading h2 {
            font-size: 32px;
        }
        .tf__location_item {
            padding: 20px;
        }
        .tf__location_item h3 {
            font-size: 24px;
        }
        .tf__location_item p {
            font-size: 18px;
            font-weight: 700;
        }
    }
</style>

<section class="tf__location mt_120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="tf__section_heading mb_40">
                    <h4>Our Location</h4>
                    <h2>Find Your Way to Flavor</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="tf__location_item">
                    <h3><i class="fas fa-map-marker-alt"></i> Address</h3>
                    <p>419 High St, Penrith NSW 2750, Australia</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="tf__location_item">
                    <h3><i class="fas fa-phone-alt"></i> Contact</h3>
                    <p>Phone: <a href="tel:0247076700" class="phone-link">0247076700</a></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="tf__location_item">
                    <h3><i class="far fa-clock"></i> Opening Hours</h3>
                    <p>Open daily: 5:00 PM - 10:00 PM</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="tf__location_item">
                    <h3><i class="fas fa-map"></i> Find Us on the Map</h3>
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3312.5668133563384!2d151.0508883!3d-33.8740555!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b12bb2b2b2b2b2b%3A0x2b2b2b2b2b2b2b2b!2sPunjabi%20Paradise%20Indian%20Restaurant!5e0!3m2!1sen!2sau!4v1628500000000!5m2!1sen!2sau"
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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