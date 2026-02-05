
@extends('layout')
@section('title')
    <title>{{__('user.Payment')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Payment')}}">
@endsection

@section('public-content')
<style>
    /* Custom styles for centering modal content */
.modal-dialog-centered {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100% - 1rem);
}

</style>
    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Payment')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="javascript:;">{{__('user.Payment')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->


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

    @php
    
    session()->forget('coupon_price');
    @endphp
    @if (Session::get('coupon_price') && Session::get('offer_type'))
        @php
            if(Session::get('offer_type') == 1) {
                $coupon_price = Session::get('coupon_price');
                $coupon_price = ($coupon_price / 100) * $sub_total;
            }else {
                $coupon_price = Session::get('coupon_price');
            }
        @endphp
    @endif

    <!--============================
        PAYMENT PAGE START
    ==============================-->
    <section class="tf__payment_page mt_30 xs_mt_30 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <!--
            <div class="col-lg-8">
                    <div class="tf__payment_area">
                        <div class="row">
                            
                        @if ($stripePaymentInfo->status == 1)
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="tf__single_payment" data-bs-toggle="modal" data-bs-target="#stripePaymentModal"
                                        href="javascript:;">
                                        <img src="{{ asset($stripePaymentInfo->payment_page_image) }}" alt="payment method" class="img-fluid w-100">
                                    </a>
                                </div>
                            @endif
                            
                            @if ($ewayPaymentInfo->status == 1)
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="tf__single_payment" href="{{ route('eway-payment') }}">
                                        <img src="{{ asset($ewayPaymentInfo->payment_page_image) }}" alt="payment method" class="img-fluid w-100">
                                    </a>
                                    
                                </div>
                            @endif

                            @if ($paypalPaymentInfo->status == 1)
                            <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                <a class="tf__single_payment" href="{{ route('pay-with-paypal') }}">
                                    <img src="{{ asset($paypalPaymentInfo->payment_page_image) }}" alt="payment method" class="img-fluid w-100">
                                </a>
                            </div>
                            @endif

                            @if ($razorpayPaymentInfo->status == 1)
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="tf__single_payment" href="javascript:;" onclick="pay_with_razorpay()">
                                        <img src="{{ asset($razorpayPaymentInfo->payment_page_image) }}" alt="payment method" class="img-fluid w-100">
                                    </a>
                                </div>

                                <form action="{{ route('pay-with-razorpay') }}" method="POST" id="razorpay_form" class="d-none">
                                    @csrf
                                    @php
                                        $payable_amount = $calculate_amount['grand_total'] * $razorpayPaymentInfo->currency_rate;
                                        $payable_amount = round($payable_amount, 2);
                                    @endphp
                                    <script src="https://checkout.razorpay.com/v1/checkout.js"
                                            data-key="{{ $razorpayPaymentInfo->key }}"
                                            data-currency="{{ $razorpayPaymentInfo->currency_code }}"
                                            data-amount= "{{ $payable_amount * 100 }}"
                                            data-buttontext="{{__('user.Pay')}}"
                                            data-name="{{ $razorpayPaymentInfo->name }}"
                                            data-description="{{ $razorpayPaymentInfo->description }}"
                                            data-image="{{ asset($razorpayPaymentInfo->image) }}"
                                            data-prefill.name=""
                                            data-prefill.email=""
                                            data-theme.color="{{ $razorpayPaymentInfo->color }}">
                                    </script>
                                </form>
                            @endif

                            @if ($flutterwavePaymentInfo->status == 1)
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="tf__single_payment" href="javascript:;" onclick="make_flutterwave_payment()">
                                        <img src="{{ asset($flutterwavePaymentInfo->payment_page_image) }}" alt="payment method" class="img-fluid w-100">
                                    </a>
                                </div>
                            @endif

                            @if ($paystackAndMollie->mollie_status == 1)
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="tf__single_payment" href="{{ route('pay-with-mollie') }}">
                                        <img src="{{ asset($paystackAndMollie->mollie_payment_page_image) }}" alt="payment method" class="img-fluid w-100">
                                    </a>
                                </div>
                            @endif

                            @if ($paystackAndMollie->paystack_status == 1)
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="tf__single_payment" href="javascript:;" onclick="payWithPaystack()">
                                        <img src="{{ asset($paystackAndMollie->paystack_payment_page_image) }}" alt="payment method" class="img-fluid w-100">
                                    </a>
                                </div>
                            @endif

                            @if ($instamojo->status == 1)
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="tf__single_payment" href="{{ route('pay-with-instamojo') }}">
                                        <img src="{{ asset($instamojo->payment_page_image) }}" alt="payment method" class="img-fluid w-100">
                                    </a>
                                </div>
                            @endif


                            @if ($sslcommerz->status == 1)
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="tf__single_payment" href="{{ route('sslcommerz-pay') }}">
                                        <img src="{{ asset($sslcommerz->payment_page_image) }}" alt="payment method" class="img-fluid w-100">
                                    </a>
                                </div>
                            @endif

                            @if ($bankPaymentInfo->status)
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="tf__single_payment" data-bs-toggle="modal" data-bs-target="#bankPaymentModal"
                                        href="#">
                                        <img src="{{ asset($bankPaymentInfo->bank_payment_page_image) }}" alt="payment method" class="img-fluid w-100">
                                    </a>
                                </div>
                            @endif

                            @if ($bankPaymentInfo->cash_on_delivery_status ==1)
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="tf__single_payment" href="{{ route('handcash-payment') }}">
                                        <img src="{{ asset($bankPaymentInfo->handcash_payment_page_image) }}" alt="payment method" class="img-fluid w-100">
                                    </a>
                                </div>
                            @endif
      
                        </div>
                    </div>
                </div>
  -->
                <div class="col-lg-4 wow fadeInUp" data-wow-duration="1s">
                    <div class="tf__cart_list_footer_button tf__cart_list_footer_button_text">
                        <h6>{{__('user.total price')}}</h6>
                        <p>{{__('user.subtotal')}}: <span>{{ $currency_icon }}{{ $calculate_amount['sub_total'] }}</span></p>
                        <p>{{__('user.discount')}} (-): <span>{{ $currency_icon }}{{ $calculate_amount['coupon_price'] }}</span></p>
                        <p>{{__('user.delivery')}} (+): <span>{{ $currency_icon }}{{ $calculate_amount['delivery_charge'] }}</span></p>
                        <p class="total"><span>{{__('user.Total')}}:</span> <span>{{ $currency_icon }}{{ $calculate_amount['grand_total'] }}</span></p>
                            <a class="common_btn" href="javascript:;" data-bs-toggle="modal" data-bs-target="#stripePaymentModal" id="continue_to_pay">{{__('user.Pay Now')}}</a>                       
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="tf__payment_modal">
        <div class="modal fade" id="bankPaymentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="tf__pay_modal_info">
                            {!! clean(nl2br($bankPaymentInfo->account_info)) !!}
                            <form action="{{ route('bank-payment') }}" method="POST">
                                @csrf
                                <textarea required name="tnx_info" rows="4" placeholder="{{__('user.Transaction Information')}}"></textarea>
                                <div class="tf__payment_btn_area">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{__('user.Close')}}</button>
                                    <button type="submit" class="btn btn-success">{{__('user.Submit')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
                                    <button type="submit" class="btn btn-success">{{__('user.Submit')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $flutterwave_payable_amount = $calculate_amount['grand_total'] * $flutterwavePaymentInfo->currency_rate;
        $flutterwave_payable_amount = round($flutterwave_payable_amount, 2);

        $public_key = $paystackAndMollie->paystack_public_key;
        $currency = $paystackAndMollie->paystack_currency_code;
        $currency = strtoupper($currency);

        $ngn_amount = $calculate_amount['grand_total'] * $paystackAndMollie->paystack_currency_rate;
        $ngn_amount = $ngn_amount * 100;
        $ngn_amount = round($ngn_amount);

    @endphp

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>

<script>
    $(function() {
        var $form = $(".require-validation");
        $('form.require-validation').bind('submit', function(e) {
            var $form         = $(".require-validation"),
            inputSelector = ['input[type=email]', 'input[type=password]',
                                'input[type=text]', 'input[type=file]',
                                'textarea'].join(', '),
            $inputs       = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid         = true;
            $errorMessage.addClass('d-none');

            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('d-none');
                    e.preventDefault();
                }
            });
           /*
            if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Eway.setPublishableKey($form.data('eway-publishable-key'));
            Eway.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, ewayResponseHandler);
            }
            */

            if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
         }

        });

        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('.error')
                    .removeClass('d-none')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                var token = response['id'];
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }

        function ewayResponseHandler(status, response) {
            if (response.error) {
                $('.error')
                    .removeClass('d-none')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                var token = response['id'];
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='ewayToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }

    });

    function pay_with_razorpay(){
        $("#razorpay_form").submit();
    }

    function make_flutterwave_payment(){
        var isDemo = "{{ env('APP_MODE') }}"
        if(isDemo == 0){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }

        FlutterwaveCheckout({
        public_key: "{{ $flutterwavePaymentInfo->public_key }}",
        tx_ref: "{{ substr(rand(0,time()),0,10) }}",
        amount: {{ $flutterwave_payable_amount }},
        currency: "{{ $flutterwavePaymentInfo->currency_code }}",
        country: "{{ $flutterwavePaymentInfo->country_code }}",
        payment_options: " ",
        customer: {
          email: "{{ $user->email }}",
          phone_number: "{{ $user->phone }}",
          name: "{{ $user->name }}",
        },
        callback: function (data) {
            var tnx_id = data.transaction_id;
            var _token = "{{ csrf_token() }}";
            $.ajax({
                type: 'post',
                data : {tnx_id,_token},
                url: "{{ route('pay-with-flutterwave') }}",
                success: function (response) {
                    toastr.success(response.message);
                    window.location.href = "{{ route('dashboard') }}";
                },
                error: function(err) {
                    if(err.status == 403){
                        toastr.error(err.responseJSON.message)
                    }
                    window.location.reload();
                }
            });

        },
        customizations: {
          title: "{{ $flutterwavePaymentInfo->title }}",
          logo: "{{ asset($flutterwavePaymentInfo->logo) }}",
        },
      });
    }

    function payWithPaystack(){

        var isDemo = "{{ env('APP_MODE') }}"
        if(isDemo == 0){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }

        var handler = PaystackPop.setup({
            key: '{{ $public_key }}',
            email: '{{ $user->email }}',
            amount: '{{ $ngn_amount }}',
            currency: "{{ $currency }}",
            callback: function(response){
            let reference = response.reference;
            let tnx_id = response.transaction;
            let _token = "{{ csrf_token() }}";
            $.ajax({
                type: "post",
                data: {reference, tnx_id, _token},
                url: "{{ route('pay-with-paystack') }}",
                success: function(response) {
                    window.location.href = "{{ route('dashboard') }}";
                },
                error: function(err) {
                    if(err.status == 403){
                        toastr.error(err.responseJSON.message)
                    }
                    window.location.reload();
                }
            });
            },
            onClose: function(){
                alert('window closed');
            }
        });
        handler.openIframe();
    }
</script>


@endsection
