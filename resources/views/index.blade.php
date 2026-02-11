@extends('layout')
@section('title')
<title>{{ $seo_setting->seo_title }}</title>
@endsection
@section('meta')
<meta name="description" content="{{ $seo_setting->seo_description }}">
<style>
    .modal-overlay {
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    .modal-container {
        margin-top: -10vh;
        max-height: 90vh;
    }
    
    .modal-close:hover {
        opacity: 0.75;
    }

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

    .tf__offer_image {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .tf__offer_image:hover {
        transform: translateY(-5px);
    }

    .tf__offer_image img {
        width: 100%;
        height: auto;
        max-width: 800px;
        max-height: 800px;
        object-fit: cover;
        display: block;
        margin: 0 auto;
    }

    @media (max-width: 768px) {
        .tf__section_heading h2 {
            font-size: 28px;
        }
        
        .tf__offers {
            padding: 30px 0;
        }
    }

    /* Closure Notice Modal Styles */
    .closure-notice-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.85);
    }

    .closure-notice-content {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        padding: 40px;
        border-radius: 20px;
        max-width: 550px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        text-align: center;
        border: 3px solid #dc2626;
        animation: fadeInScale 0.4s ease-out;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .closure-notice-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 25px;
        background: #dc2626;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    }

    .closure-notice-icon svg {
        width: 45px;
        height: 45px;
        fill: #fff;
    }

    .closure-notice-title {
        font-size: 32px;
        font-weight: 800;
        color: #dc2626;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .closure-notice-subtitle {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 25px;
    }

    .closure-notice-message {
        font-size: 16px;
        color: #555;
        line-height: 1.8;
        margin-bottom: 12px;
    }

    .closure-notice-highlight {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 15px 20px;
        margin: 25px 0;
        border-radius: 8px;
        font-size: 15px;
        color: #78350f;
        font-weight: 600;
    }

    .closure-notice-footer {
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #e5e7eb;
        font-size: 14px;
        color: #666;
        font-style: italic;
    }

    .menu-disabled {
        pointer-events: none;
        opacity: 0.3;
        user-select: none;
    }
</style>
@endsection
@section('public-content')

@php
    // Set this variable to true to show closure notice, false to hide
    $showClosureNotice = false;
@endphp

@if($showClosureNotice)
<!-- Closure Notice Modal -->
<div class="closure-notice-modal">
    <div class="closure-notice-content">
        <div class="closure-notice-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
        </div>
        <h2 class="closure-notice-title">Temporarily Closed</h2>
        <h3 class="closure-notice-subtitle">Power Outage Update</h3>
        <p class="closure-notice-message">
            Due to a storm-related power outage affecting the entire street, <strong>Punjabi Paradise is currently closed</strong>.
        </p>
        <div class="closure-notice-highlight">
            We are waiting for power to be restored and will open as soon as electricity is back.
        </div>
        <p class="closure-notice-message">
            Thank you for your patience and understanding.
        </p>
        <div class="closure-notice-footer">
            — Punjabi Paradise Management
        </div>
    </div>
</div>
@endif

<!--=============================
        OFFERS SECTION START
    ==============================-->

    <section class="tf__offers mt_70">
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
        -->
        <!--
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="tf__offer_image">
                    <img src="{{ asset('user/images/offer.jpg') }}" alt="Special Offers" class="img-fluid">
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="tf__offer_image">
                    <img src="{{ asset('user/images/31.jpg') }}" alt="Special Offers" class="img-fluid">
                </div>
            </div>
        </div>
        -->
    
    </div>
</section>
<!--=============================
        OFFERS SECTION END
    ==============================-->
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
        MENU ITEM START
    ==============================-->
<section class="tf__menu mt_25 xs_mt_25 @if($showClosureNotice) menu-disabled @endif">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="menu-search-bar">
                    <div class="menu-search-input">
                        <i class="fas fa-search"></i>
                        <input type="search" id="menuSearchInput" placeholder="Search menu items..." autocomplete="off">
                        <button type="button" id="menuSearchClear" aria-label="Clear search">Clear</button>
                    </div>
                    <div id="menuSearchSummary" class="menu-search-summary"></div>
                    <div id="menuSearchEmpty" class="menu-search-empty d-none">No matching items found.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="accordion" id="menuAccordion">
                    @foreach ($menu_section->categories as $menu_category)
                    <div class="accordion-item menu-category" data-category-id="{{ $menu_category->id }}">
                        <h2 class="accordion-header" id="heading{{ $menu_category->id }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $menu_category->id }}" aria-expanded="false" aria-controls="collapse{{ $menu_category->id }}">
                                {{ $menu_category->name }}
                            </button>
                        </h2>
                        <div id="collapse{{ $menu_category->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $menu_category->id }}" data-bs-parent="#menuAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    @foreach ($menu_section->products as $menu_product)
                                    @if ($menu_product->category_id == $menu_category->id)
                                    @php
                                        $is_in_cart = in_array($menu_product->id, $cart_product_ids ?? []);
                                    @endphp
                                    <div class="col-xxl-3 col-sm-6 col-lg-4 mb-4 menu-product" data-category-id="{{ $menu_category->id }}">
                                        <div class="tf__menu_item menu-product-card {{ $is_in_cart ? 'is-in-cart' : '' }}"
                                             role="button"
                                             tabindex="0"
                                             data-product-id="{{ $menu_product->id }}"
                                             data-category-id="{{ $menu_category->id }}"
                                             data-search="{{ strtolower($menu_product->name . ' ' . $menu_product->short_description . ' ' . $menu_product->category->name) }}">
                                            <div class="tf__menu_item_text">
                                                <label class="title" href="{{ route('show-product', $menu_product->slug) }}">{{ $menu_product->name }}</label>
                                                <hr>
                                                <label style="font-weight: 600;">{{$menu_product->short_description}}</label>
                                                
                                                @if ($menu_product->is_offer)
                                                <h5 class="price">{{ $currency_icon }}{{ $menu_product->offer_price }} <del>{{ $currency_icon }}{{ $menu_product->price }}</del> </h5>
                                                @else
                                                <h5 class="price">{{ $currency_icon }}{{ $menu_product->price }}</h5>
                                                @endif
                                            </div>
                                            @if ($is_in_cart)
                                                <span class="in-cart-badge">In Cart</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Operating Hours Modal -->
<div id="operatingHoursModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1050;">
    <!-- Modal Overlay -->
    <div class="hours-modal-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5);"></div>
    
    <!-- Modal Content -->
    <div class="hours-modal-container" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; max-width: 400px; width: 90%; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); z-index: 1051;">
        <!-- Modal Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 24px; font-weight: bold; margin: 0;">Operating Hours</h3>
            <button class="modal-close" style="background: none; border: none; cursor: pointer; padding: 5px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div>
            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 0; font-weight: 600;">Monday</td>
                        <td style="padding: 12px 0; text-align: right;">5:00 PM - 10:00 PM</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 0; font-weight: 600;">Tuesday</td>
                        <td style="padding: 12px 0; text-align: right; color: #dc2626;">Closed</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 0; font-weight: 600;">Wednesday</td>
                        <td style="padding: 12px 0; text-align: right;">5:00 PM - 10:00 PM</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 0; font-weight: 600;">Thursday</td>
                        <td style="padding: 12px 0; text-align: right;">5:00 PM - 10:00 PM</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 0; font-weight: 600;">Friday</td>
                        <td style="padding: 12px 0; text-align: right;">5:00 PM - 10:00 PM</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 0; font-weight: 600;">Saturday</td>
                        <td style="padding: 12px 0; text-align: right;">5:00 PM - 10:00 PM</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; font-weight: 600;">Sunday</td>
                        <td style="padding: 12px 0; text-align: right;">5:00 PM - 10:00 PM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--=============================
        MENU ITEM END
    ==============================-->

