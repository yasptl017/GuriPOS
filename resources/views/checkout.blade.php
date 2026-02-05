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
            color: black;
            margin-bottom: 0px;
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
        .common_btn.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
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
                    <h1>Delivery </h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="javascript:">Delivery</a></li>
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

                </div>

                <!-- User Details Form Column -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1s" style="margin-bottom:25px;">
                    <div class="tf__checkout_form user-details-container">
                        <div class="tf__check_form">
                            <form action="" method="POST">
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
                                <div class="form-group">
                                    <label for="address">{{__('user.Address')}} (Select from Dropdown)</label>
                                    <x-address-input>
                                        <div class="col-md-12 col-lg-12 col-xl-12 position-relative">
                                            <div class="tf__check_single_form">
                                                <input type="text" id="address-input" placeholder="Enter address"
                                                       name="address" autocomplete="off">
                                            </div>
                            
                                            <div id="address-warning" class="alert alert-warning d-none" role="alert">
                                                Please select an address from the dropdown.
                                            </div>
                                        </div>
                                    </x-address-input>
                                </div>
                                <div id="distance-warning" class="alert alert-warning d-none" role="alert">
                                    Not Deliverable: The delivery address is more than 10km away.
                                </div>
                                <div id="distance-warning-dd" class="alert alert-warning d-none" role="alert">
                                    Not Deliverable or try to select from the address dropdown.
                                </div>
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
        <p class="total"><span>{{__('user.Total')}}:</span> <span class="grand_total">{{ $currency_icon }}{{ $sub_total - $coupon_price }}</span></p>
        <input type="hidden" id="grand_total" value="{{ $sub_total - $coupon_price }}">
        
        <!-- Add the offer display here -->
         <!--
        @if($sub_total >= 50)
            <div class="free-item-coupon">
                <div class="coupon-scissors">✂</div>
                <h4>Offer:</h4>
                @if($sub_total >= 150)
                    <label class="free-item">Free (Butter Chicken / Dal Makhni) and Mix Bread Basket Added To Your Order</label>
                @elseif($sub_total >= 100)
                    <label class="free-item">Free Butter Chicken / Dal Makhni Added To Your Order</label>
                @elseif($sub_total >= 80)
                    <label class="free-item">Free Mix Bread Basket Added To Your Order</label>
                @elseif($sub_total >= 60)
                    <label class="free-item">Free Rice Added To Your Order</label>
                @elseif($sub_total >= 50)
                    <label class="free-item">Free Plain Naan Added To Your Order</label>
                @endif
            </div>
        @endif
        -->
        <div class="form-group">
    <label for="delivery-inst" style="margin-top: 8px;"><b>Delivery Instructions:</b></label>
    <textarea id="delivery-inst" class="form-control" name="delivery-inst" rows="3" placeholder="Enter any special delivery instructions and offer option here..."></textarea>
</div>

@php
    $minimum_order_amount = env('MINIMUM_AMOUNT', 40);
@endphp

@if($sub_total < $minimum_order_amount)
    <div class="alert alert-warning" role="alert">
        Your order total ({{ $currency_icon }}{{ $sub_total }}) is below the minimum order amount of {{ $currency_icon }}{{ $minimum_order_amount }}. Please add more items to your cart to proceed with the order.
    </div>
@endif

@php
    $minimum_order_amount = env('MINIMUM_AMOUNT', 40);
    $is_below_minimum = $sub_total < $minimum_order_amount;
@endphp

<a class="common_btn @if($is_below_minimum) disabled @endif" href="javascript:" id="continue_to_pay">
    {{__('user.Continue to pay')}}
