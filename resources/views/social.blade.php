@extends('layout')

@section('title')
<title>Follow Us</title>
@endsection

@section('meta')
<meta name="description" content="Follow Us on Social Media and Leave a Review">
@endsection

@section('public-content')
<style>
    :root {
        --primary-color: #e74c3c;
        --secondary-color: #333;
        --background-color: #f8f9fa;
        --text-color: #333;
        --border-color: #e0e0e0;
    }

    .tf__followus {
        background-color: var(--background-color);
        padding: 60px 0;
    }

    .tf__section_heading {
        text-align: center;
        margin-bottom: 40px;
    }

    .tf__section_heading h4 {
        color: var(--primary-color);
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .tf__section_heading h2 {
        font-size: 36px;
        font-weight: 700;
        color: var(--secondary-color);
    }

    .social-media-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 30px;
    }

    .social-media-item {
        background-color: #fff;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        width: 250px;
    }

    .social-media-item:hover {
        transform: translateY(-10px);
    }

    .social-media-item i {
        font-size: 48px;
        margin-bottom: 20px;
    }

    .social-media-item h3 {
        font-size: 24px;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 10px;
    }

    .social-media-item p {
        font-size: 16px;
        color: var(--text-color);
        margin-bottom: 20px;
    }

    .social-media-item a {
        display: inline-block;
        padding: 10px 15px;
        background-color: var(--primary-color);
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    .social-media-item a:hover {
        background-color: #c0392b;
    }

    .fa-facebook { color: #3b5998; }
    .fa-instagram { color: #e1306c; }
    .fa-tiktok { color: #000000; }
    .fa-google { color: #4285F4; }

    @media (max-width: 768px) {
        .tf__section_heading h2 {
            font-size: 28px;
        }
        .social-media-item {
            width: 100%;
            max-width: 300px;
        }
    }
</style>

<section class="tf__followus">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="tf__section_heading mb_25">
                    <h4>Connect With Us</h4>
                    <h2>Follow Us and Leave a Review</h2>
                </div>
            </div>
        </div>
        <div class="social-media-container">
            <div class="social-media-item">
                <i class="fab fa-facebook"></i>
                <h3>Facebook</h3>
                <p>Stay updated with our latest posts and events</p>
                <a href="https://m.facebook.com/61557600539648/" target="_blank">Follow on Facebook</a>
            </div>
            <div class="social-media-item">
                <i class="fab fa-instagram"></i>
                <h3>Instagram</h3>
                <p>Explore our delicious food gallery</p>
                <a href="http://www.facebook.com/profile.php?id=61557600539648&mibextid=kFxxJD" target="_blank">Follow on Instagram</a>
            </div>
            <div class="social-media-item">
                <i class="fab fa-tiktok"></i>
                <h3>TikTok</h3>
                <p>Watch our fun and engaging short videos</p>
                <a href="https://www.tiktok.com/@punjabiparadise2750?_t=8o13WhNSohc&_r=1" target="_blank">Follow on TikTok</a>
            </div>
            <div class="social-media-item">
                <i class="fab fa-google"></i>
                <h3>Google Review</h3>
                <p>Your review helps us grow!</p>
                <a href="https://g.page/r/CdzqlkF4siVAEBE/review" target="_blank">Leave a Review</a>
            </div>
        </div>
    </div>
</section>

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