<!-- Bottom Navigation Bar -->
<nav class="bottom-nav @if($showClosureNotice) menu-disabled @endif">
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
        background: #ffffff;
        border: 1px solid #eef0f2;
        border-radius: 14px;
        padding: 10px 12px;
        box-shadow: 0 6px 18px rgba(16, 24, 40, 0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        cursor: pointer;
        position: relative;
    }
    .tf__menu_item_text {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .tf__menu_item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(16, 24, 40, 0.12);
        border-color: #d9dee4;
    }
    .tf__menu_item_text .title {
        margin-bottom: 6px;
    }
    .tf__menu_item_text hr {
        margin: 6px 0;
    }
    .tf__menu_item_text label {
        margin-bottom: 4px;
    }
    .tf__menu_item_text .price {
        margin-bottom: 4px;
    }
    .menu-product-card.is-in-cart {
        border-color: #16a34a;
        box-shadow: 0 8px 24px rgba(22, 163, 74, 0.15);
    }
    .menu-product-card.just-added {
        animation: cartPulse 0.8s ease;
    }
    @keyframes cartPulse {
        0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.6); }
        100% { box-shadow: 0 0 0 12px rgba(22, 163, 74, 0); }
    }
    .in-cart-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #16a34a;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .menu-search-bar {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .menu-search-input {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 999px;
        padding: 10px 16px;
        box-shadow: 0 8px 20px rgba(16, 24, 40, 0.06);
    }
    .menu-search-input i {
        color: #9ca3af;
    }
    #menuSearchInput {
        border: none;
        outline: none;
        flex: 1;
        font-size: 16px;
    }
    #menuSearchClear {
        border: none;
        background: #111827;
        color: #fff;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }
    .menu-search-summary {
        font-size: 13px;
        color: #6b7280;
    }
    .menu-search-empty {
        font-size: 14px;
        font-weight: 600;
        color: #dc2626;
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show modal on page load
    const modal = document.getElementById('operatingHoursModal');
   // modal.style.display = 'flex';

    // Close modal when clicking the close button
    const closeButton = modal.querySelector('.modal-close');
    closeButton.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('menuSearchInput');
    const clearButton = document.getElementById('menuSearchClear');
    const summary = document.getElementById('menuSearchSummary');
    const emptyState = document.getElementById('menuSearchEmpty');
    const accordion = document.getElementById('menuAccordion');
    const categoryItems = Array.from(document.querySelectorAll('.menu-category'));
    const productCards = Array.from(document.querySelectorAll('.menu-product-card'));

    function setCollapseState(item, open) {
        const collapse = item.querySelector('.accordion-collapse');
        const button = item.querySelector('.accordion-button');
        if (!collapse || !button) return;

        if (open) {
            collapse.classList.add('show');
            collapse.style.height = 'auto';
            collapse.style.display = 'block';
            button.classList.remove('collapsed');
            button.setAttribute('aria-expanded', 'true');
        } else {
            collapse.classList.remove('show');
            collapse.style.height = '';
            collapse.style.display = '';
            button.classList.add('collapsed');
            button.setAttribute('aria-expanded', 'false');
        }
    }

    function updateSearch() {
        if (!searchInput) return;
        const term = searchInput.value.trim().toLowerCase();
        const hasTerm = term.length > 0;
        let matchedCount = 0;
        const matchedCategories = new Set();

        productCards.forEach(function(card) {
            const searchText = (card.dataset.search || '').toLowerCase();
            const isMatch = !hasTerm || searchText.includes(term);
            const wrapper = card.closest('.menu-product');
            if (wrapper) {
                wrapper.classList.toggle('d-none', !isMatch);
            } else {
                card.classList.toggle('d-none', !isMatch);
            }
            if (isMatch) {
                matchedCount += 1;
                matchedCategories.add(card.dataset.categoryId);
            }
        });

        categoryItems.forEach(function(item) {
            const id = item.dataset.categoryId;
            const showCategory = !hasTerm || matchedCategories.has(id);
            item.classList.toggle('d-none', !showCategory);
            if (hasTerm && showCategory) {
                setCollapseState(item, true);
            } else if (!hasTerm) {
                setCollapseState(item, false);
            } else {
                setCollapseState(item, false);
            }
        });

        if (summary) {
            summary.textContent = hasTerm ? `${matchedCount} item${matchedCount === 1 ? '' : 's'} found` : '';
        }
        if (emptyState) {
            emptyState.classList.toggle('d-none', !(hasTerm && matchedCount === 0));
        }
        if (accordion) {
            accordion.classList.toggle('search-active', hasTerm);
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', updateSearch);
        searchInput.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                searchInput.value = '';
                updateSearch();
            }
        });
    }
    if (clearButton) {
        clearButton.addEventListener('click', function() {
            if (!searchInput) return;
            searchInput.value = '';
            updateSearch();
            searchInput.focus();
        });
    }
    updateSearch();

    document.addEventListener('click', function(event) {
        const card = event.target.closest('.menu-product-card');
        if (!card) return;
        const productId = card.dataset.productId;
        if (productId) {
            load_product_model(productId);
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key !== 'Enter') return;
        const card = document.activeElement;
        if (card && card.classList.contains('menu-product-card')) {
            const productId = card.dataset.productId;
            if (productId) {
                load_product_model(productId);
            }
        }
    });

    window.markProductInCart = function(productId) {
        const card = document.querySelector(`.menu-product-card[data-product-id="${productId}"]`);
        if (!card) return;
        card.classList.add('is-in-cart');
        card.classList.add('just-added');
        let badge = card.querySelector('.in-cart-badge');
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'in-cart-badge';
            badge.textContent = 'In Cart';
            card.appendChild(badge);
        }
        window.setTimeout(function() {
            card.classList.remove('just-added');
        }, 900);
    };
});
</script>
@endsection
