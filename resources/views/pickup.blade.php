@extends('layout')
@section('title')
    <title>{{__('user.Checkout')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Checkout')}}">
    <style>
        .free-item-coupon {
            background-color: #fff5e6;
            border: 2px dashed #ff7c08;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
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
        }

        .free-item-coupon h4 {
            color: #ff7c08;
            margin-bottom: 10px;
            font-size: 18px;
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
                    <h1>PickUp</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="javascript:;">PickUp</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->

    <!--============================
        CHECK OUT PAGE START
    ==============================-->
    <section class="tf__cart_view mt_30 xs_mt_30 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <!-- Restaurant Details Column -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1s">
                    <div class="tf__checkout_form restaurant-container">
                        <div class="tf__check_form">
                            <h2 class="restaurant-title">Punjabi Paradise</h2>
                            <div class="restaurant-details">
                                <p><strong>Address:</strong> 419 High St, Penrith NSW 2750, Australia</p>
                                <p><strong>Phone:</strong> (02) 4707 6700</p>
                                <p><strong>Location:</strong> <a href="https://maps.app.goo.gl/VSnVXbY2jN26nFiR7" style="color: blue;" target="_blank">Click Here</a></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Details Form Column -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1s" style="margin-bottom:25px;">
                    <div class="tf__checkout_form user-details-container">
                        <div class="tf__check_form">
                            <form id="user_details_form">
                                @csrf
                                <div class="form-group">
                                    <label for="user_name">{{__('user.Name')}}</label>
                                    <input type="text" id="user_name" name="user_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="user_email">{{__('user.Email')}}</label>
                                    <input type="email" id="user_email" name="user_email" class="form-control" required>
                                    <label style="color:red;">Confirmation will be sent via email.</label>
                                </div>
                                <div class="form-group">
                                    <label for="user_phone">{{__('user.Phone')}}</label>
                                    <input type="text" id="user_phone" name="user_phone" class="form-control" required>
                                </div>
                                <input type="hidden" name="delivery_instructions" id="hidden_delivery_instructions">
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Payment Details Column -->
                <div class="col-lg-4 wow fadeInUp" data-wow-duration="1s">
                    @php
                        $sub_total = 0;
                        $coupon_price = 0.00;
                    @endphp
                    @foreach ($cart_contents as $index => $cart_content)
                        @php
                            $item_price = $cart_content->price * $cart_content->qty;
                            $item_total = $item_price + $cart_content->options->optional_item_price;
                            $sub_total += $item_total;
                        @endphp
                    @endforeach

                    @if (Session::get('coupon_price') && Session::get('offer_type'))
                        @php
                            if(Session::get('offer_type') == 1) {
                                $coupon_price = Session::get('coupon_price');
                                $coupon_price = ($coupon_price / 100) * $sub_total;
                            } else {
                                $coupon_price = Session::get('coupon_price');
                            }
                        @endphp
                    @endif

                    <div id="sticky_sidebar" class="tf__cart_list_footer_button tf__cart_list_footer_button_text">
        <h6>{{__('user.total price')}}</h6>
        <p>{{__('user.subtotal')}}: <span>{{ $currency_icon }}{{ $sub_total }}</span></p>
        <p>{{__('user.discount')}} (-): <span>{{ $currency_icon }}{{ $coupon_price }}</span></p>
        <p>{{__('user.delivery')}} (+): <span class="delivery_charge">{{ $currency_icon }}0.00</span></p>
        <p class="total"><span>{{__('user.Total')}}:</span> <span class="grand_total">{{ $currency_icon }}{{ number_format($sub_total - $coupon_price, 2) }}</span></p>
        <input type="hidden" id="grand_total" value="{{ $sub_total - $coupon_price }}">
        
       
        <!-- Add the offer display here -->
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
                    <label class="free-item">Free Rice Added To Your Order</label>
                @elseif($total >= 50)
                    <label class="free-item">Free Plain Naan Added To Your Order</label>
                @endif
                <br/>
            </div>
        @endif
        -->
        <div class="form-group" style="margin-top:8px;">
        <label for="delivery-instructions"><b>Instructions:</b></label>
        <textarea id="delivery-inst" class="form-control" name="delivery-inst" rows="3" placeholder="Enter any special delivery instructions and offer option here..."></textarea>
        </div>
 
     <a class="common_btn" href="javascript:;" id="continue_to_pay">{{__('user.Continue to pay')}}</a>
    
    </div>
    </div>

            </div>
        </div>
        <div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 9999;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #ffffff;">
        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
            <span class="sr-only">Loading...</span>
        </div>
        <p style="margin-top: 10px; font-size: 1.6rem; font-weight: 600; color:white;">Please wait, we are processing your order...</p>
    </div>
</div>
        <div class="tf__payment_modal">
            <div class="modal fade" id="stripePaymentModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="tf__pay_modal_info">
                            <form role="form" action="{{ route('stripe-payment') }}" method="POST" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ $stripePaymentInfo->stripe_key }}" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="text" class="input card-number" name="card_number" placeholder="{{__('user.Card number')}}" autocomplete="off">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="input card-expiry-month" name="month" placeholder="{{__('user.Month')}}" autocomplete="off">
                                        </div>
                                        <div class="col-md-6">
                                            <input class="input card-expiry-year" name="year" type="text" placeholder="{{__('user.Year')}}" autocomplete="off">
                                        </div>

                                        <div class="col-12">
                                            <input class="input card-cvc" name="cvc" type="text" placeholder="{{__('user.CVC')}}" autocomplete="off">
                                        </div>

                                        <div class='col-12 mt-3 error d-none'>
                                            <div class='alert-danger alert '>{{__('user.Please provide your valid card information')}}</div>
                                        </div>

                                    </div>
                                    <div class="tf__payment_btn_area">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{__('user.Close')}}</button>
                                        <button type="submit" class="btn btn-success" id="stripe-submit-btn">{{__('user.Submit')}}</button>
                                    </div>
                                </form>
                            </div>
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
    <!--============================
        CHECK OUT PAGE END
    ==============================-->
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script>
    (function($) {
        "use strict";
        
        $(document).ready(function () {

           // document.getElementById('stripe-submit-btn').addEventListener('click', function() { this.disabled = true; });
           $('#stripe-submit-btn').prop('disabled', true);

        // Function to check if all card details are filled
        function checkCardDetails() {
            var cardNumber = $('.card-number').val().trim();
            var cardCvc = $('.card-cvc').val().trim();
            var cardMonth = $('.card-expiry-month').val().trim();
            var cardYear = $('.card-expiry-year').val().trim();

            if (cardNumber && cardCvc && cardMonth && cardYear) {
                $('#stripe-submit-btn').prop('disabled', false);
            } else {
                $('#stripe-submit-btn').prop('disabled', true);
            }
        }

        // Add event listeners to card detail inputs
        $('.card-number, .card-cvc, .card-expiry-month, .card-expiry-year').on('input', checkCardDetails);

            // Function to handle form submission
            $('form.require-validation').bind('submit', function(e) {
                var $form = $(".require-validation"),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                                     'input[type=text]', 'input[type=file]',
                                     'textarea'].join(', '),
                    $inputs = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid = true;

                // Prevent default form submission
                e.preventDefault();

                // Append user details to the form as hidden inputs
                $form.append('<input type="hidden" name="user_name" value="' + $('#user_name').val() + '">');
                $form.append('<input type="hidden" name="user_email" value="' + $('#user_email').val() + '">');
                $form.append('<input type="hidden" name="user_phone" value="' + $('#user_phone').val() + '">');
                $form.append('<input type="hidden" name="delivery_instructions" value="' + $('#hidden_delivery_instructions').val() + '">');

                // Validate other form inputs
                $errorMessage.addClass('d-none');
                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('d-none');
                        valid = false;
                    }
                });

                if (valid) {
                    // Proceed with Stripe payment form submission
                    if (!$form.data('cc-on-file')) {
                        Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                        Stripe.createToken({
                            number: $('.card-number').val(),
                            cvc: $('.card-cvc').val(),
                            exp_month: $('.card-expiry-month').val(),
                            exp_year: $('.card-expiry-year').val()
                        }, stripeResponseHandler);
                    }
                }
            });

            // Function to handle Stripe response
            function stripeResponseHandler(status, response) {
                var $form = $('#payment-form');

                if (response.error) {
                    // Display error message
                    $('.error')
                        .removeClass('d-none')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    // Tokenize the form and submit to backend
                    var token = response['id'];
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");

                    // Submit the form to backend (assuming action attribute is set to stripe-payment route)
                    $form.get(0).submit();
                }
            }

            // Handle change in address (not directly related to Stripe, but for delivery charge)
            $("input[name='address_id']").on("change", function() {
                var delivery_id = $("input[name='address_id']:checked").val();
                $(".delivery_charge").html(`{{ $currency_icon }}${0}`);
                let grand_total = $("#grand_total").val();
                grand_total = parseInt(grand_total) + parseInt(0);
                $(".grand_total").html(`{{ $currency_icon }}${grand_total}`);
                
                // Ajax call to update delivery charge (if required)
                $.ajax({
                    type: 'get',
                    data: { delivery_id: delivery_id },
                    url: "{{ url('/set-delivery-charge') }}",
                    success: function (response) {
                        console.log(response);
                    },
                    error: function(response) {
                        toastr.error("{{__('user.Server error occured')}}")
                    }
                });
            });

            // Handle continue to pay button click
            $("#continue_to_pay").on("click", function(e) {
                e.preventDefault();
                var name = $("#user_name").val();
                var email = $("#user_email").val();
                var phone = $("#user_phone").val();
                var deliveryInstructions = $("#delivery-inst").val();
                var phonePattern = /^\d{10}$/;

                if (name === '' || email === '' || phone === '') {
                    alert("Please fill in all required fields: Name, Email, and Phone.");
                } 
                else if (!phonePattern.test(phone)) {
                alert("Please enter a valid 10-digit phone number.");
                return false;
                }
                else {
                    $("#hidden_delivery_instructions").val(deliveryInstructions);
                    $("#stripePaymentModal").modal('show');
                }
            });

        });
    })(jQuery);
</script>
<script>
    
document.getElementById('payment-form').addEventListener('submit', function() {
    document.getElementById('stripe-submit-btn').addEventListener('click', function() { this.disabled = true; });
    document.getElementById('loading-overlay').style.display = 'block';
});
</script>

@endsection