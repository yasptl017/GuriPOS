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
</style>
@endsection

@section('public-content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Shopping Cart')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="javascript:">{{__('user.Shopping Cart')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->

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
                            <div class="table-responsive">
                                <table>
                                    <tbody>
                                    <tr>
                                        <th class="tf__pro_name">
                                            {{__('user.details')}}
                                        </th>
                                        <th class="tf__pro_select">
                                            {{__('user.quantity')}}
                                        </th>
                                        <th class="tf__pro_tk">
                                            {{__('user.total')}}
                                        </th>
                                        <th class="tf__pro_icon">
                                            <a class="clear_all" href="javascript:">{{__('user.clear all')}}</a>
                                        </th>
                                    </tr>

                                    @php
                                        $sub_total = 0;
                                        $coupon_price = 0.00;
                                    @endphp
                                    @foreach ($cart_contents as $index => $cart_content)
                                        <tr class="main-cart-item-{{ $cart_content->rowId }}">
                                            <td class="tf__pro_name">
                                                <a href="{{ route('show-product', $cart_content->options->slug) }}">{{ $cart_content->name }} - {{ $currency_icon }}{{ number_format($cart_content->price, 2) }}</a>
                                                <span>{{ $cart_content->options->size ? $cart_content->options->size : '' }}</span>
                                                @foreach ($cart_content->options->optional_items as $optional_item)
                                                    <p>{{ $optional_item['optional_name'] }}
                                                        (+{{ $currency_icon }}{{ number_format($optional_item['optional_price'], 2) }}
                                                        )</p>
                                                @endforeach
                                                
                                            </td>
                                            @php
                                                $item_price = $cart_content->price * $cart_content->qty;
                                                $item_total = $item_price + $cart_content->options->optional_item_price;
                                                $sub_total += $item_total;
                                            @endphp
                                            <td class="tf__pro_select" data-item-price="{{ $cart_content->price }}"
                                                data-optional-price="{{ $cart_content->options->optional_item_price }}"
                                                data-rowid="{{ $cart_content->rowId }}">
                                                <div class="quentity_btn">
                                                    <button class="btn btn-danger decrement_product"><i
                                                            class="fal fa-minus"></i></button>
                                                    <input class="quantity" type="text" readonly
                                                           value="{{ $cart_content->qty }}">
                                                    <button class="btn btn-success increament_product"><i
                                                            class="fal fa-plus"></i></button>
                                                </div>
                                            </td>
                                            <td class="tf__pro_tk">
                                                <h6>{{ $currency_icon }}{{ number_format($item_total, 2) }}</h6>
                                                <input type="hidden" class="product_total" value="{{ $item_total }}">
                                            </td>
                                            <td class="tf__pro_icon" data-remove-rowid="{{ $cart_content->rowId }}">
                                                <a class="remove_item" href="javascript:"><i
                                                        class="far fa-times"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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
                            <!--  <label style="color: red; font-weight:700">Delivery is unavailable today. You can order through UberEats or DoorDash</label>-->
        <form id="coupon_form">
            <input name="coupon" type="text" placeholder="{{__('Apply Coupon Code Here.')}}">
            <button type="submit">{{__('user.apply')}}</button>
        </form>
    
       <a class="common_btn" href="{{ route('pickup') }}">Pick Up</a>
     
        <a class="common_btn" href="{{ route('delivery') }}">Delivery</a>

    </div>
</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

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
                let parent_td = $(this).closest('td');
                let item_price = parseFloat(parent_td.data('item-price'));
                let optional_price = parseFloat(parent_td.data('optional-price'));
                let quantity = parseInt(parent_td.find('.quantity').val());
                let new_qty = $(this).hasClass('increament_product') ? quantity + 1 : Math.max(1, quantity - 1);

                parent_td.find('.quantity').val(new_qty);
                let new_item_price = new_qty * item_price;
                let new_sub_total_price = new_item_price + optional_price;
                let parent_tr = parent_td.closest('tr');
                let product_sub_total_html = `<h6>{{ $currency_icon }}${new_sub_total_price.toFixed(2)}</h6>
        <input type="hidden" class="product_total" value="${new_sub_total_price}">`;
                parent_tr.find('.tf__pro_tk').html(product_sub_total_html);

                let rowid = parent_td.data('rowid');
                $(".mini-price-" + rowid).html(`{{ $currency_icon }}${new_sub_total_price.toFixed(2)}`);
                $(".set-mini-input-price-" + rowid).val(new_sub_total_price);
                update_item_qty(rowid, new_qty);
            });

            $(".remove_item").on("click", function () {
                let parernt_td = $(this).parents('td');
                let rowid = parernt_td.data('remove-rowid');
                let parent_tr = parernt_td.parents('tr');
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