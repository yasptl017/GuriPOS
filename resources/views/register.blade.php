
@extends('layout')
@section('title')
    <title>{{__('user.Register')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Register')}}">
@endsection

@section('public-content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Register')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="{{ route('register') }}">{{__('user.Register')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->

            <!--=========================
        SIGNIN START
    ==========================-->
    <section class="tf__signin pt_100 xs_pt_70 pb_100 xs_pb_70">
        <div class="container">
            <div class="row justify-content-center wow fadeInUp" data-wow-duration="1s">
                <div class="col-xl-5 col-sm-10 col-md-8 col-lg-6">
                    <div class="tf__login_area">
                        <h2>{{__('user.Registration')}}</h2>
                        <p>{{__('user.For new user you have to register here')}}</p>
                        <form action="{{ route('store-register') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="tf__login_imput">
                                        <input type="text" name="name" placeholder="{{__('user.Name')}}">
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="tf__login_imput">
                                        <input type="email" name="email" placeholder="{{__('user.Email')}}" value="{{$email}}">
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="tf__login_imput">
                                        <input type="number" name="phone" placeholder="{{__('user.Phone')}}">
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="tf__login_imput">
                                        <input type="password" name="password" placeholder="{{__('user.Password')}}">
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="tf__login_imput">
                                        <input type="password" name="password_confirmation" placeholder="{{__('user.Confirm Password')}}">
                                    </div>
                                </div>


                                @if($recaptcha_setting->status==1)
                                    <div class="col-xl-12 mb-3">
                                        <div class="g-recaptcha" data-sitekey="{{ $recaptcha_setting->site_key }}"></div>
                                    </div>
                                @endif

                                <div class="col-xl-12">
                                    <div class="tf__login_imput">
                                        <button type="submit" class="common_btn">{{__('user.Register')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <p class="create_account">{{__('user.Already have an account ?')}} <a href="{{ route('login') }}">{{__('user.Login here')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=========================
        SIGNIN END
    ==========================-->

    <script src="https://apis.google.com/js/platform.js" async defer></script>

    <script>

        // function isValidEmail(email) {
        //     // Regular expression for validating an email address
        //     var emailRegex = /\S+@\S+\.\S+/;
        //     return emailRegex.test(email);
        // }

        // // Function to get email address from the URL
        // function getEmailFromURL() {
        //     var urlParams = new URLSearchParams(window.location.search);
        //     return urlParams.get('email');
        // }

        // // Function to handle form submission
        // function submitForm() {
        //     var email = {!! json_encode($email) !!}; // Retrieve email from PHP variable
        //     console.log(email);
        //     var appUrl = "{{ env('APP_URL') }}";
        //     if (!email) {
        //         // Redirect to the authentication page
        //         window.location.href = appUrl + '/auth';
        //     }else{
        //         document.getElementById('emailInput').value = email;
        //     }
        // }

        // // Call the submitForm function when the page loads
        // window.onload = function() {
        //     submitForm();
        // };
    </script>

@endsection
