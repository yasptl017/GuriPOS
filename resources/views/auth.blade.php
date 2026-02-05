
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
                    <h1>{{__('user.Register')}} / LOGIN</h1>
                    <ul>
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
                        <h2>{{__('user.Registration')}} / Login</h2>
                        <p>sign in with google</p>

                            <form id="registerForm">
                                <div class="col-xl-12">
                                    <div class="tf__login_imput">
                                        <button onclick="googleSignIn()" type="button" class="common_btn">Sign in with Google</button>
                                    </div>
                                </div>
                            </form>
                        </div>

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
        // Initialize Google Sign-In

        // function initGoogleSignIn() {
        //     gapi.load('auth2', function() {
        //         var auth2 = gapi.auth2.init({
        //             client_id: '771542863570-sa14n0uagm4vn8tvavk2rjptd4euoag9.apps.googleusercontent.com' // Replace with your Google Client ID
        //         });

        //         // Attach click event to Google Sign-In button
        //         document.getElementById('googleSignInBtn').addEventListener('click', function() {
        //             auth2.signIn().then(function(googleUser) {
        //                 var profile = googleUser.getBasicProfile();
        //                 document.getElementById('emailInput').value = profile.getEmail();
        //                 document.getElementById('registerForm').submit(); // Submit the form after successful Google Sign-In
        //             });
        //         });
        //     });
        // }

        // Initialize Google Sign-In
        function googleSignIn() {
            // Replace YOUR_CLIENT_ID with your actual Google client ID
            var CLIENT_ID = '456750131432-eljd7rh95vadjq5dp7b0omk3kqb0etn7.apps.googleusercontent.com';
            var appUrl = "{{ env('APP_URL') }}";
            // Redirect URI where Google will redirect after authentication
            var REDIRECT_URI = appUrl + '/login-auth/';

            // Scope for accessing user's email address
            var SCOPE = 'https://www.googleapis.com/auth/userinfo.email';

            // Google OAuth URL
            var oauthUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' +
            'response_type=token&' +
            'client_id=' + encodeURIComponent(CLIENT_ID) + '&' +
            'redirect_uri=' + encodeURIComponent(REDIRECT_URI) + '&' +
            'scope=' + encodeURIComponent(SCOPE);

            // Redirecting the user to Google authentication

            window.location.href = oauthUrl;
        }
    </script>

@endsection