</a>


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
                                <form role="form" action="{{ route('stripe-payment') }}" method="POST"
                                      class="require-validation" data-cc-on-file="false"
                                      data-stripe-publishable-key="{{ $stripePaymentInfo->stripe_key }}"
                                      id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="text" class="input card-number" name="card_number"
                                                   placeholder="{{__('user.Card number')}}" autocomplete="off" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="input card-expiry-month" name="month"
                                                   placeholder="{{__('user.Month')}}" autocomplete="off" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input class="input card-expiry-year" name="year" type="text"
                                                   placeholder="{{__('user.Year')}}" autocomplete="off" required>
                                        </div>

                                        <div class="col-12">
                                            <input class="input card-cvc" name="cvc" type="text"
                                                   placeholder="{{__('user.CVC')}}" autocomplete="off" required>
                                        </div>

                                        <div class='col-12 mt-3 error d-none'>
                                            <div
                                                class='alert-danger alert '>{{__('user.Please provide your valid card information')}}</div>
                                        </div>

                                    </div>
                                    <input type="hidden" name="delivery_instructions" id="delivery-instructions-input">
                                    <div class="tf__payment_btn_area">
                                        <button type="button" class="btn btn-danger"
                                                data-bs-dismiss="modal">{{__('user.Close')}}</button>
                                        <button type="submit" class="btn btn-success">{{__('user.Submit')}}</button>
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
(function ($) {
    "use strict";

    $(document).ready(function () {

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

        // Function to validate user details
        function validateUserDetails() {
            var name = $('#user_name').val();
            var email = $('#user_email').val();
            var phone = $('#user_phone').val();
            var address = $('#address-input').val();
            var phonePattern = /^\d{10}$/;
            if (!name || !email || !phone || !address) {
                alert("Please fill in all required fields: Name, Email, Phone (10 Digit), and Address.");
                return false;
            }
            else if (!phonePattern.test(phone)) {
                alert("Please enter a valid 10-digit phone number.");
                return false;
            }
            return true;
        }

        // Function to check distance and show warning
        function checkDistanceAndWarn() {
            var distance = parseFloat($('#distance').val()) / 1000; // Convert to km
            if (distance > 10) {
                $('#continue_to_pay').css('display', 'none');
                $('#distance-warning').removeClass('d-none');
            } 
            else if(isNaN(distance)){
                $('#continue_to_pay').css('display', 'none');
                $('#distance-warning-dd').removeClass('d-none');
            }
            else {
                $('#distance-warning').addClass('d-none');
                $('#distance-warning-dd').addClass('d-none'); // Hide the other warning as well
                $('#continue_to_pay').css('display', 'block'); // Re-enable the button
            }
        }

        // Attach event to address input
        $('#address-input').on('change', function() {
            setTimeout(checkDistanceAndWarn, 1000); // Delay to allow distance calculation
        });

        // Attach click event to "Continue to pay" button
        $('#continue_to_pay').on('click', function(e) {
    e.preventDefault();
    
    var subTotal = parseFloat('{{ $sub_total }}');
    var minimumAmount = parseFloat('{{ env('MINIMUM_AMOUNT', 40) }}');
    
    if (subTotal < minimumAmount) {
        alert('Your order total is below the minimum order amount. Please add more items to your cart to proceed.');
        return;
    }
    
    if (validateUserDetails()) {
        checkDistanceAndWarn();
        $('#stripePaymentModal').modal('show');
    }
});

        // Function to handle form submission
        $('form.require-validation').bind('submit', function (e) {
            var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                    'input[type=text]', 'input[type=file]',
                    'textarea'].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            e.preventDefault();
            var deliveryInstructions = $('#delivery-inst').val();
            $('#delivery-instructions-input').val(deliveryInstructions);

            // Append user details to the form as hidden inputs
            $form.append('<input type="hidden" name="user_name" value="' + $('#user_name').val() + '">');
            $form.append('<input type="hidden" name="user_email" value="' + $('#user_email').val() + '">');
            $form.append('<input type="hidden" name="user_phone" value="' + $('#user_phone').val() + '">');
            $form.append('<input type="hidden" name="address" value="' + $('#address-input').val() + '">');
            $form.append('<input type="hidden" name="delivery_instructions" value="' + $('#delivery-inst').val() + '">');
            // Validate other form inputs
            $errorMessage.addClass('d-none');
            $('.has-error').removeClass('has-error');
            $inputs.each(function (i, el) {
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

        // Force user to select from autocomplete
        $('#address-input').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#address-input').val(''); // Clear input
                $('#address-warning').removeClass('d-none'); // Show warning
            }
        });

        $('#address-input').on('blur', function() {
            if (!$(this).val()) {
                $('#address-warning').removeClass('d-none'); // Show warning if input is empty
            }
        });

    });

    // Force user to select from autocomplete
    $('#address-input').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $('#address-input').val(''); // Clear input
            $('#address-warning').removeClass('d-none'); // Show warning
        }
    });

    $('#address-input').on('blur', function() {
        if (!$(this).val()) {
            $('#address-warning').removeClass('d-none'); // Show warning if input is empty
        }
    });

    // Hide address warning on typing
    $('#address-input').on('input', function() {
        $('#address-warning').addClass('d-none'); // Hide warning
    });

    const addresses = {{Js::from($delivery_areas)}};

    document.addEventListener('distance-loaded', function () {
        const deliveryDistance = document.querySelector("#distance").value;
        let fee;

        // check if grand total quailifies for free delivery
        if (parseFloat(document.querySelector('#grand_total').value) >= {{env('MINIMUM_AMOUNT')}}) {
            fee = 0;
        }

        // check if delivery distance is within the delivery range
        if (deliveryDistance / 1000 > {{env('MAXIMUM_DISTANCE')}}) {
            toastr.error('Delivery is not available for this location');
            return;
        }

        addresses.forEach(area => {

            if (fee) {
                return;
            }

            if (area.min_range <= deliveryDistance / 1000 && area.max_range >= deliveryDistance / 1000) {
                fee = area.delivery_fee;
            }
        });

        if (fee === undefined || fee === null) {
            fee = {{env('DEFAULTS_DELIVERY')}}
        }
        document.querySelector('.delivery_charge').innerText = '{{ $currency_icon }}' + fee;
        document.querySelector('.grand_total').innerText = '{{ $currency_icon }}' + (parseFloat(document.querySelector('#grand_total').value) + parseFloat(fee));
        // send an ajax request to store the delivery fee

        $.ajax({
            url: "{{ route('set-delivery-charge') }}",
            type: "GET",
            data: {
                charge: fee
            },
            success: function (response) {
                console.log(response);
                // enable the continue to pay button
                $('#continue_to_pay').prop('disabled', false);

            }
        });

    })

})(jQuery);

    </script>

    <script>
        const addresses = {{Js::from($delivery_areas)}};

        document.addEventListener('distance-loaded', function () {
            const deliveryDistance = document.querySelector("#distance").value;
            let fee;


            // check if grand total quailifies for free delivery
            if (parseFloat(document.querySelector('#grand_total').value) >= {{env('MINIMUM_AMOUNT')}}) {
                fee = 0;
            }

            // check if delivery distance is within the delivery range
            if (deliveryDistance / 1000 > {{env('MAXIMUM_DISTANCE')}}) {
                toastr.error('Delivery is not available for this location');
                return;
            }


            addresses.forEach(area => {

                if (fee) {
                    return;
                }

                if (area.min_range <= deliveryDistance / 1000 && area.max_range >= deliveryDistance / 1000) {
                    fee = area.delivery_fee;
                }
            });

            if (fee === undefined || fee === null) {
                fee = {{env('DEFAULTS_DELIVERY')}}
            }
            document.querySelector('.delivery_charge').innerText = '{{ $currency_icon }}' + fee;
            document.querySelector('.grand_total').innerText = '{{ $currency_icon }}' + (parseFloat(document.querySelector('#grand_total').value) + parseFloat(fee));
            //     send an ajax request to store the delivery fee

            $.ajax({
                url: "{{ route('set-delivery-charge') }}",
                type: "GET",
                data: {
                    charge: fee
                },
                success: function (response) {
                    console.log(response);
                    //     enable the continue to pay button
                    $('#continue_to_pay').prop('disabled', false);

                }
            });

        })

    </script>
    <script>
  // Get the textbox element
  const textbox = document.getElementById('address-input');

  // Define the function to be called when typing
  function handleTyping(event) {
    addressWarning.classList.add('d-none');
  }
  textbox.addEventListener('input', handleTyping);
</script>
<script>
    
document.getElementById('payment-form').addEventListener('submit', function() {
    document.getElementById('loading-overlay').style.display = 'block';
});
</script>

@endsection
