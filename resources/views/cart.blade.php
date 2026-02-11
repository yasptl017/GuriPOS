@extends('layout')
@section('title')
    <title>{{__('user.Shopping Cart')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Shopping Cart')}}">
    <style>
    .free-item-coupon {
        background-color: #fff5e6;
        border: 2px dashed #ff7c08;
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
        position: relative;
        overflow: hidden;
        visibility: hidden;
    }

    .free-item-coupon:before {
        content: 'FREE';
        position: absolute;
        top: 10px;
        right: -30px;
        background: #ff7c08;
        color: white;
        padding: 5px 30px;
        transform: rotate(45deg);
        font-size: 12px;
        font-weight: bold;
        visibility: hidden;
    }

    .free-item-coupon h4 {
        color: #ff7c08;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .offer-note {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }

    .free-item {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .free-item:before {
        content: '✓ ';
        color: #ff7c08;
    }

    .next-offer {
        font-size: 14px;
        color: #666;
        font-style: italic;
        margin-top: 10px;
    }

    .coupon-scissors {
        position: absolute;
        top: -10px;
        left: 10px;
        font-size: 24px;
        color: #ff7c08;
        transform: rotate(-90deg);
    }

    /* Cart Cards */
    .cart-cards-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 16px;
        background: #f8f8f8;
        border-radius: 8px;
        margin-bottom: 12px;
        font-weight: 600;
        font-size: 14px;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .cart-cards-header .clear_all {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 8px 14px;
        border-radius: 999px;
        border: 1px solid #fecaca;
        background: #fff1f2;
        color: #b42318;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .cart-cards-header .clear_all:hover {
        background: #ffe4e6;
        color: #912018;
    }

    .clear-all-mobile-wrap {
        display: none;
        margin-bottom: 12px;
    }

    .clear-all-mobile-wrap .clear_all {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 10px 16px;
        border-radius: 10px;
        border: 1px solid #fecaca;
        background: #fff1f2;
        color: #b42318;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .cart-cards-wrapper {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .cart-item-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        border: 1px solid #ebebeb;
        border-radius: 10px;
        padding: 14px 18px;
        gap: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        transition: box-shadow 0.2s;
    }

    .cart-item-card:hover {
        box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    }

    .cart-item-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex: 1;
        min-width: 0;
    }

    .cart-item-name {
        font-size: 15px;
        font-weight: 700;
        color: #222;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cart-item-name:hover {
        color: #ff7c08;
    }

    .cart-item-name .product-price-inline {
        white-space: nowrap;
    }

    .cart-item-unit-price {
        font-size: 13px;
        color: #777;
    }

    .cart-item-size {
        font-size: 12px;
        color: #999;
        background: #f0f0f0;
        border-radius: 4px;
        padding: 1px 7px;
        display: inline-block;
        width: fit-content;
    }

    .cart-item-addon {
        font-size: 12px;
        color: #888;
    }

    .cart-item-actions {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-shrink: 0;
    }

    .cart-item-total h6 {
        font-size: 15px;
        font-weight: 700;
        color: #ff7c08;
        margin: 0;
        white-space: nowrap;
    }

    .tf__pro_icon .remove_item {
        color: #ccc;
        font-size: 18px;
        line-height: 1;
        transition: color 0.2s;
    }

    .tf__pro_icon .remove_item:hover {
        color: #e74c3c;
    }

    @media (max-width: 576px) {
        .cart-cards-header .clear_all {
            display: none;
        }

        .clear-all-mobile-wrap {
            display: block;
        }

        .cart-item-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 12px 14px;
        }

        .cart-item-actions {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>
@endsection

@section('public-content')

    <section class="tf__cart_view mt_125 xs_mt_95 mb_100 xs_mb_70">
        <div class="container cart-main-body">
            @if (count($cart_contents) == 0)
                <div class="row">
                    <div class="col-12 wow fadeInUp" data-wow-duration="1s">
                        <h3 class="text-center cart_empty_text">{{__('user.Your shopping cart is empty!')}}</h3>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__cart_list">
                            <div class="cart-cards-header">
                                <span>{{__('user.details')}}</span>
                                <a class="clear_all" href="javascript:">{{__('user.clear all')}}</a>
                            </div>
                            <div class="clear-all-mobile-wrap">
                                <a class="clear_all" href="javascript:">{{__('user.clear all')}}</a>
                            </div>

                            @php
                                $sub_total = 0;
                                $coupon_price = 0.00;
                            @endphp

                            <div class="cart-cards-wrapper">
                            @foreach ($cart_contents as $index => $cart_content)
                                @php
                                    $item_price = $cart_content->price * $cart_content->qty;
                                    $item_total = $item_price + $cart_content->options->optional_item_price;
                                    $sub_total += $item_total;
                                @endphp
                                <div class="cart-item-card main-cart-item-{{ $cart_content->rowId }}">
                                    <div class="cart-item-info">
                                        <a class="cart-item-name" href="{{ route('show-product', $cart_content->options->slug) }}">{{ $cart_content->name }} <span class="product-price-inline">- {{ $currency_icon }}{{ number_format($cart_content->price, 2) }}</span></a>
                                        @if($cart_content->options->size)
                                            <span class="cart-item-size">{{ $cart_content->options->size }}</span>
                                        @endif
                                        @foreach ($cart_content->options->optional_items as $optional_item)
                                            <span class="cart-item-addon">+ {{ $optional_item['optional_name'] }} ({{ $currency_icon }}{{ number_format($optional_item['optional_price'], 2) }})</span>
                                        @endforeach
                                    </div>
                                    <div class="cart-item-actions">
                                        <div class="tf__pro_select quentity_btn"
                                             data-item-price="{{ $cart_content->price }}"
                                             data-optional-price="{{ $cart_content->options->optional_item_price }}"
                                             data-rowid="{{ $cart_content->rowId }}">
                                            <button class="btn btn-danger decrement_product"><i class="fal fa-minus"></i></button>
                                            <input class="quantity" type="text" readonly value="{{ $cart_content->qty }}">
                                            <button class="btn btn-success increament_product"><i class="fal fa-plus"></i></button>
                                        </div>
                                        <div class="tf__pro_tk cart-item-total">
                                            <h6>{{ $currency_icon }}{{ number_format($item_total, 2) }}</h6>
                                            <input type="hidden" class="product_total" value="{{ $item_total }}">
                                        </div>
                                        <div class="tf__pro_icon" data-remove-rowid="{{ $cart_content->rowId }}">
                                            <a class="remove_item" href="javascript:"><i class="far fa-times"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>

                    @if (Session::get('coupon_price') && Session::get('offer_type'))
                        <input type="hidden" id="couon_price" value="{{ Session::get('coupon_price') }}">
                        <input type="hidden" id="couon_offer_type" value="{{ Session::get('offer_type') }}">

                        @php
                             if(Session::get('offer_type') == 1) {
                                $coupon_price = Session::get('coupon_price');
                                $coupon_price = ($coupon_price / 100) * $sub_total;
                            }else {
                                $coupon_price = Session::get('coupon_price');
                            }
                        @endphp
                    @else
                        <input type="hidden" id="couon_price" value="0.00">
                        <input type="hidden" id="couon_offer_type" value="0">
                    @endif

                    <div class="col-lg-12 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__cart_list_footer_button mt_50">
                            <div class="row">
                               <!-- <div class="col-xl-7 col-md-6">
                                    <div class="tf__cart_list_footer_button_img">
                                        <a href="{{ $cart_banner->link }}">
                                            <img src="{{ asset($cart_banner->image) }}" alt="cart offer"
                                                 class="img-fluid w-100">
                                        </a>
                                    </div>
                                </div>-->
                                <div class="col-xl-5 col-md-6">
    <div class="tf__cart_list_footer_button_text">
        <div class="grand_total">
            <h6>{{__('user.total price')}}</h6>
            <p>{{__('user.subtotal')}}:
                <span>{{ $currency_icon }}{{ number_format($sub_total, 2) }}</span></p>
            <p>{{__('user.discount')}} (-):
                <span>{{ $currency_icon }}{{ number_format($coupon_price, 2) }}</span>
            </p>
            <p class="total"><span>{{__('user.Total')}}:</span>
                <span>{{ $currency_icon }}{{ number_format($sub_total - $coupon_price, 2) }}</span>
            </p>
            
            @php
                $total = $sub_total - $coupon_price;
            @endphp
            <!--
            @if($total >= 50)
                <div class="free-item-coupon">
                    <div class="coupon-scissors">✂</div>
                    <h4>Offer:</h4>
                    @if($total >= 150)
                        <label class="free-item">Free (Butter Chicken / Dal Makhni) and Mix Bread Basket Added To Your Order</label>
                    @elseif($total >= 100)
                        <label class="free-item">Free Butter Chicken / Dal Makhni Added To Your Order</label>
                    @elseif($total >= 80)
                        <label class="free-item">Free Mix Bread Basket Added To Your Order</label>
                    @elseif($total >= 60)
                        <plabel class="free-item">Free Rice Added To Your Order</plabel
                    @elseif($total >= 50)
                        <label class="free-item">Free Plain Naan Added To Your Order</label>
                    @endif
                    <br/>
                </div>
            @endif
        -->
        </div>
        <form id="coupon_form">
            <input name="coupon" type="text" placeholder="{{__('Apply Coupon Code Here.')}}">
            <button type="submit">{{__('user.apply')}}</button>
        </form>

        @if($orderControl->pickup_enabled)
            <a class="common_btn" href="{{ route('pickup') }}">Pick Up</a>
        @else
            <button class="common_btn order-disabled-btn" type="button"
                data-message="{{ $orderControl->pickup_disabled_message ?: 'Pickup is currently unavailable.' }}">
                Pick Up
            </button>
        @endif

        @if($orderControl->delivery_enabled)
            <a class="common_btn" href="{{ route('delivery') }}">Delivery</a>
        @else
            <button class="common_btn order-disabled-btn" type="button"
                data-message="{{ $orderControl->delivery_disabled_message ?: 'Delivery is currently unavailable.' }}">
                Delivery
            </button>
        @endif

    </div>
</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Order Control Notice Modal --}}
    @php $anyDisabled = !$orderControl->pickup_enabled || !$orderControl->delivery_enabled; @endphp
    @if($anyDisabled)
    <div class="modal fade" id="orderControlModal" tabindex="-1" aria-labelledby="orderControlModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
            <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.18);">
                <div class="modal-header" style="background:#fff7f0;border-bottom:2px solid #ff7c08;border-radius:16px 16px 0 0;padding:20px 24px 16px;">
                    <div class="d-flex align-items-center gap-2">
                        <span style="font-size:22px;">⚠️</span>
                        <h5 class="modal-title mb-0" id="orderControlModalLabel" style="font-size:17px;font-weight:700;color:#cc5500;">Service Notice</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:24px;">
                    @if(!$orderControl->pickup_enabled)
                    <div class="d-flex align-items-start gap-3 mb-3" style="background:#fff3f3;border-radius:10px;padding:14px 16px;border-left:4px solid #e74c3c;">
                        <span style="font-size:24px;line-height:1;">🏪</span>
                        <div>
                            <div style="font-weight:700;color:#c0392b;font-size:14px;margin-bottom:4px;">Pickup Unavailable</div>
                            <div style="font-size:14px;color:#555;line-height:1.5;">{{ $orderControl->pickup_disabled_message ?: 'Pickup is currently unavailable.' }}</div>
                        </div>
                    </div>
                    @endif
                    @if(!$orderControl->delivery_enabled)
                    <div class="d-flex align-items-start gap-3 mb-3" style="background:#fff3f3;border-radius:10px;padding:14px 16px;border-left:4px solid #e74c3c;">
                        <span style="font-size:24px;line-height:1;">🚗</span>
                        <div>
                            <div style="font-weight:700;color:#c0392b;font-size:14px;margin-bottom:4px;">Delivery Unavailable</div>
                            <div style="font-size:14px;color:#555;line-height:1.5;">{{ $orderControl->delivery_disabled_message ?: 'Delivery is currently unavailable.' }}</div>
                        </div>
                    </div>
                    @endif
                    @if($orderControl->pickup_enabled || $orderControl->delivery_enabled)
                    <p style="font-size:13px;color:#777;margin:0;padding-top:4px;">
                        @if($orderControl->pickup_enabled) ✅ <strong>Pickup</strong> is available. @endif
                        @if($orderControl->delivery_enabled) ✅ <strong>Delivery</strong> is available. @endif
                    </p>
                    @endif
                </div>
                <div class="modal-footer" style="border-top:1px solid #f0e0d0;padding:16px 24px;border-radius:0 0 16px 16px;">
                    <button type="button" class="btn" data-bs-dismiss="modal"
                        style="background:#ff7c08;color:#fff;font-weight:700;padding:10px 28px;border-radius:8px;border:none;font-size:14px;">
                        Got it
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            var modal = new bootstrap.Modal(document.getElementById('orderControlModal'));
            modal.show();
        });
    </script>
    @endif

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

    /* Disabled order button */
    .order-disabled-btn {
        opacity: 0.45;
        cursor: not-allowed;
        pointer-events: none;
        filter: grayscale(50%);
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
        $(document).ready(function () {
            $(document).ready(function () {
                $('.common_btn').click(function (event) {
                    var now = new Date();
                    var openingTime = new Date();
                    openingTime.setHours(16, 30, 0); // 4:30 PM
                    var closingTime = new Date();
                    closingTime.setHours(22, 0, 0); // 10:00 PM

                    // Uncomment this block if you want to enforce opening hours
                 if (now < openingTime || now > closingTime) {
                  toastr.warning('Our restaurant is open from 4:30 PM to 10:00 PM.');
                  event.preventDefault();
                  }
                });
            });
            $("#coupon_form").on("submit", function (e) {
    e.preventDefault();
    
    // Calculate current subtotal
    let sub_total = 0;
    $(".product_total").each(function () {
        let current_val = parseFloat($(this).val());
        sub_total += current_val;
    });
    
    // Check minimum order requirement
    if (sub_total < 50) {
        toastr.error("Minimum order of $50 required to apply coupon");
        return false;
    }
    
    $.ajax({
        type: 'get',
        data: $('#coupon_form').serialize(),
        url: "{{ url('/apply-coupon') }}",
        success: function (response) {
            toastr.success(response.message);
            $("#coupon_form").trigger("reset");
            $("#couon_price").val(response.discount);
            $("#couon_offer_type").val(response.offer_type);
            calculate_total();
        },
        error: function (response) {
            if (response.status == 422) {
                if (response.responseJSON.errors.coupon) toastr.error(response.responseJSON.errors.coupon[0])
            }
            if (response.status == 500) {
                toastr.error("{{__('user.Server error occured')}}")
            }
            if (response.status == 403) {
                toastr.error(response.responseJSON.message)
            }
        }
    });
});
            $(document).on("click", ".increament_product, .decrement_product", function () {
                let qty_div = $(this).closest('.quentity_btn');
                let item_price = parseFloat(qty_div.data('item-price'));
                let optional_price = parseFloat(qty_div.data('optional-price'));
                let quantity = parseInt(qty_div.find('.quantity').val());
                let new_qty = $(this).hasClass('increament_product') ? quantity + 1 : Math.max(1, quantity - 1);

                qty_div.find('.quantity').val(new_qty);
                let new_item_price = new_qty * item_price;
                let new_sub_total_price = new_item_price + optional_price;
                let parent_card = qty_div.closest('.cart-item-card');
                let product_sub_total_html = `<h6>{{ $currency_icon }}${new_sub_total_price.toFixed(2)}</h6>
        <input type="hidden" class="product_total" value="${new_sub_total_price}">`;
                parent_card.find('.cart-item-total').html(product_sub_total_html);

                let rowid = qty_div.data('rowid');
                $(".mini-price-" + rowid).html(`{{ $currency_icon }}${new_sub_total_price.toFixed(2)}`);
                $(".set-mini-input-price-" + rowid).val(new_sub_total_price);
                update_item_qty(rowid, new_qty);
            });

            $(".remove_item").on("click", function () {
                let parernt_td = $(this).closest('[data-remove-rowid]');
                let rowid = parernt_td.data('remove-rowid');
                let parent_tr = parernt_td.closest('.cart-item-card');
                parent_tr.remove();
                calculate_total();
                remove_mini_item(rowid);

                let new_qty = parseInt($(".cart_total_qty").html());
                let update_qty = new_qty - 1;
                $(".cart_total_qty").html(update_qty);

                $.ajax({
                    type: 'get',
                    url: "{{ url('/remove-cart-item') }}" + "/" + rowid,
                    success: function (response) {
                        toastr.success(response.message);
                    },
                    error: function (response) {
                        if (response.status == 500 || response.status == 403) {
                            toastr.error("{{__('user.Server error occured')}}");
                        }
                    }
                });
            });

            $(".clear_all").on("click", function () {
                let empty_cart = `<div class="row">
                    <div class="col-12 wow fadeInUp" data-wow-duration="1s">
                        <h3 class="text-center cart_empty_text">{{__('user.Your shopping cart is empty!')}}</h3>
                    </div>
                </div>`;

                let mini_empty_cart = `<div class="tf__menu_cart_header">
                    <h5>{{__('user.Your cart is empty')}}</h5>
                    <span class="close_cart"><i class="fal fa-times"></i></span>
                </div>`;

                $(".cart-main-body").html(empty_cart);
                $(".tf__menu_cart_boody").html(mini_empty_cart);
                $(".topbar_cart_qty").html(0);
                $(".cart_total_qty").html(0);

                $.ajax({
                    type: 'get',
                    url: "{{ url('/cart-clear') }}",
                    success: function (response) {
                        toastr.success(response.message);
                    },
                    error: function (response) {
                        if (response.status == 500 || response.status == 403) {
                            toastr.error("{{__('user.Server error occured')}}");
                        }
                    }
                });
            });
        });

        function update_item_qty(rowid, quantity) {
            calculate_total();
            $.ajax({
                type: 'get',
                data: {rowid, quantity},
                url: "{{ route('cart-quantity-update') }}",
                error: function (response) {
                    if (response.status == 500 || response.status == 403) {
                        toastr.error("{{__('user.Server error occured')}}");
                    }
                }
            });
        }

        function calculate_total() {
    let sub_total = 0;
    let coupon_price = parseFloat($("#couon_price").val() || 0);
    let couon_offer_type = parseInt($("#couon_offer_type").val() || 0);

    let total_item = 0;
    $(".product_total").each(function () {
        let current_val = parseFloat($(this).val());
        sub_total += current_val;
        total_item++;
    });

    // Initialize coupon discount
    let apply_coupon_price = 0;
    
    // Only apply coupon if subtotal meets minimum requirement of 50
    if (sub_total >= 50) {
        if (couon_offer_type === 1) {
            let percentage = coupon_price / 100;
            apply_coupon_price = percentage * sub_total;
        } else if (couon_offer_type === 2) {
            apply_coupon_price = coupon_price;
        }
    } else {
        // If subtotal is less than 50, remove any applied coupon
        $("#couon_price").val(0);
        $("#couon_offer_type").val(0);
        // Optionally show a message to the user
        toastr.warning("Minimum order of $50 required to apply coupon");
    }

    let grand_total = sub_total - apply_coupon_price;
    let total_html = `<h6>{{__('user.total cart')}}</h6>
                    <p>{{__('user.subtotal')}}: <span>{{ $currency_icon }}${sub_total.toFixed(2)}</span></p>`;
    
    if (apply_coupon_price > 0) {
        total_html += `<p>{{__('user.discount')}} (-): <span>{{ $currency_icon }}${apply_coupon_price.toFixed(2)}</span></p>`;
    }
    
    total_html += `<p class="total"><span>{{__('user.Total')}}:</span> <span>{{ $currency_icon }}${grand_total.toFixed(2)}</span></p>`;
    
    $(".grand_total").html(total_html);
    $(".mini_sub_total").html(`{{ $currency_icon }}${sub_total.toFixed(2)}`);

    // Update the offer display
    updateOfferDisplay(grand_total);

    let empty_cart = `<div class="row">
            <div class="col-12 wow fadeInUp" data-wow-duration="1s">
                <h3 class="text-center cart_empty_text">{{__('user.Your shopping cart is empty!')}}</h3>
            </div>
        </div>`;

    let mini_empty_cart = `<div class="tf__menu_cart_header">
        <h5>{{__('user.Your cart is empty')}}</h5>
        <span class="close_cart"><i class="fal fa-times"></i></span>
    </div>`;

    if (total_item == 0) {
        $(".cart-main-body").html(empty_cart);
        $(".tf__menu_cart_boody").html(mini_empty_cart);
    }

    $(".topbar_cart_qty").html(total_item);
}
function remove_mini_item(rowid) {
            $(".min-item-" + rowid).remove();
        }

        function updateOfferDisplay(total) {
    let offerHtml = '';
    if (total >= 50) {
        offerHtml = `
            <div class="free-item-coupon">
                <div class="coupon-scissors">✂</div>
                <h4>Offer:</h4>`;

        if (total >= 150) {
            offerHtml += `<label class="free-item">Free (Butter Chicken / Dal Makhni) and Mix Bread Basket Added To Your Order</label>`;
        } else if (total >= 100) {
            offerHtml += `<label class="free-item">Free Butter Chicken / Dal Makhni Added To Your Order</label>`;
        } else if (total >= 80) {
            offerHtml += `<label class="free-item">Free Mix Bread Basket Added To Your Order</label>`;
        } else if (total >= 60) {
            offerHtml += `<label class="free-item">Free Rice Added To Your Order</label>`;
        } else if (total >= 50) {
            offerHtml += `<label class="free-item">Free Plain Naan Added To Your Order</label>`;
        }

        /*
        offerHtml += `<br/><p class="next-offer">`;
        if (total < 60) {
            offerHtml += `Add {{ $currency_icon }}${(60 - total).toFixed(2)} more to get Free Rice!`;
        } else if (total < 80) {
            offerHtml += `Add {{ $currency_icon }}${(80 - total).toFixed(2)} more to get a Mix Bread Basket!`;
        } else if (total < 100) {
            offerHtml += `Add {{ $currency_icon }}${(100 - total).toFixed(2)} more to get Free Butter Chicken!`;
        } else if (total < 150) {
            offerHtml += `Add {{ $currency_icon }}${(150 - total).toFixed(2)} more to get Free Butter Chicken and Mix Bread Basket!`;
        }
        */
        offerHtml += `</div>`;
    }

    $('.free-item-coupon').remove();
    $('.grand_total').append(offerHtml);
}
    </script>

@endsection
